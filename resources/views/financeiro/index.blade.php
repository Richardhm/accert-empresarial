<x-app-layout>
    @section('css')
       <link rel="stylesheet" href="{{asset('css/estilo-financeiro.css')}}"/>
    @endsection
    <input type="hidden" id="janela_atual" value="aba_individual">

        <!-- Modal -->
        <script>
            var urlGeralEmpresarialPendentes = "{{ route('contratos.listarEmpresarial.listarContratoEmpresaPendentes') }}";
            var empresarialFinanceiroInicializar = "{{route('financeiro.modal.contrato.empresarial')}}";
            var table;
            var table_individual;
            var parcelaSelecionada;
            var tableodonto;
            var tableempresarial;
        </script>

        <div id="myModalEmpresarial" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] z-40"></div>
            <!-- Conteúdo da Modal -->
            <div class="relative w-11/12 rounded-lg shadow-3xl p-2 z-50">
                <!-- Botão Fechar no Topo -->
                <div id="modalLoaderEmpresa" class="flex justify-center items-center h-64">
                    <div class="dot-flashing">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <!-- Borda Animada -->
                <div class="relative p-1 rounded-lg animate-border overflow-hidden content-modal-empresarial hidden">
                </div>
            </div>
        </div>

        <section class="py-1 flex items-center justify-between text-white rounded" style="width:95%;margin:10px auto;background:rgba(254,254,254,0.18);backdrop-filter: blur(15px);">
            <div class="flex w-[50%] justify-end text-right" style="font-size: 1.5em;">
                <h2>Financeiro</h2>
            </div>
            <div class="flex w-[50%] justify-end">
                <small>Pagina criada as 03:59 da manhã sexta-feira 04/07/2025</small>
            </div>
        </section>

        <section class="conteudo_abas mt-2" style="width:95%;margin:0 auto;">

            <x-aba-empresarial></x-aba-empresarial>

        </section>

    <script>

        $(document).ready(function(){

            $('#valor').mask('#.##0,00', {reverse: true});
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            function totalMes() {
                return $("#select_usuario_individual").val();
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

        @section('scripts')
            <script src="{{asset('js/financeiro-arquivo.js')}}"></script>
            <script src="{{asset('js/financeiro-inicializar-empresarial.js')}}"></script>
            <script src="{{asset('js/financeiro-parametro-url.js')}}"></script>





        @endsection

</x-app-layout>
