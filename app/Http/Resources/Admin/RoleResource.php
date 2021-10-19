<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
public function toArray($request)
{
return [
'id' => $this->id,
'name' => $this->name,
'guard_name' => $this->guard_name,
'created_at' => $this->created_at,
'updated_at' => $this->updated_at,
'permissions' => $this->permissions,
];
}
}
