<?php

namespace Tests\Integration;

use GuzzleHttp\Client;
use Tests\TestCase;

class DoodleAPITest extends TestCase
{
    /** @test */
    public function get_doodle_avatar()
    {
        $url = 'https://doodleipsum.com/300/avatar-2?shape=circle';
        $response = (new Client())->get($url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/png', $response->getHeader('Content-Type')[0]);
    }
}
