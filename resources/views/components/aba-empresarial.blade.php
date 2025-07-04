<main id="aba_empresarial">

    <section class="flex justify-between" style="flex-wrap: wrap;">
        <div class="flex flex-col text-white ml-1" style="flex-basis:16%;border-radius:5px;">


            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded" style="margin:1px 0;">
                <ul style="list-style:none;margin:0;padding:5px 0;" id="cadastrar_empresarial">
                    <li style="padding:0px 3px;display:flex;text-align:center;justify-content:center;">
                        <a class="text-center w-full text-white text-lg" href="{{route('contratos.create.empresarial')}}">Cadastrar</a>
                    </li>
                </ul>
            </div>

            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] p-1" id="content_list_empresarial_begin">
                <div class="flex flex-wrap w-full mb-1">


                    <select style="flex-basis:99%;background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);" name="mudar_user_empresarial" id="mudar_user_empresarial" class="
                        w-full mt-1 rounded-lg mb-1 text-center text-sm bg-[rgba(254,254,254,0.18)]
                            active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 mr-1 focus:bg-gray-800 w-full text-xs
                            px-1 mb-2 text-sm font-medium rounded-lg hover:border-transparent focus:border-transparent border-transparent
                        ">
                        <option value="todos" class="text-center" data-id="0">---Escolher Corretor---</option>

                    </select>

                    <select style="flex-basis:99%;background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                            name="mudar_planos_empresarial" id="mudar_planos_empresarial"
                            class="
                                w-full mt-1 rounded-lg mb-1 text-center text-sm bg-[rgba(254,254,254,0.18)]
                                active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 mr-1 focus:bg-gray-800 w-full text-xs
                                px-1 mb-2 text-sm font-medium rounded-lg hover:border-transparent focus:border-transparent border-transparent
                            "
                    >
                        <option value="todos" class="text-center" data-id="0">---Escolher Planos---</option>

                    </select>

                </div>

                <ul id="list_empresarial_begin" class="list-none m-0">
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between empresarial">
                        <span class="flex basis-[50%] text-sm items-center my-auto">Contratos:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right w-[49%] bg-transparent text-sm pr-1 flex justify-end text-sm total_por_orcamento_empresarial">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between empresarial">
                        <span class="flex basis-[50%] text-sm items-center my-auto">Vidas:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right w-[49%] bg-transparent flex justify-end text-sm pr-1 total_por_vida_empresarial">0</span>
                    </li>
                    <li style="height:30px;line-height: 30px;" class="flex mb-1 justify-between empresarial">
                        <span class="flex basis-[50%] text-sm items-center my-auto">Valor:</span>
                        <span class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded text-right w-[49%] bg-transparent flex justify-end text-sm pr-1 total_por_page_empresarial">0</span>
                    </li>
                </ul>
            </div>





            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded p-2 mb-1">


            </div>



        </div>

        <!--COLUNA DA CENTRAL-->
        <div style="flex-basis:83%;">
            <div class="p-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] rounded" style="color:#FFF;">
                <table id="tabela_empresarial" class="table table-sm text-left listarempresarial" style="table-layout: fixed;">

                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cod.</th>
                        <th>Corretor</th>
                        <th>Cliente</th>
                        <th>CNPJ</th>
                        <th>Vidas</th>
                        <th>Valor</th>
                        <th>Comissão</th>
                        <th>%</th>
                        <th>Plano</th>
                        <th>Vencimento</th>

                        <th>Status</th>
                        <th>Ver</th>
                        <th>Resposta</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!--FIM COLUNA DA CENTRAL-->
    </section>
</main>
