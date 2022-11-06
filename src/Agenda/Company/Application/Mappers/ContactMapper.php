<?php

namespace Src\Agenda\Company\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Model\ValueObjects\ContactRole;
use Src\Agenda\Company\Domain\Model\ValueObjects\Email;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Agenda\Company\Infrastructure\EloquentModels\ContactEloquentModel;

class ContactMapper
{
    public static function fromRequest(Request $request, ?int $contact_id = null): Contact
    {
        return new Contact(
            id: $contact_id,
            contact_role: $request->enum('contact_role', ContactRole::class),
            name: new Name($request->string('name')),
            email: new Email($request->string('email')),
            phone: new Phone($request->string('phone')),
            address_id: $request->input('address_id'),
        );
    }

    public static function fromArray(array $contact): Contact
    {
        $contactEloquentModel = new ContactEloquentModel($contact);
        $contactEloquentModel->id = $contact['id'] ?? null;
        return self::fromEloquent($contactEloquentModel);
    }

    public static function fromEloquent(ContactEloquentModel $contactEloquent): Contact
    {
        return new Contact(
            id: $contactEloquent->id,
            contact_role: ContactRole::from($contactEloquent->contact_role),
            name: new Name($contactEloquent->name),
            email: new Email($contactEloquent->email),
            phone: new Phone($contactEloquent->phone),
            address_id: $contactEloquent->address_id,
        );
    }

    public static function toEloquent(Contact $contact): ContactEloquentModel
    {
        $contactEloquent = new ContactEloquentModel();
        if ($contact->id) {
            $contactEloquent = ContactEloquentModel::query()->findOrFail($contact->id);
        }
        $contactEloquent->address_id = $contact->address_id;
        $contactEloquent->contact_role = $contact->contact_role->value;
        $contactEloquent->name = $contact->name;
        $contactEloquent->email = $contact->email;
        $contactEloquent->phone = $contact->phone;
        return $contactEloquent;
    }
}