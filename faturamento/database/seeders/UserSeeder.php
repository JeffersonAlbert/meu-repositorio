<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            "name" => 'Admin',
            "last_name" => 'Number',
            "email" => 'number@number.com.br',
            "password" => Hash::make('password'),
            "administrator" => true,
            "id_empresa" => 1
        ]);

        DB::table('empresa')->insert([
            "nome" => "Number",
            "razao_social" => "Number",
            "endereco" => "Rua Yen",
            "numero" => "18",
            "cidade" => "Barueri",
            "complemento" => "Casa 1",
            "bairro" => "Jd dos Camargos",
            "cep" => "064100040",
            "cpf_cnpj" => "12345678901234"
        ]);
    }
}
