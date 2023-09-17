<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Src\Agenda\User\Application\Mappers\UserMapper;
use Src\Agenda\User\Domain\Factories\UserFactory;
use Src\Agenda\User\Domain\Model\User;

trait WithUsers
{
    use WithFaker;

    protected function newUser(): User
    {
        $user = UserFactory::new();
        $userEloquent = UserMapper::toEloquent($user);
        $userEloquent->password = $this->faker->password(8);
        $userEloquent->save();
        return UserMapper::fromEloquent($userEloquent);
    }

    protected function createRandomUsers($usersNumber = 1): array
    {
        $user_ids = [];
        foreach (range(1, $usersNumber) as $_) {
            $user = UserFactory::new();
            $userEloquent = UserMapper::toEloquent($user);
            $userEloquent->password = $this->faker->password(8);
            $userEloquent->save();

            $user_ids[] = $userEloquent->id;
        }
        return $user_ids;
    }
}
