<?php

namespace Tests\Feature;

use App\Models\Processo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessoTest extends TestCase
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
    public function test_rota_grid_processo(): void
    {
        $response = $this->post('/custom-login', [
            'email' => 'demostenex@gmail.com',
            'password' => '@Dgdemo4194',
        ]);

        $response = $this->get('/processo');

        $response->assertStatus(200);
    }

    public function test_rota_processo_create(): void
    {
        $this->get_login_teste();
        $response = $this->get('/processo/create');

        $response->assertStatus(200);
    }

    public function test_rota_processo_show(): void
    {
        $processo = Processo::find(1);
        $vencimento = date('Y-m-d 00:00:00', strtotime(json_decode($processo->dt_parcelas)->data0));
        $this->get_login_teste();
        $response = $this->get("processo/{$processo->id}/{$vencimento}");
        $response->assertStatus(200);
    }

    public function test_rota_processo_edit(): void
    {
        $this->get_login_teste();
        $response = $this->get('processo/1/edit');
        $response->assertStatus(200);
    }
}
