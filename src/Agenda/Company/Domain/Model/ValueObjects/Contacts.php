<?php

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Common\Domain\Exceptions\EntityNotFoundException;
use Src\Common\Domain\ValueObjectArray;

final class Contacts extends ValueObjectArray
{
    public readonly array $contacts;

    public function __construct(array $contacts = [])
    {
        parent::__construct($contacts);

        foreach ($contacts as $contact) {
            if (!$contact instanceof Contact) {
                throw new \InvalidArgumentException('Invalid contact');
            }
        }
        $this->contacts = $contacts;
    }

    public function add(Contact $contact): void
    {
        $this->append($contact);
    }

    public function update(Contact $newContact): void
    {
        $contactIds = array_column($this->contacts, 'id');
        if (!in_array($newContact->id, $contactIds)) {
            throw new EntityNotFoundException('Contact not found');
        }
        $this->offsetSet(array_search($newContact->id, $contactIds), $newContact);
    }

    public function remove(int $contact_id): void
    {
        $contactIds = array_column($this->contacts, 'id');
        if (!in_array($contact_id, $contactIds)) {
            throw new EntityNotFoundException('Contact not found');
        }
        $this->offsetUnset(array_search($contact_id, $contactIds));
    }

    public function jsonSerialize(): array
    {
        return $this->contacts;
    }
}