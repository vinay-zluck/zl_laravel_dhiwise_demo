<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Group extends Model
{
protected $table = 'groups';

protected $fillable = [
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'added_by',
                'updated_by',
    ];

protected $hidden = [
];

protected $casts = [
                'name' => 'string',
                'is_active' => 'boolean',
                'created_at' => 'date',
                'updated_at' => 'date',
                'added_by' => 'integer',
                'updated_by' => 'integer',
    ];
}
