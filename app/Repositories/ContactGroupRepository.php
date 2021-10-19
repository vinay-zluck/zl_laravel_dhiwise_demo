<?php

namespace App\Repositories;

use App\Models\ContactGroup;

class ContactGroupRepository extends BaseRepository
{
    protected $fieldSearchable = [
                    'contact_id',
                'group_id',
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
        return ContactGroup::class;
    }
}
