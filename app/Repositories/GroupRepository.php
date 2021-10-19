<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository extends BaseRepository
{
    protected $fieldSearchable = [
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
        return Group::class;
    }
}
