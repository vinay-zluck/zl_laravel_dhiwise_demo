<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\BulkCreateRoleAPIRequest;
use App\Http\Requests\Admin\BulkUpdateRoleAPIRequest;
use App\Http\Requests\Admin\CreateRoleAPIRequest;
use App\Http\Requests\Admin\UpdateRoleAPIRequest;
use App\Http\Resources\Admin\RoleCollection;
use App\Http\Resources\Admin\RoleResource;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RoleController extends AppBaseController
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
    * Return Lists of roles.
    *
    * @param  Request $request
    *
    * @return  RoleCollection
    */
    public function index(Request $request): RoleCollection
    {
        try {
            $roles = $this->roleRepository->all(
                $request->all(),
                $request->get('skip', null),
                $request->get('limit', null),
            );

            return new RoleCollection($roles);
        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Create new roles with given permissions.
    *
    * @param  CreateRoleAPIRequest $request
    *
    * @return  RoleResource
    */
    public function store(CreateRoleAPIRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $role = $this->roleRepository->create($input);

            if (isset($input['permissions']) && ! empty($input['permissions'])) {
                $role->syncPermissions($input['permissions']);
            }
            DB::commit();
            return new RoleResource($role);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Return role with given ID.
    * 
    * @param  int $id
    *
    * @return  RoleResource
    */
    public function show($id): RoleResource
    {
        $role = $this->roleRepository->findOrFail($id);

        return new RoleResource($role);
    }

    /**
    * Update role with given payload.
    *
    * @param  UpdateRoleAPIRequest $request
    * @param  int $id
    *
    * @return  RoleResource
    */
    public function update(UpdateRoleAPIRequest $request,$id): RoleResource
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            $role = $this->roleRepository->update($input,$id);

            if (isset($input['permissions']) && ! empty($input['permissions'])) {
                $role->syncPermissions($input['permissions']);
            }

            DB::commit();
            return new RoleResource($role);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Delete role with given ID.
    *
    * @param  int $id
    *
    * @return  mixed
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->roleRepository->delete($id);

            DB::commit();
            return $this->successResponse('Role deleted successfully');
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Create multiple roles with related permissions.
    *
    * @param  BulkCreateRoleAPIRequest $request
    *
    * @return  RoleCollection
    */
    public function bulkStore(BulkCreateRoleAPIRequest $request): RoleCollection
    {
        try {
        DB::beginTransaction();
            $roles = collect();

            $input = $request->get('data');
            foreach ($input as $key => $RoleInput) {
                $roles[$key] = $this->roleRepository->create($RoleInput);
                if (isset($input['permissions']) && ! empty($input['permissions'])) {
                    $roles[$key]->syncPermissions(RoleInput['permissions']);
                }
            }

            DB::commit();
            return new RoleCollection($roles);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Update multiple roles with given payload.
    *
    * @param  BulkUpdateRoleAPIRequest $request
    *
    * @return  RoleCollection
    */
    public function bulkUpdate(BulkUpdateRoleAPIRequest $request): RoleCollection
    {
        try {
            DB::beginTransaction();
            $roles = collect();

            $input = $request->get('data');
            foreach ($input as $key => $RoleInput) {
                $roles[$key] = $this->roleRepository->update($RoleInput,$RoleInput['id']);
                if (isset($input['permissions']) && ! empty($input['permissions'])) {
                    $roles[$key]->syncPermissions($RoleInput['permissions']);
                }
            }

            DB::commit();
            return new RoleCollection($roles);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
