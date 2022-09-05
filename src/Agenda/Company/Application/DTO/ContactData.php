<?php

namespace Src\Agenda\Company\Application\DTO;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Model\ValueObjects\ContactRole;
use Src\Agenda\Company\Domain\Model\ValueObjects\Email;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Agenda\Company\Infrastructure\EloquentModels\ContactEloquentModel;

class ContactData
{
    public static function fromRequest(Request $request, ?int $contact_id = null): Contact
    {
        return new Contact(
            id: $contact_id,
            contact_role: ContactRole::from($request->input('contact_role')),
            name: new Name($request->input('name')),
            email: new Email($request->input('email')),
            phone: new Phone($request->input('phone')),
            address_id: $request->input('address_id'),
        );
    }

    public static function fromArray(array $contact): Contact
    {
        return new Contact(
            id: $contact['id'] ?? null,
            contact_role: ContactRole::from($contact['contact_role']),
            name: new Name($contact['name']),
            email: new Email($contact['email']),
            phone: new Phone($contact['phone']),
            address_id: $contact['address_id'] ?? null,
        );
    }

    public static function fromEloquent(ContactEloquentModel $contactEloquentModel): Contact
    {
        return new Contact(
            id: $contactEloquentModel->id,
            contact_role: ContactRole::from($contactEloquentModel->contact_role),
            name: new Name($contactEloquentModel->name),
            email: new Email($contactEloquentModel->email),
            phone: new Phone($contactEloquentModel->phone),
            address_id: $contactEloquentModel->address_id,
        );
    }

    public static function toEloquent(Contact $contact): ContactEloquentModel
    {
        $contactEloquent = new ContactEloquentModel();
        if ($contact->id) {
            $contactEloquent = ContactEloquentModel::query()->find($contact->id);
        }
        $contactEloquent->address_id = $contact->address_id;
        $contactEloquent->contact_role = $contact->contact_role;
        $contactEloquent->name = $contact->name;
        $contactEloquent->email = $contact->email;
        $contactEloquent->phone = $contact->phone;
        return $contactEloquent;
    }
}