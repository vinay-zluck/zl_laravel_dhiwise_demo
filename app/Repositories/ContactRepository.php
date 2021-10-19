<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository extends BaseRepository
{
    protected $fieldSearchable = [
                    'name',
                'contact_no',
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
        return Contact::class;
    }
}
