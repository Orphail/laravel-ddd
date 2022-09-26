<?php

namespace Src\Agenda\User\Domain\Repositories;


use Src\Agenda\User\Domain\Model\ValueObjects\Avatar;

interface AvatarRepositoryInterface
{
    public function getRandomAvatar(?string $url): Avatar;

    public function storeAvatarFile(Avatar $avatar): ?string;

    public function retrieveAvatarFile(Avatar $avatar): Avatar;

    public function deleteAvatarFile(Avatar $avatar): void;
}