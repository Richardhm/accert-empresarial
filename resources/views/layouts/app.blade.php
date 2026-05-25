<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" sizes="75x76" href="{{ asset('icons/icone_bmsys.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <script src="{{asset('assets/jquery.min.js')}}"></script>
    <script src="{{asset('assets/datatables.min.js')}}"></script>
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">

    <script src="{{asset('js/jszip.min.js')}}"></script>
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/buttons.html5.min.js')}}"></script>

    <script src="{{asset('js/jquery-deparam.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('js/sweetalert2@11.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <script src="{{asset('js/xlsx.full.min.js')}}"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{asset('js/select2.min.js')}}"></script>
    @yield('css')
    <style>
        .container_principal {
            background:#0f1623;background-position: 100% 100%;background-repeat: no-repeat;background-size: cover;background-attachment: fixed;min-height: 100vh;display: flex;flex-wrap: wrap;align-items: flex-start;align-content: flex-start;box-sizing: border-box;
        }
        .container_formulario {background:rgba(254,254,254,0.18);backdrop-filter: blur(15px);}
        .navbar {position:relative;background:rgba(254,254,254,0.18);top:5px;left:1px;backdrop-filter: blur(15px);border-radius:15px;width:45px;padding:10px;transition: 0.1s 0s ease-out;z-index: 9999999;}
        .profile {position:relative;width:100%;height:100%;justify-content:space-between;align-items:center;display: grid;place-content: center;padding-bottom: 20px;scale: .8;}
        .profile::after {}
        .profile .imgbox {position:relative;height:38px;width:38px;border-radius:50%;overflow: hidden;}
        .imgbox img {width: 100%;height: 100%;object-fit: cover;}
        .heading {color:#fff;display:none;}
        .heading h3 {font-size:1.15em;font-weight:500;}
        .heading h4{opacity:0.5;font-size:1em;font-weight:400;}

        .navbar ul li {list-style: none;position:relative;display:flex;align-items: center;align-content: center;justify-content: center;}
        .navbar ul li:hover svg {color:black;}
        .navbar ul li a:hover svg {color:black;}
        .navbar ul li a {color:#fff;font-size:0.8em;font-weight:100;display:block;height:30px;line-height:30px;text-decoration:none;text-transform: capitalize;
            border-radius:8px;transition: .4s .05s ease-out;text-align: center;padding:0;vertical-align: middle;
        }
        .navbar ul li a span {display:none;}
        .navbar ul li:hover a {color:black;background:#FFF;transition: 0s ease-out;}
        .navbar ul li::before {position:absolute;content:attr(text-data);padding: 8px 12px;min-width:150px;background:#fff;color:#333;font-weight:500;top: 50%;left:50px;transform: translateX(10px) translateY(-30%);border-radius:8px;text-transform:capitalize;opacity: 0;visibility: hidden;z-index: 9999;}
        .navbar ul li::after {position:absolute;content:'';left:20px;top:50%;transform: translateX(30px) translateY(-50%);opacity: 0;visibility: hidden;}
        .navbar ul li:hover::before,
        .navbar ul li:hover::after {transform: translateX(0px) translateY(-30%);opacity: 1;visibility: visible;transition: 0.15s ease-out;}
        main {flex: 1;}
        .ajax_load {display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;}
        .ajax_load_box{margin:auto;text-align:center;color:#fff;font-weight:var(700);text-shadow:1px 1px 1px rgba(0,0,0,.5)}
        .ajax_load_box_circle{border:16px solid #e3e3e3;border-top:16px solid #61DDBC;border-radius:50%;margin:auto;width:80px;height:80px;-webkit-animation:spin 1.2s linear infinite;-o-animation:spin 1.2s linear infinite;animation:spin 1.2s linear infinite}
        @-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
        :root {--header-height: 60px;}
        @media (max-width: 768px) {
            .fixed-header {position: fixed;top: 0;left: 0;width: 100%;z-index: 1000;background: rgba(254,254,254,0.18);backdrop-filter: blur(15px);padding: 10px 15px;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);height:60px;box-sizing: border-box;}
            .content_all {padding-top: 60px;padding-left: 15px;padding-right: 15px;margin: 100px auto;max-width: 100%;}
            #container_informacoes {margin-top:80px !important;}
            .mobile-icon {display: block;}
            .desktop-icon {display: none;}
        }
        @media (min-width: 769px) {
            .mobile-icon {display: none;}
            .desktop-icon {display: block;}
        }

        /*.interruptor-container {display: flex;align-items: center;justify-content: center;height: 35px;}*/
        /*.interruptor {position: relative;width: 60px;height: 30px;background-color: #ccc;border-radius: 30px;cursor: pointer;transition: background-color 0.3s;}*/
        /*.interruptor::before {content: "";position: absolute;top: 3px;left: 3px;width: 24px;height: 24px;background-color: white;border-radius: 50%;transition: transform 0.3s;box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);}*/
        /*.interruptor.ligado {background-color: #4caf50;}*/
        /*.interruptor.ligado::before {transform: translateX(30px);}*/

        .switch {position: relative;display: flex;width: 40px;height: 20px;margin:0 auto;justify-content: center;}
        .switch input {opacity: 0;width: 0;height: 0;}
        .slider {position: absolute;cursor: pointer;top: 0;left: 0;right: 0;bottom: 0;background-color: #ccc;transition: .4s;border-radius: 20px;}
        .slider:before {position: absolute;content: "";height: 16px;width: 16px;left: 2px;bottom: 2px;background-color: white;transition: .4s;border-radius: 50%;}
        input:checked + .slider {background-color: #4caf50;}
        input:checked + .slider:before {transform: translateX(20px);}

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(128, 128, 128, 0.7); /* Fundo cinza com opacidade */
            display: flex; /* Ocultado por padrão */
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Garantir que fique acima de todos os outros elementos */
        }

        /* Estilo do loader */
        #loader {
            text-align: center;
        }

        .dot {
            display: inline-block;
            width: 20px; /* Aumentar o tamanho dos pontos */
            height: 20px; /* Aumentar o tamanho dos pontos */
            margin: 0 8px; /* Espaço entre os pontos */
            background-color: #000; /* Cor preta */
            animation: bounce 1.5s infinite ease-in-out;
        }

        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }


        .dots-loading span {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0 5px;
            background-color: #fff;
            border-radius: 50%;
            animation: bouncegerente 1.2s infinite;
        }
        .dots-loading span:nth-child(1) {animation-delay: 0s;}
        .dots-loading span:nth-child(2) {animation-delay: 0.2s;}
        .dots-loading span:nth-child(3) {animation-delay: 0.4s;}

        @keyframes bouncegerente {
            0%, 80%, 100% {transform:scale(0);}
            40% {transform:scale(1);}
        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 15% !important;
            background: #FFF;
            border: 1px solid black;
            border-radius: 3px;
            font-weight: bold;
            border-radius:10px;
            color:black;
        }

        .radio-clt:checked + label::before,
        .radio-parceiro:checked + label::before {
            background-color: #4caf50; /* Verde para checked */
            border-color: #4caf50;
        }

        /* Estilizar o input radio */
        .radio-label input[type="radio"] {
            appearance: none; /* Remove o estilo padrão */
            margin-right: 5px;
            width: 24px;
            height: 24px;
            border: 2px solid #ccc;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            cursor: pointer;
        }

        .radio-label input[type="radio"]:checked {
            border-color: #4caf50;
            background-color: #4caf50;
        }

        .radio-label input[type="radio"]:checked::after {
            content: '';
            display: block;
            width: 8px;
            height: 8px;
            background-color: #4caf50;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }



    </style>
</head>
<body class="font-sans antialiased">
<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<div class="container_principal min-h-screen bg-gray-100">



    <!-- Page Content -->
    <div class="navbar hidden lg:block">


        <ul>


{{--                <li text-data="orçamento" class="hover:text-black">--}}
{{--                    <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                        <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">--}}
{{--                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17.345a4.76 4.76 0 0 0 2.558 1.618c2.274.589 4.512-.446 4.999-2.31.487-1.866-1.273-3.9-3.546-4.49-2.273-.59-4.034-2.623-3.547-4.488.486-1.865 2.724-2.899 4.998-2.31.982.236 1.87.793 2.538 1.592m-3.879 12.171V21m0-18v2.2"/>--}}
{{--                        </svg>--}}
{{--                        <span class="text-sm">orçamento</span> <!-- Adicionei classe de texto menor -->--}}
{{--                    </a>--}}
{{--                </li>--}}



{{--                <li text-data="Tabela Completa" class="hover:text-black">--}}
{{--                    <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-white dark:text-white">--}}
{{--                            <path fill-rule="evenodd" d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 18.375V5.625ZM21 9.375A.375.375 0 0 0 20.625 9h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5ZM10.875 18.75a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5ZM3.375 15h7.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375Zm0-3.75h7.5a.375.375 0 0 0 .375-.375v-1.5A.375.375 0 0 0 10.875 9h-7.5A.375.375 0 0 0 3 9.375v1.5c0 .207.168.375.375.375Z" clip-rule="evenodd" />--}}
{{--                        </svg>--}}
{{--                        <span class="text-sm">tabelas</span>--}}
{{--                    </a>--}}
{{--                </li>--}}






{{--                <li text-data="ranking" class="hover:text-black">--}}
{{--                    <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                        <svg class="w-4 h-4 text-gray-800 text-white hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                            <path d="M11 9a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"/>--}}
{{--                            <path fill-rule="evenodd" d="M9.896 3.051a2.681 2.681 0 0 1 4.208 0c.147.186.38.282.615.255a2.681 2.681 0 0 1 2.976 2.975.681.681 0 0 0 .254.615 2.681 2.681 0 0 1 0 4.208.682.682 0 0 0-.254.615 2.681 2.681 0 0 1-2.976 2.976.681.681 0 0 0-.615.254 2.682 2.682 0 0 1-4.208 0 .681.681 0 0 0-.614-.255 2.681 2.681 0 0 1-2.976-2.975.681.681 0 0 0-.255-.615 2.681 2.681 0 0 1 0-4.208.681.681 0 0 0 .255-.615 2.681 2.681 0 0 1 2.976-2.975.681.681 0 0 0 .614-.255ZM12 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z" clip-rule="evenodd"/>--}}
{{--                            <path d="M5.395 15.055 4.07 19a1 1 0 0 0 1.264 1.267l1.95-.65 1.144 1.707A1 1 0 0 0 10.2 21.1l1.12-3.18a4.641 4.641 0 0 1-2.515-1.208 4.667 4.667 0 0 1-3.411-1.656Zm7.269 2.867 1.12 3.177a1 1 0 0 0 1.773.224l1.144-1.707 1.95.65A1 1 0 0 0 19.915 19l-1.32-3.93a4.667 4.667 0 0 1-3.4 1.642 4.643 4.643 0 0 1-2.53 1.21Z"/>--}}
{{--                        </svg>--}}

{{--                        <span class="text-sm">ranking</span>--}}
{{--                    </a>--}}
{{--                </li>--}}



            @if(Auth::user()->isAdministrador())
            <li text-data="dashboard" class="hover:text-black">
                <a href="{{route('dashboard')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                    <svg class="w-4 h-4 text-gray-800 text-white hover:text-black flex align-middle" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">dashboard</span>
                </a>
            </li>
            @endif


{{--            <li text-data="estrela" class="hover:text-black">--}}
{{--                <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                    <svg class="w-4 h-4 text-gray-800 text-white hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                        <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>--}}
{{--                    </svg>--}}
{{--                    <span class="text-sm">estrela</span>--}}
{{--                </a>--}}
{{--            </li>--}}



                <li text-data="financeiro" class="hover:text-black">
                    <a href="{{route('financeiro.index')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg class="w-4 h-4 text-gray-800 text-white hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M7 6a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-2v-4a3 3 0 0 0-3-3H7V6Z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M2 11a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7Zm7.5 1a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z" clip-rule="evenodd"/>
                            <path d="M10.5 14.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                        </svg>
                        <span class="text-sm">financeiro</span>
                    </a>
                </li>



                @if(Auth::user()->isAdministrador())
                <li text-data="gerente">
                    <a href="{{route('gerente.index')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg class="w-4 h-4 text-gray-800 text-white hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd" d="M4 4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4Zm5.178 12.137a4.137 4.137 0 1 1 1.036-8.144A6.113 6.113 0 0 0 8.726 12c0 1.531.56 2.931 1.488 4.006a4.114 4.114 0 0 1-1.036.131ZM10.726 12c0-1.183.496-2.252 1.294-3.006A4.125 4.125 0 0 1 13.315 12a4.126 4.126 0 0 1-1.294 3.006A4.126 4.126 0 0 1 10.726 12Zm4.59 0a6.11 6.11 0 0 1-1.489 4.006 4.137 4.137 0 1 0 0-8.013A6.113 6.113 0 0 1 15.315 12Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">gerente</span>
                    </a>
                </li>
                <li text-data="usuários">
                    <a href="{{route('admin.cadastrar_usuario')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg class="w-4 h-4 text-gray-800 text-white hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 0-2-2h-4.534Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">usuários</span>
                    </a>
                </li>
                <li text-data="Tabelas de Valor">
                    <a href="{{route('configuracoes.cadastro')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-white hover:text-black">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605"/>
                        </svg>
                        <span class="text-sm">tabelas $</span>
                    </a>
                </li>
                {{-- OCULTO DO MENU — rotas ainda funcionam via /configuracoes/faixas e /configuracoes/odonto
                <li text-data="Faixas Saúde">
                    <a href="{{route('configuracoes.faixas')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4 text-white hover:text-black">
                            <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z"/>
                            <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z" clip-rule="evenodd"/>
                            <path d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z"/>
                        </svg>
                        <span class="text-sm">saúde $</span>
                    </a>
                </li>
                <li text-data="Valores Odonto">
                    <a href="{{route('configuracoes.odonto')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white hover:text-black">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C5.6 2 3.6 3.6 3 5.9c-.5 1.8-.1 3.8 1.1 5.3.7.9 1.1 2 1.2 3.1L6 20c.2 1.2.9 2 1.9 2 .9 0 1.6-.7 1.8-1.9L10 18l.3 2.1c.2 1.2.9 1.9 1.8 1.9 1 0 1.7-.8 1.9-2l.7-5.7c.1-1.1.5-2.2 1.2-3.1C17.1 9.7 17.5 7.7 17 5.9 16.4 3.6 14.4 2 12 2H8z"/>
                        </svg>
                        <span class="text-sm">odonto $</span>
                    </a>
                </li>
                --}}
                <li text-data="Pagamento">
                    <a href="{{route('pagamento.index')}}" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white hover:text-black">
                            <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z"/>
                            <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm">pagamento</span>
                    </a>
                </li>
                @endif



{{--                <li text-data="Tabela de Preços">--}}
{{--                    <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" class="w-4 h-4">--}}
{{--                            <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />--}}
{{--                            <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z" clip-rule="evenodd" />--}}
{{--                            <path d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />--}}
{{--                        </svg>--}}
{{--                        <span class="text-sm">Tabela de Preços</span>--}}
{{--                    </a>--}}
{{--                </li>--}}






{{--                <li text-data="Corretores">--}}
{{--                    <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" class="w-4 h-4">--}}
{{--                            <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />--}}
{{--                            <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />--}}
{{--                        </svg>--}}
{{--                        <span class="text-sm">Corretores</span>--}}
{{--                    </a>--}}
{{--                </li>--}}




{{--                <li text-data="Conf. Comissões">--}}
{{--                    <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" class="w-4 h-4">--}}
{{--                            <path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 0 1-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004ZM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 0 1-.921.42Z" />--}}
{{--                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.816a3.836 3.836 0 0 0-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 0 1-.921-.421l-.879-.66a.75.75 0 0 0-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 0 0 1.5 0v-.81a4.124 4.124 0 0 0 1.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 0 0-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 0 0 .933-1.175l-.415-.33a3.836 3.836 0 0 0-1.719-.755V6Z" clip-rule="evenodd" />--}}
{{--                        </svg>--}}
{{--                        <span class="text-sm">Corretores</span>--}}
{{--                    </a>--}}
{{--                </li>--}}


{{--            <li text-data="Meus Clientes" class="hover:text-black">--}}
{{--                <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" class="w-4 h-4">--}}
{{--                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />--}}
{{--                    </svg>--}}
{{--                    <span class="text-sm">Meus Clientes</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <li text-data="Editar Perfil" class="hover:text-black">
                <a href="" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" class="w-4 h-4">
                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                    </svg>
                    <span class="text-sm">Perfil</span>
                </a>
            </li>

            <li text-data="sair">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <a href="#sair" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center flex-col align-middle content-center hover:text-black">
                    <svg class="w-4 h-4 text-gray-800 text-white hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>

                    <span class="text-sm">Sair</span>
                </a>
            </li>

        </ul>
    </div>

    <main class="container_all">

        {{ $slot }}
    </main>
</div>
@yield('scripts')
</body>
</html>
