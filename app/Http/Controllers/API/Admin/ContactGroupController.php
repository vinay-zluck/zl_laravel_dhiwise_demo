<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\BulkCreateContactGroupAPIRequest;
use App\Http\Requests\Admin\BulkUpdateContactGroupAPIRequest;
use App\Http\Requests\Admin\CreateContactGroupAPIRequest;
use App\Http\Requests\Admin\UpdateContactGroupAPIRequest;
use App\Http\Resources\Admin\ContactGroupCollection;
use App\Http\Resources\Admin\ContactGroupResource;
use App\Repositories\ContactGroupRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ContactGroupController extends AppBaseController
{
    private $contactGroupRepository;

    public function __construct(ContactGroupRepository $contactGroupRepository)
    {
        $this->contactGroupRepository = $contactGroupRepository;
    }

    /**
    * ContactGroup's Listing API. Default it will return 10 records.
    * Limit Param: limit
    * Skip Param: skip
    * 
    * @param  Request $request
    *
    * @return  ContactGroupCollection
    */
    public function index(Request $request): ContactGroupCollection
    {
        try {
            $contactGroups = $this->contactGroupRepository->all(
                $request->all(),
                $request->get('skip', null),
                $request->get('limit', null),
            );

            return new ContactGroupCollection($contactGroups);
        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Create ContactGroup with given payload.
    * 
    * @param  CreateContactGroupAPIRequest $request
    *
    * @return  ContactGroupResource
    */
    public function store(CreateContactGroupAPIRequest $request): ContactGroupResource
    {
        try {
            DB::beginTransaction();
            $contactGroup = $this->contactGroupRepository->create($request->all());

            DB::commit();
            return new ContactGroupResource($contactGroup);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Get single ContactGroup record.
    * 
    * @param  int $id
    *
    * @return  ContactGroupResource
    */
    public function show($id): ContactGroupResource
    {
        $contactGroup = $this->contactGroupRepository->findOrFail($id);

        return new ContactGroupResource($contactGroup);
    }

    /**
    * Update ContactGroup with given payload.
    * 
    * @param  UpdateContactGroupAPIRequest $request
    * @param  int $id
    *
    * @return  ContactGroupResource
    */
    public function update(UpdateContactGroupAPIRequest $request,$id): ContactGroupResource
    {
        try {
            DB::beginTransaction();
            $contactGroup = $this->contactGroupRepository->update($request->all(),$id);

            DB::commit();
            return new ContactGroupResource($contactGroup);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Delete given ContactGroup.
    *
    * @param  int $id
    *
    * @return  mixed
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->contactGroupRepository->delete($id);

            DB::commit();
            return $this->successResponse('ContactGroup deleted successfully');
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk create ContactGroup's.
    *
    * @param  BulkCreateContactGroupAPIRequest $request
    *
    * @return  ContactGroupCollection
    */
    public function bulkStore(BulkCreateContactGroupAPIRequest $request): ContactGroupCollection
    {
        try {
        DB::beginTransaction();
            $contactGroups = collect();

            $input = $request->get('data');
            foreach ($input as $key => $ContactGroupInput) {
                $contactGroups[$key] = $this->contactGroupRepository->create($ContactGroupInput);
            }

            DB::commit();
            return new ContactGroupCollection($contactGroups);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk update ContactGroup's data.
    *
    * @param  BulkUpdateContactGroupAPIRequest $request
    *
    * @return  ContactGroupCollection
    */
    public function bulkUpdate(BulkUpdateContactGroupAPIRequest $request): ContactGroupCollection
    {
        try {
            DB::beginTransaction();
            $contactGroups = collect();

            $input = $request->get('data');
            foreach ($input as $key => $ContactGroupInput) {
                $contactGroups[$key] = $this->contactGroupRepository->update($ContactGroupInput,$ContactGroupInput['id']);
            }

            DB::commit();
            return new ContactGroupCollection($contactGroups);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
