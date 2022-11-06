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
        return new Avatar(binary_data: 'data:' . $mime . ';base64,' . $binaryImage, filename: null);
    }

    public function storeAvatarFile(Avatar $avatar): ?string
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar->binary_data));
        $filename = Str::uuid() . '.jpg';
        Storage::disk('avatars')->put($filename, $fileData);
        return $filename;
    }

    public function retrieveAvatarFile(Avatar $avatar): ?string
    {
        if ($avatar->fileExists()) {
            $fileData = Storage::disk('avatars')->get($avatar->filename);
            return 'data:image/' . $avatar->getExtension() . ';base64,' . base64_encode($fileData);
        }
        return null;
    }

    public function deleteAvatarFile(Avatar $avatar): void
    {
        if ($avatar->fileExists()) {
            Storage::disk('avatars')->delete($avatar->filename);
        }
    }
}