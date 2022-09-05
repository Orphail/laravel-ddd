<?php

namespace Src\Agenda\User\Application\UseCases\Commands;

use Src\Agenda\User\Domain\Model\User;
use Src\Agenda\User\Domain\Model\ValueObjects\Password;
use Src\Agenda\User\Domain\Policies\UserPolicy;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Src\Agenda\User\Domain\Repositories\UserRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class UpdateUserCommand implements CommandInterface
{
    private UserRepositoryInterface $repository;
    private AvatarRepositoryInterface $avatarRepository;
    private UserPolicy $policy;

    public function __construct(
        private readonly User $user,
        private readonly Password $password
    )
    {
        $this->repository = app()->make(UserRepositoryInterface::class);
        $this->avatarRepository = app()->make(AvatarRepositoryInterface::class);
        $this->policy = new UserPolicy();
    }

    public function execute(): void
    {
        authorize('update', $this->policy, ['user' => $this->user]);
//        if (UserEloquentModel::query()->where('email', $this->user->email)->exists()) {
//            throw new EmailAlreadyUsedException();
//        }

        $avatar = $this->user->avatar;
        if ($avatar->isBinaryFile()) {
            $filename = $this->avatarRepository->storeAvatarFile($avatar, $this->user->name);
            $this->user->setAvatar($filename);
        }

        $this->repository->update($this->user, $this->password);
    }
}