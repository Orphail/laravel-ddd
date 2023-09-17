<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CandidatoTest extends TestCase
{
    use RefreshDatabase;

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

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->managerToken])
            ->post($this->candidato_uri, $requestBody)
            ->assertStatus(Response::HTTP_CREATED);
    }

    function usuario_no_puede_crear_candidato_si_es_agent()
    {
        $requestBody = [
            'name' => fake()->name(),
            'source' => fake()->country(),
            'owner' => $this->agentData['id'],
        ];

        // si el usuario es agent entonces no puede crear candidatos
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->agentToken])
            ->post($this->candidato_uri, $requestBody)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['errors' => 'User Agent cannot create candidato']);
    }

    /** @test */
    function obtener_todos_candidatos_manager_test()
    {
        $candidatosCount = $this->faker->numberBetween(1, 20);
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

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->agentToken])
            ->get($this->candidato_uri . '/' . $randomRandomId)
            ->assertStatus(Response::HTTP_NOT_FOUND);
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
