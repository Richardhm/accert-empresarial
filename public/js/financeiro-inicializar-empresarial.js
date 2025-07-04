var tableempresarial;
function inicializarEmpresarial(corretora_id = null) {

    if($.fn.DataTable.isDataTable('.listarempresarial')) {
        $('.listarempresarial').DataTable().destroy();
    }

    tableempresarial = $(".listarempresarial").DataTable({
        dom: '<"flex justify-between"<"#title_empresarial">Bftr><t><"flex justify-between"lp>',
        language: {
            "search": "Pesquisar",
            "paginate": {
                "next": "Próx.",
                "previous": "Ant.",
                "first": "Primeiro",
                "last": "Último"
            },
            "emptyTable": "Nenhum registro encontrado",
            "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 até 0 de 0 registros",
            "infoFiltered": "(Filtrados de _MAX_ registros)",
            "infoThousands": ".",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "lengthMenu": "Exibir _MENU_ por página",
            "zeroRecords": "Nenhum registro encontrado"
        },
        ajax: {
            "url":urlGeralEmpresarialPendentes,
            data: function (d) {
                d.corretora_id = corretora_id
            }
        },
        "lengthMenu": [1000,2000,3000],
        "ordering": false,
        "paging": true,
        "searching": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "processing": true,
        columns: [
            {data:"created_at",name:"created_at",width:"8%"},
            {data:"codigo_externo",name:"codigo_externo",width:"6%"},
            {data:"usuario",name:"usuario",width:"10%"},
            {data:"razao_social",name:"razao_social",width:"31%"},
            {data:"cnpj",name:"cnpj",width:"14%"},
            {data:"quantidade_vidas",name:"vidas",width:"5%"},
            {data:"valor_plano",name:"valor_plano",width:"8%",render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')},
            {data:"comissao",name:"comissao",width:"8%",render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ')},
            {data:"plano",name:"plano",width:"10%"},
            {data:"vencimento",name:"vencimento",width:"9%"},
            {data:"status",name:"status",width:"10%",
                render: function (data, type, row, meta) {
                    if (data == 1) {
                        return `<div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="green" class="size-6" style="width:25px; height:25px;">
                                <path d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z" />
                            </svg>
                        </div>`;
                    } else {
                        return `<div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red" class="size-6" style="width:25px; height:25px;">
                                <path d="M15.73 5.5h1.035A7.465 7.465 0 0 1 18 9.625a7.465 7.465 0 0 1-1.235 4.125h-.148c-.806 0-1.534.446-2.031 1.08a9.04 9.04 0 0 1-2.861 2.4c-.723.384-1.35.956-1.653 1.715a4.499 4.499 0 0 0-.322 1.672v.633A.75.75 0 0 1 9 22a2.25 2.25 0 0 1-2.25-2.25c0-1.152.26-2.243.723-3.218.266-.558-.107-1.282-.725-1.282H3.622c-1.026 0-1.945-.694-2.054-1.715A12.137 12.137 0 0 1 1.5 12.25c0-2.848.992-5.464 2.649-7.521C4.537 4.247 5.136 4 5.754 4H9.77a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23ZM21.669 14.023c.536-1.362.831-2.845.831-4.398 0-1.22-.182-2.398-.52-3.507-.26-.85-1.084-1.368-1.973-1.368H19.1c-.445 0-.72.498-.523.898.591 1.2.924 2.55.924 3.977a8.958 8.958 0 0 1-1.302 4.666c-.245.403.028.959.5.959h1.053c.832 0 1.612-.453 1.918-1.227Z" />
                            </svg>
                        </div>`;
                    }
                }


            },
            {data:"id",name:"id",width:"4%"},
            {data:"id",name:"id",width:"10%",visible:false}
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'vivaz-empresarial',
                text: 'Exportar',
                className: 'btn-exportar', // Classe personalizada para estilo
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,6] // Define as colunas a serem exportadas (ajuste conforme necessário)
                },
                filename: 'vivaz-empresarial'
            }
        ],
        "columnDefs": [

            {
                "targets": 11,
                "createdCell": function (td, cellData, rowData, row, col) {
                    let id = cellData;
                    let corretor = rowData['usuario'];
                    let cidade = rowData['cidade'];
                    let email = rowData['email'];
                    let plano = rowData['plano'];
                    let celular = rowData['fone'];
                    let vidas = rowData['quantidade_vidas'];
                    let cnpj = rowData['cnpj'];
                    let razao_social = rowData['razao_social'];
                    let data_vencimento = rowData['vencimento'];
                    let codigo_corretora = rowData['codigo_corretora'];
                    let codigo_odonto = rowData['codigo_odonto'];
                    let codigo_saude = rowData['codigo_saude'];
                    let senha_cliente = rowData['senha_cliente'];

                    let valor_boleto = rowData['valor_boleto'];
                    let valor_odonto = rowData['valor_odonto'];
                    let valor_saude = rowData['valor_saude'];
                    let taxa_adesao = rowData['taxa_adesao'];
                    let vencimento_boleto = rowData['vencimento_boleto'];
                    let data_boleto = rowData['data_boleto'];
                    let tabela_origens = rowData['tabela_origens'];
                    let responsavel = rowData['responsavel'];
                    let plano_contratado = rowData['plano_contrado'];
                    let codigo_externo = rowData['codigo_externo'];
                    let valor_total = rowData['valor_total'];
                    let uf = rowData['uf'];
                    let data_analise = rowData['data_analise'];




                    $(td).html(`<div class='text-center text-white'>
                                            <a href="#" data-id="${id}" data-vendedor="${corretor}" data-plano="${plano}" data-origens="${tabela_origens}"
                                              data-razao_social="${razao_social}" data-cnpj="${cnpj}" data-vidas="${vidas}" data-celular="${celular}"
                                              data-email="${email}" data-responsavel="${responsavel}" data-cidade="${cidade}"
                                              data-plano_contrado="${plano_contratado}" data-codigo_corretora="${codigo_corretora}"
                                              data-codigo_saude="${codigo_saude}" data-codigo_odonto="${codigo_odonto}" data-senha_cliente="${senha_cliente}"
                                              data-valor_saude="${valor_saude}" data-valor_odonto="${valor_odonto}" data-valor_total="${valor_total}"
                                              data-taxa_adesao="${taxa_adesao}" data-valor_boleto="${valor_boleto}" data-vencimento_boleto="${vencimento_boleto}"
                                              data-boleto="${data_boleto}" data-uf="${uf}" data-codigo_externo="${codigo_externo}"
                                              data-analise="${data_analise}"
                                              class="text-white open-modal-empresarial">

                                                <svg style="width:25px; height:25px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 div_info">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                            </a>
                                        </div>
                                    `);
                },
            },
        ],
        "initComplete": function( settings, json ) {
            $('.btn-exportar').css({
                'background-color': '#4CAF50',
                'color': '#FFF',
                'border': 'none',
                'padding': '8px 16px',
                'border-radius': '4px'
            });
            //$('#title_empresarial').html("<h4 style='font-size:1em;margin-top:10px;'>Listagem(Completa)</h4>");
            let corretores = this.api().column(2).data().unique(); // Coluna 2 tem os corretores
            let selectUsuarioIndividual = $('#mudar_user_empresarial');
            selectUsuarioIndividual.empty(); // Limpa o select
            selectUsuarioIndividual.append('<option value="">-- Todos os Corretores --</option>'); // Adiciona uma opção para todos
            corretores.each(function(d) {
                selectUsuarioIndividual.append(`<option value="${d}" style="color:black;">${d}</option>`);
            });

            let planos = this.api().column(8).data().unique(); // Coluna 2 tem os corretores
            let mudarPlanosEmpresarial = $('#mudar_planos_empresarial');
            mudarPlanosEmpresarial.empty(); // Limpa o select
            mudarPlanosEmpresarial.append('<option value="">-- Todos os Planos --</option>'); // Adiciona uma opção para todos
            planos.each(function(d) {
                mudarPlanosEmpresarial.append(`<option value="${d}" style="color:black;">${d}</option>`);
            });

        },
        "drawCallback":function(settings) {

        },
        footerCallback: function (row, data, start, end, display) {
            var intVal = (i) => typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            total = this.api().column(6,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);}, 0);
            total_vidas = this.api().column(5,{search: 'applied'}).data().reduce(function (a, b) {return intVal(a) + intVal(b);},0);
            total_linhas = this.api().column(5,{search: 'applied'}).data().count();
            total_br = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

            $(".total_por_page_empresarial").html(total_br);
            $(".total_por_vida_empresarial").html(total_vidas);
            $(".total_por_orcamento_empresarial").html(total_linhas);
        }
    });
}
$('#tabela_empresarial').on('click', 'tbody tr', function () {
    tableempresarial.$('tr').removeClass('textoforte');
    $(this).closest('tr').addClass('textoforte');
});
inicializarEmpresarial();


$("#mudar_user_empresarial").on('change',function(){
    let valorSelecionado = $(this).val();
    tableempresarial.column(2).search(valorSelecionado).draw();
});


$("#mudar_planos_empresarial").on('change',function(){
    let valorSelecionado = $(this).val();
    tableempresarial.column(8).search(valorSelecionado).draw();
});

$(document).on('click','.open-modal-empresarial',function(e){
    e.preventDefault();
    let vendedor = $(this).data("vendedor");
    let plano = $(this).data("plano");
    let origens = $(this).data("origens");
    let razao_social = $(this).data("razao_social");
    let cnpj = $(this).data("cnpj");
    let vidas = $(this).data("vidas");
    let celular = $(this).data("celular");
    let email = $(this).data("email");
    let responsavel = $(this).data("responsavel");
    let cidade = $(this).data("cidade");
    let uf = $(this).data("uf");
    let plano_contratado = $(this).data("plano_contratado");
    let codigo_corretora = $(this).data("codigo_corretora");
    let codigo_saude = $(this).data("codigo_saude");
    let codigo_odonto = $(this).data("codigo_odonto");
    let senha_cliente = $(this).data("senha_cliente");
    let valor_saude = $(this).data("valor_saude");
    let valor_odonto = $(this).data("valor_odonto");
    let valor_total = $(this).data("valor_total");
    let taxa_adesao = $(this).data("taxa_adesao");
    let valor_boleto = $(this).data("valor_boleto");
    let vencimento_boleto = $(this).data("vencimento_boleto");
    let data_boleto = $(this).data("boleto");
    let id = $(this).data('id');
    let codigo_externo = $(this).data('codigo_externo');
    let data_analise = $(this).data('analise');

    $.ajax({
        url:empresarialFinanceiroInicializar,
        method:"POST",
        data: {
            data_analise:data_analise,
            vendedor: vendedor,
            plano: plano,
            origens: origens,
            razao_social: razao_social,
            cnpj: cnpj,
            vidas: vidas,
            celular: celular,
            email: email,
            responsavel: responsavel,
            cidade: cidade,
            uf: uf,
            id: id,
            plano_contratado: plano_contratado,
            codigo_corretora: codigo_corretora,
            codigo_saude: codigo_saude,
            codigo_odonto: codigo_odonto,
            senha_cliente: senha_cliente,
            valor_saude: valor_saude,
            valor_odonto: valor_odonto,
            valor_total: valor_total,
            taxa_adesao: taxa_adesao,
            valor_boleto: valor_boleto,
            vencimento_boleto: vencimento_boleto,
            data_boleto: data_boleto,
            codigo_externo: codigo_externo
        },
        success:function(res){
            $('#modalLoaderEmpresa').addClass('hidden');
            $('.content-modal-empresarial').removeClass('hidden');
            $(".content-modal-empresarial").html(res);
        }
    });
    $('#myModalEmpresarial').removeClass('hidden').addClass('flex');
});

$("body").on('click','#closeModalEmpresarial',function(){
    $('#myModalEmpresarial').removeClass('flex').addClass('hidden');
    $('.content-modal-empresarial').html('');
});

$("body").on('click','.editar_empresarial_select',function(){

    let input = $(this).closest("div").find("select");

    if (input.prop('disabled')) {
        input.prop('disabled', false); // Remove a propriedade readonly
    } else {
        input.prop('disabled', true); // Adiciona a propriedade readonly
    }
});



$("body").on('click','.editar_empresarial',function(){

    let input = $(this).closest("div").find("input");

    if (input.prop('readonly')) {
        input.prop('readonly', false); // Remove a propriedade readonly
    } else {
        input.prop('readonly', true); // Adiciona a propriedade readonly
    }
});


