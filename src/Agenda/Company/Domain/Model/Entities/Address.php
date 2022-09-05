<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\Entities;

use Src\Agenda\Company\Domain\Model\ValueObjects\AddressType;
use Src\Agenda\Company\Domain\Model\ValueObjects\City;
use Src\Agenda\Company\Domain\Model\ValueObjects\Country;
use Src\Agenda\Company\Domain\Model\ValueObjects\Phone;
use Src\Agenda\Company\Domain\Model\ValueObjects\Street;
use Src\Agenda\Company\Domain\Model\ValueObjects\ZipCode;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;

class Address implements \JsonSerializable
{
    public function __construct(
        public ?int $id,
        public readonly AddressType $type,
        public readonly Street $street,
        public readonly ZipCode $zip_code,
        public readonly City $city,
        public readonly Country $country,
        public readonly Phone $phone,
        public readonly Email $email
    ) {}

    public function __toString(): string
    {
        return $this->street . ', ' . $this->zip_code . ' ' . $this->city . ', ' . $this->country;
    }

    public function id(?int $newId = null): ?int
    {
        if ($newId) {
            $this->id = $newId;
        }
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'street' => $this->street,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}