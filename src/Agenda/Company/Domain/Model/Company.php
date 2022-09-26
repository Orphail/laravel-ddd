<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model;

use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\Entities\Contact;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Model\ValueObjects\Addresses;
use Src\Agenda\Company\Domain\Model\ValueObjects\Contacts;
use Src\Agenda\Company\Domain\Model\ValueObjects\Departments;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;
use Src\Common\Domain\AggregateRoot;

class Company extends AggregateRoot implements \JsonSerializable
{
    public function __construct(
        public readonly ?int $id,
        public readonly FiscalName $fiscal_name,
        public readonly SocialName $social_name,
        public readonly Vat $vat,
        public readonly Addresses $addresses,
        public readonly Departments $departments,
        public readonly Contacts $contacts,
        public readonly bool $is_active = true
    ) {}

    public function addAddress(Address $address): void
    {
        $this->addresses->add($address);
    }

    public function updateAddress(Address $newAddress): void
    {
        $this->addresses->update($newAddress);
    }

    public function removeAddress(int $address_id): void
    {
        $this->addresses->remove($address_id);
    }

    public function addDepartment(Department $department): void
    {
        $this->departments->add($department);
    }

    public function updateDepartment(Department $newDepartment): void
    {
        $this->departments->update($newDepartment);
    }

    public function removeDepartment(int $department_id): void
    {
        $this->departments->remove($department_id);
    }

    public function addContact(Contact $contact): void
    {
        $this->contacts->add($contact);
    }

    public function updateContact(Contact $newContact): void
    {
        $this->contacts->update($newContact);
    }

    public function removeContact(int $contact_id): void
    {
        $this->contacts->remove($contact_id);
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