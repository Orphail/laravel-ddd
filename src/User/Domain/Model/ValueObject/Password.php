<?php

declare(strict_types=1);

namespace Src\User\Domain\Model\ValueObject;

use Src\User\Domain\Exceptions\PasswordsDoNotMatchException;
use Src\User\Domain\Exceptions\PasswordTooShortException;

final class Password
{
    private ?string $password;

    public function __construct(?string $password, ?string $confirmation)
    {
        if ($password && strlen($password) < 8) {
            throw new PasswordTooShortException();
        }

        if ($password !== $confirmation) {
            throw new PasswordsDoNotMatchException();
        }

        $this->password = $password;
    }

    public static function fromString(string $password, string $confirmation): self
    {
        return new self($password, $confirmation);
    }

    public function value(): string
    {
        return $this->password;
    }

    public function isNotEmpty(): bool
    {
        return $this->password !== null;
    }
}