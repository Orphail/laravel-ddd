<?php

declare(strict_types=1);

namespace Src\Agenda\User\Domain\Model;

use Src\Agenda\User\Domain\Exceptions\CompanyRequiredException;
use Src\Agenda\User\Domain\Model\ValueObjects\Avatar;
use Src\Agenda\User\Domain\Model\ValueObjects\Email;
use Src\Agenda\User\Domain\Model\ValueObjects\CompanyId;
use Src\Agenda\User\Domain\Model\ValueObjects\Name;
use Src\Common\Domain\AggregateRoot;

class User extends AggregateRoot
{
    public function __construct(
        public readonly ?int $id,
        public readonly Name $name,
        public readonly Email $email,
        public readonly CompanyId $company_id,
        public Avatar $avatar,
        public readonly bool $is_admin = false,
        public readonly bool $is_active = true
    ) {}

    public function validateNonAdminWithCompany(): User
    {
        if (!$this->company_id->value && !$this->is_admin) {
            throw new CompanyRequiredException();
        }
        return $this;
    }

    public function setAvatar($binaryData, $filename): void
    {
        $this->avatar = new Avatar(binary_data: $binaryData, filename: $filename);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->company_id->value,
            'avatar' => $this->avatar->binary_data,
            'is_admin' => $this->is_admin,
            'is_active' => $this->is_active,
        ];
    }
}
