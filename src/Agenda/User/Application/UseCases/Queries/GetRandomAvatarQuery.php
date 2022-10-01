<?php

namespace Src\Agenda\User\Application\UseCases\Queries;

use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class GetRandomAvatarQuery implements QueryInterface
{
    private AvatarRepositoryInterface $avatarRepository;

    public function __construct()
    {
        $this->avatarRepository = app()->make(AvatarRepositoryInterface::class);
    }

    public function handle(): ?string
    {
        authorize('getRandomAvatar', UserPolicy::class);
        return $this->avatarRepository->getRandomAvatar()->binary_data;
    }
}