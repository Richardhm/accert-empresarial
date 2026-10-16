<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAcesso
{
    /**
     * Sem parâmetro: exige apenas que o usuário tenha ALGUMA permissão
     * (e-mails fora do mapa são deslogados — não podem usar o sistema).
     * Com parâmetro (ex.: acesso:gerente): exige acesso àquela área.
     */
    public function handle(Request $request, Closure $next, ?string $area = null): Response
    {
        $user = auth()->user();

        if (!$user || empty($user->permissoes())) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors([
                'email' => 'Este usuário não tem acesso ao sistema.',
            ]);
        }

        if ($area !== null && !$user->temAcesso($area)) {
            return redirect('/financeiro')->with('error', 'Acesso restrito.');
        }

        return $next($request);
    }
}
