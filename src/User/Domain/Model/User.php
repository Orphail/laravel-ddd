<?php

declare(strict_types=1);

namespace Src\User\Domain\Model;

use Src\User\Domain\Model\ValueObject\Avatar;
use Src\User\Domain\Model\ValueObject\Email;
use Src\User\Domain\Model\ValueObject\Name;

class User implements \JsonSerializable
{
    private Name $name;
    private Email $email;
    private Avatar $avatar;
    private bool $is_admin;
    private bool $is_active;
    private ?int $id;

    public function __construct(?int $id, Name $name, Email $email, Avatar $avatar, ?bool $is_admin, ?bool $is_active) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->is_admin = $is_admin ?? false;
        $this->is_active = $is_active ?? true;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function avatar(): Avatar
    {
        return $this->avatar;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

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
