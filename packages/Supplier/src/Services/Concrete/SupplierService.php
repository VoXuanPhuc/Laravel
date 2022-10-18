<?php

namespace Encoda\Supplier\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Facades\Context;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Encoda\Supplier\Exports\SupplierExport;
use Encoda\Supplier\Http\Requests\Supplier\CreateSupplierRequest;
use Encoda\Supplier\Http\Requests\Supplier\UpdateSupplierRequest;
use Encoda\Supplier\Models\Supplier;
use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;
use Encoda\Supplier\Services\Interfaces\SupplierServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

/**
 *
 */
class SupplierService implements SupplierServiceInterface
{
    /**
     * @param SupplierRepositoryInterface         $supplierRepository
     * @param SupplierCategoryRepositoryInterface $supplierCategoryRepository
     */
    public function __construct(
        protected SupplierRepositoryInterface         $supplierRepository,
        protected SupplierCategoryRepositoryInterface $supplierCategoryRepository,
        protected DocumentServiceInterface            $documentService
    )
    {
    }


    /**
     * @return LengthAwarePaginator
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function listSupplier()
    {
        $query = tenant()
            ->suppliers()
            ->with('categories');

        $this->applySearchFilter($query);
        $this->applySortFilter($query);

        return
            $this->supplierRepository->applyPaging($query);

    }

    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySearchFilter($query): void
    {
        //Apply filter
        FilterFluent::init()
            ->setTable(Supplier::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'search','description', 'email', 'fax', 'phone_number', 'is_active'])
            ->setCustomFilter('search', static function ($query, $type, $column, $value){
                $query->where('name', 'LIKE', '%' . $value . '%' )
                    ->orWhere('description', 'LIKE', '%' . $value . '%')
                    ->orWhere('email', 'LIKE', '%' . $value. '%');
            })
            ->validate()
            ->applyFilter();
    }

    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySortFilter($query): void
    {
        //Apply sort
        SortFluent::init()
            ->setTable(Supplier::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'email', 'is_active'])
            ->validate()
            ->applySort();
    }

    /**
     * @param              $uid
     *
     * @return Supplier
     * @throws NotFoundException
     */
    public function getSupplier($uid): mixed
    {
        $supplier = $this->supplierRepository->query()
            ->hasUID($uid)
            ->get()
            ->first();

        if (!$supplier) {
            throw new NotFoundException('Supplier not found');
        }

        return $supplier->setAppends(['certs'])->load(['categories']);
    }

    /**
     * @param CreateSupplierRequest $request
     *
     * @return mixed
     * @throws ServerErrorException
     */
    public function createSupplier(CreateSupplierRequest $request): mixed
    {
        $categoryIDs = $this->supplierCategoryRepository
            ->query()
            ->whereIn('uid', collect($request->get('categories'))->pluck('uid'))
            ->get()->pluck('id');

        try {
            DB::beginTransaction();

            /**
             * @var Resource $resource
             * @var Supplier $supplier
             */
            $supplier = $this->supplierRepository->create($request->all());

            $supplier->categories()->sync($categoryIDs);
            if($request->has('certs')){
                $certs = $request->get('certs');
                foreach($certs as $cert){
                    $document = $this->documentService->getDocument($cert['uid'] ?? null);
                    $supplier->addDocument($document, 'certs');
                }
            }
            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create supplier error');
        }

        return $supplier->refresh()->load(['categories']);
    }

    /**
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function updateSupplier(UpdateSupplierRequest $request, string $uid)
    {
        $supplier = $this->getSupplier($uid);

        try {

            DB::beginTransaction();
            /** @var Resource $resourceUpdated */
            $supplier = $this->supplierRepository->update($request->all(), $supplier->id);

            if ($request->has('categories')) {
                $categoryIDs = $this->supplierCategoryRepository
                    ->query()
                    ->whereIn('uid', collect($request->get('categories'))->pluck('uid'))
                    ->get()->pluck('id');
                $supplier->categories()->sync($categoryIDs);
            }

            if($request->has('certs')){
                $certs = $request->get('certs');
                foreach($certs as $cert){
                    $document = $this->documentService->getDocument($cert['uid'] ?? null);
                    $supplier->addDocument($document, 'certs');
                }
            }


            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Update supplier error');
        }

        return $supplier->refresh()->load('categories');
    }

    /**
     * @param string $uid
     *
     * @return int
     * @throws NotFoundException
     */
    public function deleteSupplier(string $uid)
    {
        $supplier = $this->getSupplier($uid);
        $supplier->clearDocumentCollection('certs');
        return $this->supplierRepository->delete($supplier->id);
    }

    /**
     * @param $category
     * @param $range
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($category = null, $range = 'all')
    {
        $fileName = 'Suppliers_' . time() . '.xlsx';

        $data = $this->supplierRepository->with(['categories'])->all();

        $export = new SupplierExport($data);

        return Excel::download($export, $fileName);
    }
}
