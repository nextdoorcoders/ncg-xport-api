<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Contact as ContactRequest;
use App\Http\Resources\Account\Contact as ContactResource;
use App\Http\Resources\Account\ContactCollection;
use App\Models\Account\Contact as ContactModel;
use App\Models\Account\User as UserModel;
use App\Services\Account\ContactService;
use Exception;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    protected ContactService $contactService;

    /**
     * ContactController constructor.
     *
     * @param ContactService $contactService
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * @return ContactCollection
     */
    public function allContacts()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->contactService->allContacts($user);

        return new ContactCollection($response);
    }

    /**
     * @param ContactRequest $request
     * @return ContactResource
     */
    public function createContact(ContactRequest $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->contactService->createContact($user, $data);

        return new ContactResource($response);
    }

    /**
     * @param ContactModel $contact
     * @return ContactResource
     */
    public function readContact(ContactModel $contact)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->contactService->readContact($contact, $user);

        return new ContactResource($response);
    }

    /**
     * @param ContactRequest $request
     * @param ContactModel   $contact
     * @return ContactResource
     */
    public function updateContact(ContactRequest $request, ContactModel $contact)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->contactService->updateContact($contact, $user, $data);

        return new ContactResource($response);
    }

    /**
     * @param ContactModel $contact
     * @return Response
     * @throws Exception
     */
    public function deleteContact(ContactModel $contact)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->contactService->deleteContact($contact, $user);

        return response()->noContent();
    }
}
