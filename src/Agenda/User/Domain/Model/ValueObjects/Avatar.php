<?php

namespace Src\Agenda\User\Domain\Model\ValueObjects;

final class Avatar implements \JsonSerializable
{
    protected ?string $avatar;

    public function __construct(?string $avatar)
    {
        $this->avatar = $avatar;
    }

    public static function fromString(?string $avatar): self
    {
        return new self($avatar);
    }

    public function setValue(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getPath(): ?string
    {
        return $this->avatar ?? '';
    }

    public function getExtension(): ?string
    {
        return pathinfo(storage_path('app/avatars/' . $this->avatar), PATHINFO_EXTENSION);
    }

    public function isNull(): bool
    {
        return $this->avatar === null;
    }

    public function isBinaryFile(): bool
    {
        return !$this->isNull() && str_starts_with($this->avatar, 'data:image');
    }

    public function fileExists(): bool
    {
        return $this->avatar && file_exists(storage_path('app/avatars/' . $this->avatar));
    }

    public function __toString(): string
    {
        return $this->getPath() ?? '';
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }
}