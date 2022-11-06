<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\Entities;

use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;
use Src\Agenda\Company\Domain\Model\ValueObjects\City;
use Src\Agenda\Company\Domain\Model\ValueObjects\Country;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Agenda\Company\Domain\Model\ValueObjects\Street;
use Src\Agenda\Company\Domain\Model\ValueObjects\ZipCode;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;
use Src\Common\Domain\Entity;

class Address extends Entity
{
    public function __construct(
        public readonly ?int $id,
        public readonly Name $name,
        public readonly AddressType $type,
        public readonly Street $street,
        public readonly ZipCode $zip_code,
        public readonly City $city,
        public readonly Country $country,
        public readonly Phone $phone,
        public readonly Email $email
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'street' => $this->street,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}