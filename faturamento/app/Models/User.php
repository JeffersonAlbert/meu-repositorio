<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'last_name',
        'password',
        'centrocustos',
        'id_grupos',
        'id_empresa',
        'id_filiais',
        'master',
        'fiannceiro',
        'token',
        'administrator'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function userPermissions()
    {
        $resultados = DB::table('users')
            ->select(
                'users.id AS user_id',
                'grupo_processos.id AS grupo_id',
                'grupo_processos.nome AS grupo_nome',
                'grupo_processos.criar_usuario AS grupo_criar_usuario',
                'grupo_processos.move_processo AS grupo_move_processo',
                'grupo_processos.deleta_processo AS grupo_deleta_processo',
                'grupo_processos.criar_fluxo AS grupo_criar_fluxo'
            )
            ->join('grupo_processos', function ($join) {
                $join->on(DB::raw("JSON_SEARCH(users.id_grupos, 'one', CAST(grupo_processos.id AS CHAR))"), 'IS', DB::raw('NOT NULL'));
            })
            ->where('users.id', auth()->user()->id)
            ->get();

        session(['permissions' => $resultados]);
        return;
    }

    public static function userBranches()
    {
        $resultados = DB::table('users')
            ->select(
                'filial.id as f_id',
                'filial.razao_social as f_rs',
                'filial.nome as f_nome'
            )
            ->join('empresa', 'empresa.id', 'users.id_empresa')
            ->leftJoin('filial', function ($join) {
                $join->on(DB::raw("JSON_SEARCH(users.id_filiais, 'one', CAST(filial.id AS CHAR))"), 'IS', DB::raw('NOT NULL'));
            })
            ->where('users.id', auth()->user()->id)
            ->get();

        if ($resultados[0]->f_id !== null) {
            session(['filiais' => $resultados]);
            return;
        }
        session(['filiais' => $resultados]);
        return;
    }
}
