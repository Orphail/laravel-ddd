<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\Entities;

use Src\Agenda\Company\Domain\Model\ValueObjects\ContactRole;
use Src\Agenda\Company\Domain\Model\ValueObjects\Email;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Common\Domain\Entity;

class Contact extends Entity
{
    public function __construct(
        public ?int $id,
        public readonly ContactRole $contact_role,
        public readonly Name $name,
        public readonly Email $email,
        public readonly Phone $phone,
        public readonly ?int $address_id = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'contact_role' => $this->contact_role->value,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_id' => $this->address_id,
        ];
    }
}