<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
                    'username',
                'password',
                'email',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'added_by',
                'updated_by',
        ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return User::class;
    }
}
