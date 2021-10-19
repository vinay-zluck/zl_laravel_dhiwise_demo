<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\Admin\PermissionCollection;
use App\Http\Resources\Admin\PermissionResource;
use App\Repositories\PermissionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PermissionController extends AppBaseController
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
    * Return lists of permissions.
    * 
    * @param  Request $request
    *
    * @return  PermissionCollection
    */
    public function index(Request $request): PermissionCollection
    {
        try {
            $permissions = $this->permissionRepository->all(
                $request->all(),
                $request->get('skip', null),
                $request->get('limit', null),
            );
        
            return new PermissionCollection($permissions);
        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }   
    }

    /**
    * Return permission with given ID.
    * 
    * @param  $id
    *
    * @return  PermissionResource
    */
    public function show($id): PermissionResource
    {
        $permission = $this->permissionRepository->findOrFail($id);

        return new PermissionResource($permission);
    }
}
