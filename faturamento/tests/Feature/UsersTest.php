<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{

    public function get_login_teste(): array|object
    {
        $response = $this->post('/custom-login', [
            'email' => 'demostenex@gmail.com',
            'password' => '@Dgdemo4194',
        ]);

        return $response;
    }

    // Teste de login válido
    public function test_Login_Valido()
    {
        $response = $this->post('/custom-login', [
            'email' => 'demostenex@gmail.com',
            'password' => '@Dgdemo4194',
        ]);
        $response->assertStatus(302); // Verifique se o usuário é redirecionado após o login válido
        $response->assertRedirect('/dashboard'); // Verifique se o redirecionamento é para a página restrita
    }

    // Teste de login inválido
    public function test_Login_Invalido()
    {
        $response = $this->post('/custom-login', [
            'email' => 'email_invalido@example.com',
            'password' => 'senha_incorreta',
        ]);

        $response->assertStatus(302); // Verifique se o usuário permanece na página de login após um login inválido
        $response->assertRedirect('/login'); // Verifique se o redirecionamento é para a página de login
        $response->assertSessionHasErrors('email'); // Verifique se há erros de validação na sessão
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_grid(): void
    {
        $this->get_login_teste();
        $response = $this->get('/usuarios');
        $response->assertStatus(200);
    }

    public function test_user_edit(): void
    {
        $this->get_login_teste();
        $response = $this->get('/usuarios/1/edit');
        $response->assertStatus(200);
    }

    public function test_user_create(): void
    {
        $this->get_login_teste();
        $response = $this->get('/grupoprocesso/create');
        $response->assertStatus(200);
    }


}
