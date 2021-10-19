<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
public function toArray($request)
{
return [
'id' => $this->id,
'username' => $this->username,
'email' => $this->email,
'name' => $this->name,
'is_active' => $this->is_active,
'created_at' => $this->created_at,
'updated_at' => $this->updated_at,
'added_by' => $this->added_by,
'updated_by' => $this->updated_by,
'login_reactive_time' => $this->login_reactive_time,
'login_retry_limit' => $this->login_retry_limit,
];
}
}
