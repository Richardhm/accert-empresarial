<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- CARDS DOS PLANOS -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($dadosPlanos as $index => $plano)
                    <div class="p-6 shadow-lg rounded-lg
                        @if($index === 0) bg-blue-600 text-white border-b-4 border-blue-800
                        @elseif($index === 1) bg-green-600 text-white border-b-4 border-green-800
                        @elseif($index === 2) bg-yellow-400 text-black border-b-4 border-yellow-600
                        @elseif($index === 3) bg-red-500 text-white border-b-4 border-red-700
                        @else bg-purple-500 text-white border-b-4 border-purple-700 @endif">

                        <h3 class="text-2xl font-bold mb-2 uppercase">{{ $plano->nome }}</h3>

                        <p class="text-lg">
                            <strong>Vidas:</strong>
                            {{ $plano->contratos->first()->total_vidas ?? 0 }}
                        </p>

                        <p class="text-lg">
                            <strong>Total R$ Vendido:</strong>
                            {{ number_format($plano->contratos->first()->total_valor ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- SEÇÃO PRINCIPAL ABAIXO DOS CARDS: GRÁFICO À ESQUERDA E TABELAS À DIREITA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <!-- COLUNA DA ESQUERDA -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Planos Mais Vendidos</h3>
                    <canvas id="topPlanosChart" width="100%" height="50"></canvas>
                </div>

                <!-- COLUNA DA DIREITA -->
                <div class="space-y-6">
                    <!-- TABELA DE USUÁRIOS COM MAIS CONTRATOS -->
                    <div class="bg-white overflow-x-auto dark:bg-gray-800 shadow-sm sm:rounded-lg p-1">
                        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Usuários com Mais Contratos</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-1 py-1">#</th>
                                <th class="px-1 py-1">Nome</th>

                                <th class="px-1 py-1">Total Contratos</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($usuariosRanking as $index => $usuario)
                                <tr>
                                    <td class="px-1 py-1">{{ $index + 1 }}</td>
                                    <td class="px-1 py-1">{{ $usuario->user->name }}</td>

                                    <td class="px-1 py-1">{{ $usuario->total_contratos }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- TABELA DE RECENTES -->
                    <div class="bg-white dark:bg-gray-800 overflow-x-auto shadow-sm sm:rounded-lg p-1">
                        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Mais Recentes</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-1 py-1">Plano</th>
                                <th class="px-1 py-1">Usuário</th>
                                <th class="px-1 py-1">Valor Pago</th>

                                <th class="px-1 py-1">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tabelaDetalhada as $contrato)
                                <tr>
                                    <td class="px-1 py-1">{{ $contrato->plano->nome }}</td>
                                    <td class="px-1 py-1">{{ $contrato->user->name }}</td>
                                    <td class="px-1 py-1">R$ {{ number_format($contrato->valor_pagar, 2, ',', '.') }}</td>

                                    <td class="px-1 py-1">{{ $contrato->pago ? 'Pago' : 'Pendente' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Obtendo a área de contexto do elemento canvas
        const ctx = document.getElementById('topPlanosChart').getContext('2d');

        // Configurando os dados para o gráfico com Chart.js
        const topPlanosData = {
            labels: {!! json_encode($topPlanos->pluck('plano.nome')->toArray()) !!}, // Extrai os nomes dos planos do relacionamento
            datasets: [{
                label: 'Total de Vendas',
                data: {!! json_encode($topPlanos->pluck('total_vendas')->toArray()) !!}, // Extrai o total de vendas para cada plano
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)', // Azul
                    'rgba(75, 192, 192, 0.6)', // Verde
                    'rgba(255, 206, 86, 0.6)', // Amarelo
                    'rgba(255, 99, 132, 0.6)', // Vermelho
                    'rgba(153, 102, 255, 0.6)' // Roxo
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1 // Espessura das bordas
            }]
        };

        // Criando o gráfico com Chart.js
        new Chart(ctx, {
            type: 'bar', // Tipo: Gráfico de barras
            data: topPlanosData, // Dados do gráfico
            options: {
                responsive: true, // Gráfico responsivo
                plugins: {
                    legend: {
                        display: true, // Exibe a legenda no topo
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true // Tooltips dinâmicos ao passar o mouse
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true, // Inicia o eixo Y no zero
                    }
                }
            }
        });
    </script>
</x-app-layout>
