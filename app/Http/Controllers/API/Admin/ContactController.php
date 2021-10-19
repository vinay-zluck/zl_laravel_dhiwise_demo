<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\BulkCreateContactAPIRequest;
use App\Http\Requests\Admin\BulkUpdateContactAPIRequest;
use App\Http\Requests\Admin\CreateContactAPIRequest;
use App\Http\Requests\Admin\UpdateContactAPIRequest;
use App\Http\Resources\Admin\ContactCollection;
use App\Http\Resources\Admin\ContactResource;
use App\Repositories\ContactRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ContactController extends AppBaseController
{
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
    * Contact's Listing API. Default it will return 10 records.
    * Limit Param: limit
    * Skip Param: skip
    * 
    * @param  Request $request
    *
    * @return  ContactCollection
    */
    public function index(Request $request): ContactCollection
    {
        try {
            $contacts = $this->contactRepository->all(
                $request->all(),
                $request->get('skip', null),
                $request->get('limit', null),
            );

            return new ContactCollection($contacts);
        }catch(Exception $e){
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Create Contact with given payload.
    * 
    * @param  CreateContactAPIRequest $request
    *
    * @return  ContactResource
    */
    public function store(CreateContactAPIRequest $request): ContactResource
    {
        try {
            DB::beginTransaction();
            $contact = $this->contactRepository->create($request->all());

            DB::commit();
            return new ContactResource($contact);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Get single Contact record.
    * 
    * @param  int $id
    *
    * @return  ContactResource
    */
    public function show($id): ContactResource
    {
        $contact = $this->contactRepository->findOrFail($id);

        return new ContactResource($contact);
    }

    /**
    * Update Contact with given payload.
    * 
    * @param  UpdateContactAPIRequest $request
    * @param  int $id
    *
    * @return  ContactResource
    */
    public function update(UpdateContactAPIRequest $request,$id): ContactResource
    {
        try {
            DB::beginTransaction();
            $contact = $this->contactRepository->update($request->all(),$id);

            DB::commit();
            return new ContactResource($contact);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Delete given Contact.
    *
    * @param  int $id
    *
    * @return  mixed
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->contactRepository->delete($id);

            DB::commit();
            return $this->successResponse('Contact deleted successfully');
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk create Contact's.
    *
    * @param  BulkCreateContactAPIRequest $request
    *
    * @return  ContactCollection
    */
    public function bulkStore(BulkCreateContactAPIRequest $request): ContactCollection
    {
        try {
        DB::beginTransaction();
            $contacts = collect();

            $input = $request->get('data');
            foreach ($input as $key => $ContactInput) {
                $contacts[$key] = $this->contactRepository->create($ContactInput);
            }

            DB::commit();
            return new ContactCollection($contacts);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Bulk update Contact's data.
    *
    * @param  BulkUpdateContactAPIRequest $request
    *
    * @return  ContactCollection
    */
    public function bulkUpdate(BulkUpdateContactAPIRequest $request): ContactCollection
    {
        try {
            DB::beginTransaction();
            $contacts = collect();

            $input = $request->get('data');
            foreach ($input as $key => $ContactInput) {
                $contacts[$key] = $this->contactRepository->update($ContactInput,$ContactInput['id']);
            }

            DB::commit();
            return new ContactCollection($contacts);
        }catch(Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
