<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_workflow_grid(): void
    {
        $this->get_login_teste();
        $response = $this->get('/workflow');
        $response->assertStatus(200);
    }

    public function test_workflow_edit(): void
    {
        $this->get_login_teste();
        $response = $this->get('/workflow/1/edit');
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

    public function test_workflow_create(): void
    {
        $this->get_login_teste();
        $response = $this->get('workflow/create');
        $response->assertStatus(200);
    }

}

