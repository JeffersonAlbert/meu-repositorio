<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FornecedorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function get_login_teste(): array|object
    {
        $response = $this->post('/custom-login', [
            'email' => 'demostenex@gmail.com',
            'password' => '@Dgdemo4194',
        ]);

        return $response;
    }

    public function test_fornecedor_grid(): void
    {
        $this->get_login_teste();
        $response = $this->get('fornecedor');
        $response->assertStatus(200);
    }

    public function test_fornecedor_create(): void
    {
        $this->get_login_teste();
        $response = $this->get('fornecedor/create');
        $response->assertStatus(200);
    }

    public function test_fornecedor_edit(): void
    {
        $this->get_login_teste();
        $response = $this->get('fornecedor/1/edit');
        $response->assertStatus(200);
    }
}
