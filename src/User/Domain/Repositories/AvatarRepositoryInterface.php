<?php

namespace Src\User\Domain\Repositories;


use Src\User\Domain\Model\ValueObject\Avatar;

interface AvatarRepositoryInterface
{
    public function getRandomAvatar(?string $url): Avatar;

    public function storeAvatarFile(Avatar $avatar, string $name): ?string;
    public function retrieveAvatarFile(Avatar $avatar): Avatar;
}