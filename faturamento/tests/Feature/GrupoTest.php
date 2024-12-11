<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GrupoTest extends TestCase
{

    public function get_login_teste(): array|object
    {
        $response = $this->post('/custom-login', [
            'email' => 'demostenex@gmail.com',
            'password' => '@Dgdemo4194',
        ]);

        return $response;
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_grupo_grid()
    {
        $this->get_login_teste();
        $response = $this->get('grupoprocesso');
        $response->assertStatus(200);
    }
}
