<?php

namespace App\Http\Controllers;

use App\Models\Comissao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function formCadastrarUsuario()
    {
        $usuarios = User::with('comissao')->orderBy('name')->get();
        return view('admin.cadastrar-usuario', compact('usuarios'));
    }

    public function storeCadastrarUsuario(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'comissao'  => 'required|numeric|min:0|max:100',
        ], [
            'name.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.unique'      => 'Este e-mail já está em uso.',
            'comissao.required' => 'A comissão é obrigatória.',
            'comissao.numeric'  => 'A comissão deve ser um número.',
            'comissao.min'      => 'A comissão não pode ser negativa.',
            'comissao.max'      => 'A comissão não pode ultrapassar 100%.',
        ]);

        // Gera código do vendedor: 7 dígitos aleatórios únicos
        do {
            $codigo_vendedor = str_pad(random_int(0, 9999999), 7, '0', STR_PAD_LEFT);
        } while (User::where('codigo_vendedor', $codigo_vendedor)->exists());

        // Senha temporária gerada automaticamente
        $senha_temporaria = Str::random(8);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => $senha_temporaria,
            'codigo_vendedor'=> $codigo_vendedor,
        ]);

        Comissao::create([
            'user_id' => $user->id,
            'valor'   => $request->comissao,
        ]);

        return redirect('/cadastrar_usuario')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function excluirUsuario(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->delete(); // a comissão é excluída em cascata (onDelete cascade na migration)
        return response()->json(['success' => true]);
    }

    public function atualizarComissao(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'comissao' => 'required|numeric|min:0|max:100',
        ]);

        Comissao::where('user_id', $request->user_id)
            ->update(['valor' => $request->comissao]);

        return response()->json(['success' => true]);
    }
}
