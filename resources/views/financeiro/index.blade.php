<x-app-layout>
    @section('css')
        <link rel="stylesheet" href="{{ asset('css/estilo-financeiro.css') }}"/>
    @endsection

    <script>
        var urlGeralEmpresarialPendentes    = "{{ route('contratos.listarEmpresarial.listarContratoEmpresaPendentes') }}";
        var empresarialFinanceiroInicializar = "{{ route('financeiro.modal.contrato.empresarial') }}";
        var urlAtualizarStatusPagamento      = "{{ route('financeiro.status.pagamento') }}";
        var isAdmin = {{ auth()->check() && auth()->user()->isAdministrador() ? 'true' : 'false' }};
        var table;
        var table_individual;
        var parcelaSelecionada;
        var tableodonto;
        var tableempresarial;
    </script>

    {{-- ── Modal Empresarial (preservado intacto) ── --}}
    <div id="myModalEmpresarial" class="fixed mx-auto inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
        <div class="relative w-[50%] rounded-lg shadow-3xl p-2 z-50">
            <div id="modalLoaderEmpresa" class="flex justify-center items-center h-64">
                <div class="dot-flashing"><div></div><div></div><div></div></div>
            </div>
            <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-empresarial hidden"></div>
        </div>
    </div>

    {{-- ── Page ── --}}
    <div class="fin-page">
        <div class="fin-inner">

            {{-- Header --}}
            <div class="fin-header">
                <div>
                    <h1 class="fin-title">Financeiro</h1>
                    <p class="fin-sub">Gestão de contratos empresariais</p>
                </div>
                <a href="{{ route('contratos.create.empresarial') }}" class="fin-btn-new">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Novo Contrato
                </a>
            </div>

            {{-- Conteúdo principal --}}
            <section>
                <x-aba-empresarial></x-aba-empresarial>
            </section>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#valor').mask('#.##0,00', {reverse: true});

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $("body").on('click', '.excluir_contrato', function () {
                let id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Você não poderá reverter esta ação!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('contratos.excluir') }}",
                            data: { id: id },
                            method: "POST",
                            success: function () {
                                Swal.fire('Excluído!', 'O contrato foi excluído com sucesso.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function (xhr) {
                                Swal.fire('Erro!', 'Houve um problema ao excluir o contrato.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

    @section('scripts')
        <script src="{{ asset('js/financeiro-arquivo.js') }}"></script>
        <script src="{{ asset('js/financeiro-inicializar-empresarial.js') }}"></script>
        <script src="{{ asset('js/financeiro-parametro-url.js') }}"></script>
    @endsection

</x-app-layout>
