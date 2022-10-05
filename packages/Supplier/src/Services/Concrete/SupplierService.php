<?php

namespace Encoda\Supplier\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Supplier\Exports\SupplierExport;
use Encoda\Supplier\Http\Requests\Supplier\CreateSupplierRequest;
use Encoda\Supplier\Http\Requests\Supplier\UpdateSupplierRequest;
use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;
use Encoda\Supplier\Services\Interfaces\SupplierServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    )
    {
    }


    /**
     * @return LengthAwarePaginator
     * @throws NotFoundException
     */
    public function listSupplier()
    {
        return tenant()
            ->suppliers()
            ->with('categories')
            ->paginate(config('config.pagination_size'))
            ;
    }

    /**
     * @param              $uid
     *
     * @return mixed
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

        return $supplier->load(['categories']);
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

            /** @var Resource $resource */
            $supplier = $this->supplierRepository->create($request->all());

            $supplier->categories()->sync($categoryIDs);

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
        $supplier = $this->getSupplier( $uid );

        try {

            DB::beginTransaction();
            /** @var Resource $resourceUpdated */
            $supplier = $this->supplierRepository->update( $request->all(), $supplier->id );

            if($request->has('categories')){
                $categoryIDs = $this->supplierCategoryRepository
                    ->query()
                    ->whereIn('uid', collect($request->get('categories'))->pluck('uid'))
                    ->get()->pluck('id');
                $supplier->categories()->sync( $categoryIDs );
            }

            DB::commit();
        }
        catch ( Throwable $e ) {
            DB::rollBack();
            Log::error( $e );
            throw new ServerErrorException( 'Oops! Update supplier error' );
        }

        return $supplier->refresh()->load('categories' );
    }

    /**
     * @param string       $uid
     *
     * @return int
     * @throws NotFoundException
     */
    public function deleteSupplier(string $uid)
    {
        $supplier = $this->getSupplier(  $uid );

        return $this->supplierRepository->delete( $supplier->id );
    }

    /**
     * @param $category
     * @param $range
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($category = null, $range = 'all')
    {
        $fileName = 'Suppliers_'. time(). '.xlsx';

        $data = $this->supplierRepository->with(['categories'])->all();

        $export = new SupplierExport( $data );

        return Excel::download( $export, $fileName );
    }
}
