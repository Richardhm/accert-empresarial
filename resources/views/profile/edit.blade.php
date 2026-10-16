<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Perfil
        </h2>
    </x-slot>

    <style>
        .perfil-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 16px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .perfil-card {
            flex: 1;
            min-width: 320px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 12px;
            padding: 26px 28px;
        }
        .perfil-card h2 { color: #fff; }
        .perfil-card header p { color: rgba(255,255,255,.5); }
        .perfil-card label { color: rgba(255,255,255,.65) !important; }
        .perfil-card input {
            background: #1a2540 !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(255,255,255,.15) !important;
            border-radius: 8px !important;
        }
        .perfil-card input:focus {
            border-color: #4f8ef7 !important;
            box-shadow: 0 0 0 1px #4f8ef7 !important;
        }
        .perfil-card input[disabled] {
            opacity: .55;
            cursor: not-allowed;
        }
        .perfil-card button[type="submit"] {
            background: #4f8ef7 !important;
            border: none !important;
            color: #fff !important;
        }
        .perfil-card button[type="submit"]:hover { background: #3b7ae4 !important; }
        .perfil-card .text-sm.text-gray-600 { color: #34d399 !important; }
    </style>

    <div class="perfil-wrap">
        <div class="perfil-card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="perfil-card">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>
