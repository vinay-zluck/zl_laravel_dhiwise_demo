<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\BulkCreateGroupAPIRequest;
use App\Http\Requests\Admin\BulkUpdateGroupAPIRequest;
use App\Http\Requests\Admin\CreateGroupAPIRequest;
use App\Http\Requests\Admin\UpdateGroupAPIRequest;
use App\Http\Resources\Admin\GroupCollection;
use App\Http\Resources\Admin\GroupResource;
use App\Repositories\GroupRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class GroupController extends AppBaseController
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
    * Group's Listing API. Default it will return 10 records.
    * Limit Param: limit
    * Skip Param: skip
    * 
    * @param  Request $request
    *
    * @return  GroupCollection
    */
    public function index(Request $request): GroupCollection
    {
        try {
            $groups = $this->groupRepository->all(
                $request->all(),
                $request->get('skip', null),
                $request->get('limit', null),
            );

            return new GroupCollection($groups);
        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Create Group with given payload.
    * 
    * @param  CreateGroupAPIRequest $request
    *
    * @return  GroupResource
    */
    public function store(CreateGroupAPIRequest $request): GroupResource
    {
        try {
            DB::beginTransaction();
            $group = $this->groupRepository->create($request->all());

            DB::commit();
            return new GroupResource($group);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Get single Group record.
    * 
    * @param  int $id
    *
    * @return  GroupResource
    */
    public function show($id): GroupResource
    {
        $group = $this->groupRepository->findOrFail($id);

        return new GroupResource($group);
    }

    /**
    * Update Group with given payload.
    * 
    * @param  UpdateGroupAPIRequest $request
    * @param  int $id
    *
    * @return  GroupResource
    */
    public function update(UpdateGroupAPIRequest $request,$id): GroupResource
    {
        try {
            DB::beginTransaction();
            $group = $this->groupRepository->update($request->all(),$id);

            DB::commit();
            return new GroupResource($group);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Delete given Group.
    *
    * @param  int $id
    *
    * @return  mixed
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->groupRepository->delete($id);

            DB::commit();
            return $this->successResponse('Group deleted successfully');
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk create Group's.
    *
    * @param  BulkCreateGroupAPIRequest $request
    *
    * @return  GroupCollection
    */
    public function bulkStore(BulkCreateGroupAPIRequest $request): GroupCollection
    {
        try {
        DB::beginTransaction();
            $groups = collect();

            $input = $request->get('data');
            foreach ($input as $key => $GroupInput) {
                $groups[$key] = $this->groupRepository->create($GroupInput);
            }

            DB::commit();
            return new GroupCollection($groups);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk update Group's data.
    *
    * @param  BulkUpdateGroupAPIRequest $request
    *
    * @return  GroupCollection
    */
    public function bulkUpdate(BulkUpdateGroupAPIRequest $request): GroupCollection
    {
        try {
            DB::beginTransaction();
            $groups = collect();

            $input = $request->get('data');
            foreach ($input as $key => $GroupInput) {
                $groups[$key] = $this->groupRepository->update($GroupInput,$GroupInput['id']);
            }

            DB::commit();
            return new GroupCollection($groups);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
