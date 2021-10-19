<?php

namespace App\Http\Resources\Device;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
public function toArray($request)
{
return [
'id' => $this->id,
'name' => $this->name,
'is_active' => $this->is_active,
'created_at' => $this->created_at,
'updated_at' => $this->updated_at,
'added_by' => $this->added_by,
'updated_by' => $this->updated_by,
];
}
}
