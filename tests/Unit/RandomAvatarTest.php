<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Src\Agenda\User\Application\Repositories\Local\AvatarRepository;
use Src\Agenda\User\Domain\Model\ValueObjects\Avatar;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;
use Tests\TestCase;

class RandomAvatarTest extends TestCase
{
    /** @test */
    public function get_random_avatar()
    {
        $guzzleMock = \Mockery::mock(Client::class);
        $guzzleMock
            ->shouldReceive('request')
            ->andReturn(new Response(200, ['Content-Type' => 'image/png'], 'binary data'));

        app()->bind(AvatarRepositoryInterface::class, function () use ($guzzleMock) {
            return new AvatarRepository($guzzleMock);
        });

        $avatar = (new AvatarRepository($guzzleMock))->getRandomAvatar();
        $avatarBinaryStr = 'data:image/png;base64,' . base64_encode('binary data');

        $this->assertEquals(new Avatar(
            binary_data: $avatarBinaryStr,
            filename: null,
        ), $avatar);
    }
}
