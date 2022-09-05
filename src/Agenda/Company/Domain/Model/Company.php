<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model;

use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;
use Src\Common\Domain\AggregateRoot;
use Src\Common\Domain\Exceptions\EntityNotFoundException;

class Company extends AggregateRoot implements \JsonSerializable
{
    public function __construct(
        public readonly ?int $id,
        public readonly FiscalName $fiscal_name,
        public readonly SocialName $social_name,
        public readonly Vat $vat,
        public array $addresses = [],
        public array $departments = [],
        public array $contacts = [],
        public readonly bool $is_active = true
    ) {}

    public function addAddress(Address $address): void
    {
        $this->addresses[] = $address;
    }

    public function updateAddress(Address $newAddress): void
    {
        $addressIds = array_column($this->addresses, 'id');
        if (!in_array($newAddress->id, $addressIds)) {
            throw new EntityNotFoundException('Address not found');
        }
        $this->addresses[array_search($newAddress->id, $addressIds)] = $newAddress;
    }

    public function removeAddress(int $address_id): void
    {
        $this->addresses = array_filter($this->addresses, function ($item) use ($address_id) {
            return $item['id'] !== $address_id;
        });
    }

    public function addDepartment(Department $department): void
    {
        $this->departments[] = $department;
    }

    public function updateDepartment(Department $newDepartment): void
    {
        $departmentIds = array_column($this->departments, 'id');
        if (!in_array($newDepartment->id, $departmentIds)) {
            throw new EntityNotFoundException('Department not found');
        }
        $this->departments[array_search($newDepartment->id, $departmentIds)] = $newDepartment;
    }

    public function removeDepartment(int $department_id): void
    {
        $this->departments = array_filter($this->departments, function ($item) use ($department_id) {
            return $item['id'] !== $department_id;
        });
    }

    public function addContact(Contact $contact): void
    {
        $this->contacts[] = $contact;
    }

    public function updateContact(Contact $newContact): void
    {
        $contactIds = array_column($this->contacts, 'id');
        if (!in_array($newContact->id, $contactIds)) {
            throw new EntityNotFoundException('Contact not found');
        }
        $this->contacts[array_search($newContact->id, $contactIds)] = $newContact;
    }

    public function removeContact(int $contact_id): void
    {
        $this->contacts = array_filter($this->contacts, function ($item) use ($contact_id) {
            return $item['id'] !== $contact_id;
        });
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fiscal_name' => $this->fiscal_name,
            'social_name' => $this->social_name,
            'vat' => $this->vat,
            'addresses' => $this->addresses,
            'departments' => $this->departments,
            'contacts' => $this->contacts,
            'is_active' => $this->is_active,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}