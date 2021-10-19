<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class ContactGroup extends Model
{
protected $table = 'contact_groups';

protected $fillable = [
                'contact_id',
                'group_id',
                'is_active',
                'created_at',
                'updated_at',
                'added_by',
                'updated_by',
    ];

protected $hidden = [
];

protected $casts = [
                'contact_id' => 'string',
                'group_id' => 'string',
                'is_active' => 'boolean',
                'created_at' => 'date',
                'updated_at' => 'date',
                'added_by' => 'integer',
                'updated_by' => 'integer',
    ];
}
