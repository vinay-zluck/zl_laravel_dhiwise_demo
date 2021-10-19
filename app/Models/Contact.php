<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Contact extends Model
{
protected $table = 'contacts';

protected $fillable = [
                'name',
                'contact_no',
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
                'contact_no' => 'string',
                'is_active' => 'boolean',
                'created_at' => 'date',
                'updated_at' => 'date',
                'added_by' => 'integer',
                'updated_by' => 'integer',
    ];
}
