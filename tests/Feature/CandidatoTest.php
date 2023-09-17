<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertJson;

class CandidatoTest extends TestCase
{
    protected $managerData;
    protected $agentData;
    protected $managerToken;
    protected $agentToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->candidato_uri = '/lead';
        $this->candidato_uri_all = $this->candidato_uri . 's';
        $this->managerData = $this->newLoggedManager();
        $this->managerToken =  $this->managerData['token'];
        $this->agentData = $this->newLoggedAgent();
        $this->agentToken = $this->agentData['token'];
    }

    /** @test */
    function usuario_puede_crear_candidato_si_es_manager()
    {
        $requestBody = [
            'name' => fake()->name(),
            'source' => fake()->country(),
            'owner' => $this->agentData['id'],
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->managerToken])
            ->post($this->candidato_uri, $requestBody)
            ->assertStatus(Response::HTTP_CREATED);

        $reponseInfo = $response->json();

        $this->assertEquals(
            ["id", "name", "source", "owner", "created_at", "created_by"],
            array_keys($reponseInfo['data'])
        );
    }

    /** @test */
    function usuario_no_puede_crear_candidato_si_es_agent()
    {
        $requestBody = [
            'name' => fake()->name(),
            'source' => fake()->country(),
            'owner' => $this->agentData['id'],
        ];

        $unauthorizedResponse = [
            "meta" => [
                "success" =>
                false, "errors" => ["Token expired"]
            ],
        ];

        // si el usuario es agent entonces no puede crear candidatos
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->agentToken])
            ->post($this->candidato_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson($unauthorizedResponse);
    }

    /** @test */
    function obtener_todos_candidatos_manager_test()
    {
        $candidatosCount = $this->faker->numberBetween(1, 10);
        $this->createRandomCandidato($candidatosCount, [$this->agentData['id'], $this->managerData['id']], $this->managerData['id']);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->managerToken])
            ->get($this->candidato_uri_all)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($candidatosCount, 'data');

        $candidatosAgent = $this->withHeaders(['Authorization' => 'Bearer ' . $this->agentToken])
            ->get($this->candidato_uri_all)
            ->assertStatus(Response::HTTP_OK);

        $candidatosInfoAgent = $candidatosAgent->json();

        foreach ($candidatosInfoAgent['data'] as $candidato) {
            $this->assertEquals($this->agentData['id'], $candidato['owner']);
        }
    }

    /** @test */
    function manager_obtener_candidatos_by_id()
    {
        $candidatosCount = $this->faker->numberBetween(1, 10);
        $candidato_ids = $this->createRandomCandidato($candidatosCount, [$this->agentData['id'], $this->managerData['id']], $this->managerData['id']);
        $randomRandomId = $this->faker->randomElement($candidato_ids);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->managerToken])
            ->get($this->candidato_uri . '/' . $randomRandomId)
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    function agent_no_obtener_candidatos_by_id()
    {
        $candidatosCount = $this->faker->numberBetween(1, 10);
        $candidato_ids = $this->createRandomCandidato($candidatosCount, [$this->managerData['id']], $this->managerData['id']);
        $randomRandomId = $this->faker->randomElement($candidato_ids);

        $unauthorizedResponse = [
            "meta" => [
                "success" =>
                false, "errors" => ["No lead found"]
            ],
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->agentToken])
            ->get($this->candidato_uri . '/' . $randomRandomId)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($unauthorizedResponse);
    }

    /** @test */
    function agent_si_obtener_candidatos_by_id()
    {
        $candidatosCount = $this->faker->numberBetween(1, 10);
        $candidato_ids = $this->createRandomCandidato($candidatosCount, [$this->agentData['id']], $this->managerData['id']);
        $randomRandomId = $this->faker->randomElement($candidato_ids);

        $candidatoAgent = $this->withHeaders(['Authorization' => 'Bearer ' . $this->agentToken])
            ->get($this->candidato_uri . '/' . $randomRandomId)
            ->assertStatus(Response::HTTP_OK);

        $candidatosInfoAgent = $candidatoAgent->json();
        $this->assertEquals($this->agentData['id'], $candidatosInfoAgent['data']['owner']);
    }
}
