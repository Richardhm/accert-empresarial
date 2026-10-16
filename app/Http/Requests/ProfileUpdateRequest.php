<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // E-mail não é editável: as permissões de acesso são definidas por e-mail
        // (ver User::PERMISSOES), então alterá-lo mudaria o acesso do usuário.
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
