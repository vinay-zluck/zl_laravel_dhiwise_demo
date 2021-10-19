<?php

namespace App\Http\Controllers\API\Device;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Device\BulkCreateUserAPIRequest;
use App\Http\Requests\Device\BulkUpdateUserAPIRequest;
use App\Http\Requests\Device\CreateUserAPIRequest;
use App\Http\Requests\Device\UpdateUserAPIRequest;
use App\Http\Resources\Device\UserCollection;
use App\Http\Resources\Device\UserResource;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserController extends AppBaseController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
    * User's Listing API. Default it will return 10 records.
    * Limit Param: limit
    * Skip Param: skip
    * 
    * @param  Request $request
    *
    * @return  UserCollection
    */
    public function index(Request $request): UserCollection
    {
        try {
            $users = $this->userRepository->all(
                $request->all(),
                $request->get('skip', null),
                $request->get('limit', null),
            );

            return new UserCollection($users);
        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Create User with given payload.
    * 
    * @param  CreateUserAPIRequest $request
    *
    * @return  UserResource
    */
    public function store(CreateUserAPIRequest $request): UserResource
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($request->all());

            DB::commit();
            return new UserResource($user);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Get single User record.
    * 
    * @param  int $id
    *
    * @return  UserResource
    */
    public function show($id): UserResource
    {
        $user = $this->userRepository->findOrFail($id);

        return new UserResource($user);
    }

    /**
    * Update User with given payload.
    * 
    * @param  UpdateUserAPIRequest $request
    * @param  int $id
    *
    * @return  UserResource
    */
    public function update(UpdateUserAPIRequest $request,$id): UserResource
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->update($request->all(),$id);

            DB::commit();
            return new UserResource($user);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Delete given User.
    *
    * @param  int $id
    *
    * @return  mixed
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->delete($id);

            DB::commit();
            return $this->successResponse('User deleted successfully');
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk create User's.
    *
    * @param  BulkCreateUserAPIRequest $request
    *
    * @return  UserCollection
    */
    public function bulkStore(BulkCreateUserAPIRequest $request): UserCollection
    {
        try {
        DB::beginTransaction();
            $users = collect();

            $input = $request->get('data');
            foreach ($input as $key => $UserInput) {
                $users[$key] = $this->userRepository->create($UserInput);
            }

            DB::commit();
            return new UserCollection($users);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk update User's data.
    *
    * @param  BulkUpdateUserAPIRequest $request
    *
    * @return  UserCollection
    */
    public function bulkUpdate(BulkUpdateUserAPIRequest $request): UserCollection
    {
        try {
            DB::beginTransaction();
            $users = collect();

            $input = $request->get('data');
            foreach ($input as $key => $UserInput) {
                $users[$key] = $this->userRepository->update($UserInput,$UserInput['id']);
            }

            DB::commit();
            return new UserCollection($users);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
