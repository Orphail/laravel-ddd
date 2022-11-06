<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\Entities;

use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Common\Domain\Entity;

class Department extends Entity
{
    public function __construct(
        public readonly ?int $id,
        public readonly Name $name,
        public readonly int $address_id,
        public readonly bool $is_active = true
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address_id' => $this->address_id,
            'is_active' => $this->is_active
        ];
    }
}