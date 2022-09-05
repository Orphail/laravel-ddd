<?php

namespace Src\Agenda\User\Application\Repositories\Local;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Src\Agenda\User\Domain\Model\ValueObjects\Avatar;
use Src\Agenda\User\Domain\Repositories\AvatarRepositoryInterface;

class AvatarRepository implements AvatarRepositoryInterface
{
    public function __construct(
        private readonly ClientInterface $guzzle = new Client
    )
    {}

    public function getRandomAvatar($url = 'https://doodleipsum.com/300/avatar-2?shape=circle'): Avatar
    {
        $doodleIpsum = $this->guzzle->request('GET', $url);
        $mime = $doodleIpsum->getHeader('Content-Type')[0];
        $binaryImage = base64_encode($doodleIpsum->getBody()->getContents());
        return new Avatar('data:' . $mime . ';base64,' . $binaryImage);
    }

    public function storeAvatarFile(Avatar $avatar, string $name): ?string
    {
        if ($avatar->isBinaryFile()) {
            $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar->getPath()));
            $filename = Str::snake($name) . '.jpg';
            Storage::disk('avatars')->put($filename, $fileData);
            return $filename;
        }
        return null;
    }

    public function retrieveAvatarFile(Avatar $avatar): Avatar
    {
        if ($avatar->fileExists()) {
            $fileData = Storage::disk('avatars')->get($avatar->getPath());
            $avatar->setValue('data:image/' . $avatar->getExtension() . ';base64,' . base64_encode($fileData));
        }
        return $avatar;
    }
}