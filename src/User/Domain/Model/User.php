<?php

declare(strict_types=1);

namespace Src\User\Domain\Model;

use Src\User\Domain\Model\ValueObjects\Avatar;
use Src\User\Domain\Model\ValueObjects\Email;
use Src\User\Domain\Model\ValueObjects\Name;

class User implements \JsonSerializable
{
    public function __construct(
        public readonly ?int $id,
        public readonly Name $name,
        public readonly Email $email,
        public Avatar $avatar,
        public readonly ?bool $is_admin,
        public readonly ?bool $is_active
    ) {}

    public function setAvatar($avatar): void
    {
        $this->avatar = new Avatar($avatar);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'is_admin' => $this->is_admin,
            'is_active' => $this->is_active,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
