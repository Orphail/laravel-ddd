<?php

namespace Src\Agenda\User\Application\UseCases\Commands;

use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class GetRandomAvatarCommand implements CommandInterface
{
    private AvatarRepositoryInterface $avatarRepository;

    public function __construct()
    {
        $this->avatarRepository = app()->make(AvatarRepositoryInterface::class);
    }

    public function execute(): ?string
    {
        return $this->avatarRepository->getRandomAvatar()->getPath();
    }
}