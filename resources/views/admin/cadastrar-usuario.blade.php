<x-app-layout>
    <section class="py-1 flex items-center justify-between text-white rounded"
             style="width:95%;margin:10px auto;background:rgba(254,254,254,0.18);backdrop-filter:blur(15px);">
        <div class="flex w-[50%] justify-end text-right" style="font-size:1.5em;">
            <h2>Cadastrar Usuário</h2>
        </div>
    </section>

    <section style="width:95%;margin:10px auto;">
        <div class="flex gap-4" style="flex-wrap:wrap;">

            <!-- Formulário de cadastro -->
            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-4 text-white"
                 style="flex-basis:32%;min-width:260px;">
                <h3 class="text-lg mb-4">Novo Usuário</h3>

                @if(session('success'))
                    <div class="rounded p-3 mb-4"
                         style="background:rgba(76,175,80,0.25);border:1px solid rgba(76,175,80,0.6);">
                        <p class="font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded p-3 mb-4"
                         style="background:rgba(244,67,54,0.25);border:1px solid rgba(244,67,54,0.6);">
                        <ul class="list-none m-0 p-0 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.store_usuario') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="block text-sm mb-1">Nome</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded px-2 py-1 text-black"
                               placeholder="Nome completo" required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm mb-1">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded px-2 py-1 text-black"
                               placeholder="email@exemplo.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm mb-1">Comissão (%)</label>
                        <input type="number" name="comissao" value="{{ old('comissao') }}"
                               class="w-full rounded px-2 py-1 text-black"
                               placeholder="Ex: 30" min="0" max="100" step="0.01" required>
                    </div>

                    <button type="submit" class="w-full py-2 rounded font-bold"
                            style="background:rgba(76,175,80,0.8);">
                        Cadastrar
                    </button>
                </form>
            </div>

            <!-- Listagem de usuários -->
            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-4 text-white"
                 style="flex:1;min-width:300px;">
                <h3 class="text-lg mb-4">Usuários Cadastrados</h3>
                <table class="w-full text-left text-sm" style="border-collapse:separate;border-spacing:0;">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.12);">
                            <th style="padding:10px 14px;border-bottom:1px solid rgba(255,255,255,0.15);width:28%;">Nome</th>
                            <th style="padding:10px 14px;border-bottom:1px solid rgba(255,255,255,0.15);width:35%;">E-mail</th>
                            <th style="padding:10px 14px;border-bottom:1px solid rgba(255,255,255,0.15);width:27%;text-align:center;">Comissão (%)</th>
                            <th style="padding:10px 14px;border-bottom:1px solid rgba(255,255,255,0.15);width:10%;text-align:center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr data-user-id="{{ $usuario->id }}"
                            style="border-bottom:1px solid rgba(255,255,255,0.08);transition:background 0.15s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.06)'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:12px 14px;">{{ $usuario->name }}</td>
                            <td style="padding:12px 14px;opacity:0.85;">{{ $usuario->email }}</td>
                            <td style="padding:12px 14px;text-align:center;">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="number"
                                           class="input-comissao rounded px-2 py-1 text-black text-sm"
                                           style="width:75px;text-align:center;"
                                           value="{{ $usuario->comissao?->valor ?? 0 }}"
                                           min="0" max="100" step="0.01"
                                           data-user-id="{{ $usuario->id }}"
                                           data-original="{{ $usuario->comissao?->valor ?? 0 }}">
                                    <button class="btn-salvar-comissao rounded px-3 py-1 text-xs font-bold hidden"
                                            style="background:rgba(76,175,80,0.85);white-space:nowrap;"
                                            data-user-id="{{ $usuario->id }}">
                                        Salvar
                                    </button>
                                </div>
                            </td>
                            <td style="padding:12px 14px;text-align:center;">
                                <button class="btn-excluir-usuario"
                                        data-user-id="{{ $usuario->id }}"
                                        data-nome="{{ $usuario->name }}"
                                        title="Excluir usuário">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor"
                                         style="width:20px;height:20px;color:rgba(244,67,54,0.85);cursor:pointer;">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Mostra botão Salvar ao editar
        $(document).on('input', '.input-comissao', function () {
            let original = $(this).data('original');
            let btn = $(this).closest('div').find('.btn-salvar-comissao');
            if ($(this).val() != original) {
                btn.removeClass('hidden');
            } else {
                btn.addClass('hidden');
            }
        });

        // Excluir usuário
        $(document).on('click', '.btn-excluir-usuario', function () {
            let userId = $(this).data('user-id');
            let nome   = $(this).data('nome');

            Swal.fire({
                title: 'Excluir usuário?',
                text: nome + ' e sua comissão serão removidos permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#555',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.excluir_usuario") }}',
                        method: 'POST',
                        data: { user_id: userId },
                        success: function () {
                            $('tr[data-user-id="' + userId + '"]').fadeOut(300, function () {
                                $(this).remove();
                            });
                        },
                        error: function () {
                            Swal.fire('Erro', 'Não foi possível excluir o usuário.', 'error');
                        }
                    });
                }
            });
        });

        // Salva comissão via AJAX
        $(document).on('click', '.btn-salvar-comissao', function () {
            let userId   = $(this).data('user-id');
            let input    = $(this).closest('div').find('.input-comissao');
            let comissao = input.val();
            let btn      = $(this);

            $.ajax({
                url: '{{ route("admin.atualizar_comissao") }}',
                method: 'POST',
                data: { user_id: userId, comissao: comissao },
                success: function () {
                    input.data('original', comissao);
                    btn.addClass('hidden');
                    input.css('border', '2px solid #4CAF50');
                    setTimeout(() => input.css('border', ''), 1500);
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message ?? 'Erro ao salvar.';
                    alert(msg);
                }
            });
        });
    </script>
</x-app-layout>
