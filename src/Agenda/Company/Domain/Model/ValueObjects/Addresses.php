<?php

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Common\Domain\Exceptions\EntityNotFoundException;
use Src\Common\Domain\ValueObjectArray;

final class Addresses extends ValueObjectArray
{
    public readonly array $addresses;

    public function __construct(array $addresses)
    {
        parent::__construct($addresses);

        foreach ($addresses as $address) {
            if (!$address instanceof Address) {
                throw new \InvalidArgumentException('Invalid address');
            }
        }
        $this->addresses = array_values($addresses);
    }

    public function add(Address $address): void
    {
        $this->append($address);
    }

    public function update(Address $newAddress): void
    {
        $addressIds = array_column($this->addresses, 'id');
        if (!in_array($newAddress->id, $addressIds)) {
            throw new EntityNotFoundException('Address not found');
        }
        $this->offsetSet(array_search($newAddress->id, $addressIds), $newAddress);
    }

    public function remove(int $address_id): void
    {
        $addressIds = array_column($this->addresses, 'id');
        if (!in_array($address_id, $addressIds)) {
            throw new EntityNotFoundException('Address not found');
        }
        $this->offsetUnset(array_search($address_id, $addressIds));
    }

    public function getMainAddress(): ?Address
    {
        $mainAddress = array_filter($this->addresses, function ($address) {
            return $address->type === AddressType::Fiscal;
        });
        return $mainAddress ? array_shift($mainAddress) : null;
    }

    public function getOtherAddresses(): Addresses
    {
        $otherAddresses = array_filter($this->addresses, function ($address) {
            return $address->type !== AddressType::Fiscal;
        });
        return new Addresses($otherAddresses);
    }

    public function __toString(): string
    {
        return implode(',', $this->addresses);
    }

    public function toArray(): array
    {
        return $this->addresses;
    }

    public function jsonSerialize(): array
    {
        return $this->addresses;
    }
}