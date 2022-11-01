<?php

namespace Encoda\BCP\Services\Concrete;

use Encoda\BCP\Http\Requests\Report\CreateBCPRequest;
use Encoda\BCP\Http\Requests\Report\UpdateBCPRequest;
use Encoda\BCP\Models\BCP;
use Encoda\BCP\Repositories\Concrete\BCPRepository;
use Encoda\BCP\Repositories\Interfaces\BCPRepositoryInterface;
use Encoda\BCP\Services\Interfaces\BCPServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Throwable;

/**
 *
 */
class BCPService implements BCPServiceInterface
{
    /**
     * @param DocumentServiceInterface $documentService
     * @param BCPRepositoryInterface   $bcpRepository
     */
    public function __construct(
        protected DocumentServiceInterface $documentService,
        protected BCPRepositoryInterface   $bcpRepository
    )
    {
    }


    /**
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function list()
    {
        $query = tenant()
            ->bcp();

        $this->applySearchFilter($query);
        $this->applySortFilter($query);

        return  $this->bcpRepository->applyPaging($query);

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
            ->setTable(BCP::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['search', 'name', 'description', 'status', 'due_date', 'created_at'])
            ->setCustomFilter('search', static function ($query, $type, $column, $value) {
                // Group where name like and description like
                $query->where( function( $query) use ( $value) {
                    $query->where('name', 'LIKE', '%' . $value . '%')
                        ->orWhere('description', 'LIKE', '%' . $value . '%');
                });

            })
            ->validate()
            ->applyFilter()
        ;
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
            ->setTable(BCP::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'description', 'status', 'due_date'])
            ->validate()
            ->applySort();
    }

    /**
     * @param string $uid
     *
     * @return BCP
     * @throws NotFoundException
     */
    public function getBCP(string $uid)
    {
        $bcp = $this->bcpRepository->query()
            ->hasUID($uid)
            ->get()
            ->first();

        if (!$bcp) {
            throw new NotFoundException('BCP not found');
        }

        return $bcp->setAppends(['reports']);
    }

    /**
     * @param CreateBCPRequest $request
     *
     * @return BCP
     * @throws ServerErrorException
     */
    public function create(CreateBCPRequest $request)
    {
        try{
            DB::beginTransaction();
            /**
             * @var Resource $resource
             * @var BCP      $bcp
             */
            $bcp = $this->bcpRepository->create($request->all());

            if ($request->has('reports')) {
                $reports = $request->get('reports');
                foreach ($reports as $report) {
                    $document = $this->documentService->getDocument($report['uid']);
                    $bcp->addDocument($document, 'reports');
                }
            }
            DB::commit();
        }
        catch (Throwable $e){
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create BCP error');
        }


        return $bcp->refresh()->setAppends(['reports']);
    }

    /**
     * @param UpdateBCPRequest $request
     * @param string           $uid
     *
     * @return mixed
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function update(UpdateBCPRequest $request, string $uid): mixed
    {
        $bcp = $this->getBCP($uid);
        try{
            DB::beginTransaction();

            /**
             * @var Resource $resource
             */
            $bcp = $this->bcpRepository->update($request->all(), $bcp->id);

            if ($request->has('reports')) {
                $reports = $request->get('reports');
                foreach ($reports as $report) {
                    $document = $this->documentService->getDocument($report['uid']);
                    $bcp->addDocument($document, 'reports');
                }
            }
            DB::commit();
        }
        catch (Throwable $e){
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Update BCP error');
        }

        return $bcp->refresh()->setAppends(['reports']);
    }

    /**
     * @param string $uid
     *
     * @return bool|null
     * @throws NotFoundException
     */
    public function delete(string $uid)
    {
        return $this->getBCP($uid)->delete();
    }

    /**
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function top()
    {

        return $this->bcpRepository
            ->orderBy('created_at', 'DESC')
            ->take( config('dashboard.bcp.rows') ?? 2 )
            ->all()
            ;
    }
}
