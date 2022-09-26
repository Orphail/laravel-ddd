<?php

namespace Src\Agenda\User\Domain\Model\ValueObjects;

final class Avatar implements \JsonSerializable
{
    public function __construct(
        public ?string $binary_data,
        public ?string $filename
    )
    {}

    public function setBinaryData(?string $binary_data): void
    {
        $this->binary_data = $binary_data;
    }

    public function getExtension(): ?string
    {
        return pathinfo(storage_path('app/avatars/' . $this->filename), PATHINFO_EXTENSION);
    }

    public function isNull(): bool
    {
        return $this->binary_data === null;
    }

    public function hasBinaryData(): bool
    {
        return !$this->isNull() && str_starts_with($this->binary_data, 'data:image');
    }

    public function fileExists(): bool
    {
        return $this->filename && file_exists(storage_path('app/avatars/' . $this->filename));
    }

    public function __toString(): string
    {
        return $this->filename ?? '';
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }
}