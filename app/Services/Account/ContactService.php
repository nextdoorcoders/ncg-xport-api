<?php

namespace App\Services\Account;

use App\Models\Account\Contact as ContactModel;
use App\Models\Account\User as UserModel;
use Exception;

class ContactService
{
    /**
     * @param UserModel $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allContacts(UserModel $user)
    {
        return $user->contacts()
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return ContactModel|null
     */
    public function createContact(UserModel $user, array $data)
    {
        /** @var ContactModel $contact */
        $contact = $user->contacts()
            ->create($data);

        return $this->readContact($contact, $user);
    }

    /**
     * @param ContactModel $contact
     * @param UserModel    $user
     * @return ContactModel|null
     */
    public function readContact(ContactModel $contact, UserModel $user)
    {
        return $contact->fresh();
    }

    /**
     * @param ContactModel $contact
     * @param UserModel    $user
     * @param array        $data
     */
    public function updateContact(ContactModel $contact, UserModel $user, array $data)
    {
        $contact->fill($data);
        $contact->save();

        return $this->readContact($contact, $user);
    }

    /**
     * @param ContactModel $contact
     * @param UserModel    $user
     * @throws Exception
     */
    public function deleteContact(ContactModel $contact, UserModel $user)
    {
        try {
            $contact->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
