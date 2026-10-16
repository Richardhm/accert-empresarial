<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'codigo_vendedor',
        'image', // Adicione aqui para permitir preenchimento em massa
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function contratos()
    {
        return $this->hasMany(ContratoEmpresarial::class, 'user_id', 'id');
    }

    public function comissao()
    {
        return $this->hasOne(Comissao::class, 'user_id', 'id');
    }

    /**
     * Mapa de permissões por e-mail. '*' = acesso total.
     * Áreas: dashboard, financeiro, gerente, usuarios, tabelas, pagamento
     * E-mails fora do mapa NÃO têm acesso ao sistema (login é bloqueado).
     */
    private const PERMISSOES = [
        'richardjonhshm@gmail.com'  => ['*'],
        'fredbbarros@gmail.com'     => ['*'],
        'nikaelly.accert@gmail.com' => ['dashboard', 'financeiro', 'gerente', 'usuarios', 'tabelas'],
        'administrador@accert.com'  => ['financeiro', 'tabelas'],
    ];

    public function permissoes(): array
    {
        return self::PERMISSOES[strtolower(trim($this->email ?? ''))] ?? [];
    }

    public function temAcesso(string $area): bool
    {
        $p = $this->permissoes();
        return in_array('*', $p) || in_array($area, $p);
    }

    public function isAdministrador(): bool
    {
        return in_array('*', $this->permissoes());
    }
}
