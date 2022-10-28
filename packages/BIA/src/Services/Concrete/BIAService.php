<?php

namespace Encoda\BIA\Services\Concrete;

use Encoda\BIA\Http\Requests\Report\CreateBIARequest;
use Encoda\BIA\Http\Requests\Report\UpdateBIARequest;
use Encoda\BIA\Models\BIA;
use Encoda\BIA\Repositories\Interfaces\BIARepositoryInterface;
use Encoda\BIA\Services\Interfaces\BIAServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Throwable;

/**
 *
 */
class BIAService implements BIAServiceInterface
{
    /**
     * @param DocumentServiceInterface $documentService
     * @param BIARepositoryInterface   $biaRepository
     */
    public function __construct(
        protected DocumentServiceInterface $documentService,
        protected BIARepositoryInterface $biaRepository
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
            ->bia();

        $this->applySearchFilter($query);
        $this->applySortFilter($query);

        return
            $this->biaRepository->applyPaging($query);

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
            ->setTable(BIA::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['search', 'name', 'description', 'status', 'due_date','created_at'])
            ->setCustomFilter('search', static function ($query, $type, $column, $value) {
                $query->where('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('description', 'LIKE', '%' . $value . '%');
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
            ->setTable(BIA::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'description', 'status', 'due_date'])
            ->validate()
            ->applySort();
    }

    /**
     * @param string $uid
     *
     * @return BIA
     * @throws NotFoundException
     */
    public function getBIA(string $uid)
    {
        $bia = $this->biaRepository->findByUid( $uid );

        if (!$bia) {
            throw new NotFoundException('BIA not found');
        }

        return $bia->setAppends(['reports']);
    }

    /**
     * @param CreateBIARequest $request
     *
     * @return BIA
     * @throws ServerErrorException
     */
    public function create(CreateBIARequest $request)
    {
        try{
            DB::beginTransaction();
            /**
             * @var Resource $resource
             * @var BIA      $bia
             */
            $bia = $this->biaRepository->create($request->all());

            if ($request->has('reports')) {
                $reports = $request->get('reports');
                foreach ($reports as $report) {
                    $document = $this->documentService->getDocument($report['uid']);
                    $bia->addDocument($document, 'reports');
                }
            }
            DB::commit();
        }
        catch (Throwable $e){
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create BIA error');
        }


        return $bia->refresh()->setAppends(['reports']);
    }

    /**
     * @param UpdateBIARequest $request
     * @param string           $uid
     *
     * @return mixed
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function update(UpdateBIARequest $request, string $uid)
    {
        $bia = $this->getBIA($uid);
        try{
            DB::beginTransaction();

            /**
             * @var Resource $resource
             */
            $bia = $this->biaRepository->update($request->all(), $bia->id);

            if ($request->has('reports')) {
                $reports = $request->get('reports');
                foreach ($reports as $report) {
                    $document = $this->documentService->getDocument($report['uid']);
                    $bia->addDocument($document, 'reports');
                }
            }
            DB::commit();
        }
        catch (Throwable $e){
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Update BIA error');
        }

        return $bia->refresh()->setAppends(['reports']);
    }

    /**
     * @param string $uid
     *
     * @return bool|null
     * @throws NotFoundException
     */
    public function delete(string $uid)
    {
        return $this->getBIA($uid)->delete();
    }
}
