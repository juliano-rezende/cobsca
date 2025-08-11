<?php
include("../../../../sessao.php");
?>

<div class="tabs-spacer" style="display:none;">

    <?php

    include("../../../../conexao.php");
    $cfg->set_model_directory('../../../../models/');

    //$FRM_matricula    = isset( $_GET['matricula'])  ? $_GET['matricula']/* variavel com os ids das parcelas*/
    //                        : tool::msg_erros("O Campo matricula Obrigatorio faltando.");


    $dadosassociado = associados::find_by_sql("SELECT
										  SQL_CACHE associados.*,
										  logradouros.descricao as nm_logradouro,
										  logradouros.cep,
										  estados.sigla AS nm_estado,
										  cidades.descricao AS nm_cidade,
										  bairros.descricao AS nm_bairro,
										  convenios.nm_fantasia as nm_convenio,
										  convenios.tx_adesao ,
										  usuarios.login as nm_usuario,
										  empresas.logomarca,
										  (
										  SELECT CONCAT(logradouros.complemento,' ',logradouros.descricao,' cep: ',SUBSTR(logradouros.cep,1,2),'.',SUBSTR(logradouros.cep,3,3),'-',SUBSTR(logradouros.cep,5,3),' Num ',empresas.num,', ', bairros.descricao,', ', cidades.descricao,'/', estados.sigla)
										  FROM
										  empresas
										  LEFT JOIN logradouros ON empresas.logradouros_id = logradouros.id
										  LEFT JOIN bairros ON logradouros.bairros_id = bairros.id
										  LEFT JOIN cidades ON logradouros.cidades_id = cidades.id
										  LEFT JOIN estados ON logradouros.estados_id = estados.id
										  WHERE logradouros.id = empresas.logradouros_id) as endereco_emp,
										  empresas.razao_social,
										  empresas.fone_fixo as fone_fixo_emp,
										  empresas.fone_cel as fone_cel_emp,
										  empresas.cnpj,
										  vendedores.nm_vendedor as nm_vendedor,
										  configs.nm_seguradora,configs.num_apolice,configs.cnpj_seg,configs.vlr_apol_seg,configs.vlr_aux_fun,
										  dados_cobranca.dt_venc_p,dados_cobranca.valor,dados_cobranca.forma_cobranca_id,dados_cobranca.formascobranca_sys_id
										FROM
										  associados
										  LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
										  LEFT JOIN estados ON estados.id = logradouros.estados_id
										  LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
										  LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
										  LEFT JOIN usuarios ON usuarios.id = associados.usuarios_id
										  LEFT JOIN convenios ON convenios.id = associados.convenios_id
										  LEFT JOIN vendedores ON vendedores.id = associados.vendedores_id
										  LEFT JOIN empresas ON associados.empresas_id = empresas.id
										  LEFT JOIN dados_cobranca ON associados.matricula = dados_cobranca.matricula
										  LEFT JOIN configs ON configs.empresas_id =  empresas.id
										WHERE
										  associados.matricula = '1'");


    $dcad = new ActiveRecord\DateTime($dadosassociado[0]->dt_cadastro);

    echo '</div>';
    ?>
    <nav class="uk-navbar" style=" padding:8px 8px 8px 0px; text-align: right;">
        <a onclick="Print();">
            <i class="uk-icon-print uk-icon-medium "></i>
        </a>
    </nav>

    <style>
        p {
            margin: 5px 3px;
        }

        em {
            font-size: 11pt;
            font-weight: bold;
        }

        .tbform1 {
            border: 1px solid #ccc;
            border-radius: 3px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            behavior: url(border-radius.htc);
        }

        .tbform1 tr {
            border: 1px solid #ccc;
            line-height: 20px;
        }

        .tbform1 td {
            font-size: 8pt;
            border-right: 1px solid #ccc;
            padding-left: 2px;
            border-top: 1px solid #ccc;
        }

        .titulo {
            background-color: #f5f5f5;
            text-transform: capitalize;
            font-weight: bold;
        }
    </style>

    <div id="print_c" style=" height: 455px; width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; ">


        <p style="font-family:arial; width:98%; text-align:right; font-size:7pt; position:relative;">Pagina 1</p>

        <div id="big_col_contrato" style="border:0; width: 98%; margin: 0 auto;">
            <div id="col_left_header" style="float: left; height:80px; width: 33%; ">
                <div id="col_logo" style="width: 200px; float: left; margin: 3px 10px;">

                </div>
            </div>
            <div id="col_center_header" style="float: left; height:80px; width: 64%; text-align: center; padding-top: 5px;">
                <div class="uk-article">
                    <p style="font-size: 9pt; text-align: justify; margin-bottom: 5px; font-size: 16pt; font-weight: bold;">
                        CONTRATO DE ADESÃO
                    </p>
                    <p style="font-size: 8pt; text-align: justify; margin-bottom: 5px;">
                        <?php echo $dadosassociado[0]->razao_social . "  CNPJ" . tool::MascaraCampos("??.???.???/????-??", $dadosassociado[0]->cnpj); ?>
                    </p>
                </div>
            </div>
        </div>
        <div id="col_CONTRATANTE" style="border:0; height: auto; width: 98%; margin: 0 auto;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbform1">
                <tr>
                    <td style=" text-transform:uppercase; font-weight:bold; font-size:14px; line-height:30px; background-color:#f5f5f5;">contratante:</td>
                </tr>
                <tr>
                    <td style="border:0; padding:0;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
                            <tr>
                                <td width="11%" height="30" align="center" class="titulo">Nº Contrato</td>
                                <td width="11%" height="30" align="center" class="titulo">Matricula</td>
                                <td width="13%" height="30" align="center" class="titulo">Data contrato</td>
                                <td width="35%" height="30" class="titulo">Vendedor</td>
                                <td width="30%" height="30" class="titulo">Credenciado</td>
                            </tr>
                            <tr>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30">&nbsp;</td>
                                <td height="30">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border:0;padding:0;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0;">
                            <tr class="titulo">
                                <td width="50%" height="30">Nome completo resp.</td>
                                <td width="13%" height="30" align="center">Data Nasc</td>
                                <td width="13%" height="30" align="center">CPF</td>
                                <td width="12%" height="30" align="center">RG</td>
                                <td width="12%" height="30" align="center">O.emissor RG</td>
                            </tr>
                            <tr>
                                <td height="30">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border:0;padding:0;">
                        <table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="titulo">
                                <td height="30">Endereço completo (Rua,Av,Num)</td>
                                <td width="27%" height="30">Bairro</td>
                                <td width="12%" height="30" align="center">CEP</td>
                            </tr>
                            <tr>
                                <td height="30">&nbsp;</td>
                                <td height="30">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border:0;padding:0;">
                        <table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="titulo">
                                <td width="276" height="30">Cidade</td>
                                <td width="48" height="30" align="center">UF</td>
                                <td width="113" height="30" align="center">Casa própria ?</td>
                                <td width="180" height="30">E-mail</td>
                                <td width="87" height="30" align="center">Tel Celular</td>
                                <td width="87" height="30" align="center">Tel Trabalho</td>
                                <td width="87" height="30" align="center">Tel Residência</td>
                            </tr>
                            <tr>
                                <td height="30">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                                <td height="30" align="center">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div id="col_dependentes" style="border:0; height: auto; width: 98%; margin: 10px auto;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbform1">
                <tr>
                    <td style=" text-transform:uppercase; font-weight:bold; font-size:14px; line-height:30px; background-color:#f5f5f5;">dependentes:</td>
                </tr>
                <tr>
                    <td style="border:0; padding:0;">
                        <table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="titulo">
                                <td height="30">Nome Completo</td>
                                <td width="100" height="30" align="center">Telefone</td>
                                <td width="100" height="30" align="center">Data nascimento</td>
                                <td width="100" height="30" align="center">Idade</td>
                                <td width="100" height="30" align="center">Parentesco</td>
                                <td width="100" height="30" align="center">Documento</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border:0;padding:0;">
                        <table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">

                            <tr class="linhas">

                                <td>&nbsp;</td>

                                <td width="100" align="center">&nbsp;</td>

                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                            </tr>
                        </table>
                        <table style="border:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="linhas">
                                <td></td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" height="30" align="center">&nbsp;</td>
                            </tr>
                            <tr class="linhas">
                                <td></td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" height="30" align="center">&nbsp;</td>
                            </tr>
                            <tr class="linhas">
                                <td></td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" height="30" align="center">&nbsp;</td>
                            </tr>
                            <tr class="linhas">
                                <td></td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" height="30" align="center">&nbsp;</td>
                            </tr>
                            <tr class="linhas">
                                <td></td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" align="center">&nbsp;</td>
                                <td width="100" height="30" align="center">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

            <div class="uk-article">
                <p style="font-size: 9pt; text-align: justify;">
                    Contratada LIDER LTDA, CNPJ: Sob o número 26.245.294/0001-05, localizada na RUA DOUTOR VALDIVINO VAZ, CEP: 75.503-304
                    NUM 52 , CENTRO, ITUMBIARA/GO.<br>

                    <br>1.	A CONTRATADA assegurará, ao CONTRATANTE e seus dependentes, todos os serviços prestados pela rede credenciada, de acordo com as listas de serviços e tabelas de valores disponibilizadas na sede da CONTRATADA e na rede credenciada, respeitando os valores e profissionais já praticados e existentes.
                    <br> 2.	CONTRATANTE declara ter sido esclarecido e ter conhecimento de que os serviços de saúde oferecidos pela UNICLÍN não se caracterizam como PLANO DE SAÚDE ou SEGURO DE ASSISTÊNCIA INTEGRAL E PRIVADO DE SAÚDE, nos termos da Lei: 9.656/98, pelo que a mesma Lei não se aplica ao presente contrato e negócio celebrado entre as partes.
                    <br>3. Todos os pagamentos de consultas, exames e demais serviços são de responsabilidade do CONTRATANTE.
                    <br>4. Todos os valores estarão sempre atualizados e disponíveis para todos os clientes.
                    <br>5. Todos os atendimentos na área da saúde serão previamente agendados, não havendo atendimentos de urgência e emergência.
                    <br>6. A UNICLIN não se obriga a comunicar previamente ao CONTRATANTE qualquer alteração de valores, profissionais e serviços em  sua lista ou tabela de credenciados.
                    <br>7. Somente o CONTRATANTE rigorosamente em dia com suas obrigações financeiras junto a CONTRATADA, terá direito de usufruir dos serviços prestados.
                    <br>8. Será considerado aderente titular a pessoa que assinar este contrato e aderentes dependentes todos que possuírem grau de parentesco, no qual receberão, em igualdade de condições com o contratante todos os serviços oferecidos pela UNICLÍN.
                    <br>9. A CONTRATADA não se responsabiliza por serviços prestados ou produtos vendidos, assim como valores cobrados ou formas de pagamentos ofertadas pela rede credenciada. Cabe ao CONTRATANTE proceder com as solicitações diretamente com as credenciadas.
                </p>
            </div>
        </div>


        <div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto; page-break-before: always">

            <div class="uk-article">
                <h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">AUTORIZAÇÃO DE DÉBITO</h1>

                <p style="font-size: 9pt; text-align: justify;">

                    <br>10.	A taxa de adesão de R$ R$ 60,00 ( sessenta reais ), deverá ser paga no ato da assinatura.
                    <br>11.	Em adiantamento ao contrato supra, o titular autoriza o débito para quitação do contrato que terá duração de 12 (doze) meses: - (12) (doze) meses , à vista, no valor de R$____________, ou em 12 (doze) parcelas mensais no valor de R$___________, cada uma em favor da LÍDER LTDA.
                    <br> 12.	A forma de cobrança poderá ser feita através de dinheiro em espécie, boleto bancário, cartão de crédito, anual à vista. A mensalidade deverá estar em dia com a LÍDER SERVIÇOS.
                    <br> 12.	A forma de cobrança poderá ser feita através de dinheiro em espécie, boleto bancário, cartão de crédito, anual à vista. A mensalidade deverá estar em dia com a LÍDER SERVIÇOS.
                    <br>14.	Esta Autorização de débito só poderá ser cancelada pelo titular, na sede da LÍDER SERVIÇOS, Rua. Doutor Valdivino Vaz, 52, Centro, Itumbiara, NÃO CANCELAMOS VIA TELEFONE. A rescisão poderá ocorrer dentro do prazo legal de 7 (sete) dias a contar da data de assinatura desde contrato, nos termos do artigo 49 da lei 8078/1990. As partes poderão rescindir o presente contrato unilateralmente sem qualquer ónus, desde que tenha transcorrido um mínimo de 12 (doze) meses do contrato, através de comunicado escrito com antecedência de 30 (trinta) dias desde que esteja em dia com suas mensalidades. Em caso contrário o TITULAR pagará; uma multa de 50% das parcelas restantes para o término da vigência do contrato.
                    <br>15.	O não recebimento do boleto bancário para pagamento das parcelas devidas pela (o) TITULAR, não justificará qualquer atraso no respectivo pagamento, devendo a (o) TITULAR solicitar segunda via do mesmo no setor financeiro da CONTRATADA ou proceder conforme orientação e solicitação da mesma.
                    <br>16.	É de responsabilidade da (o) TITULAR manter atualizado junto a CONTRATADA seu cadastro pessoal, endereço para correspondência e telefones para contato.
                    <br>17.	Havendo atraso de pagamento de qualquer valor ou acréscimo previsto no presente instrumento, a CONTRATADA fica desde já autorizada a emitir contra o TITULAR os títulos de créditos pertinentes; efetuar a cobrança pelos meios previstos na legislação comum aplicável, extrajudicialmente ou judicialmente; registrar débitos, nos valores exatos das respectivas parcelas, com todos os reajustes especificados neste contrato e quando for o caso, com acréscimos aqui previstos, nos órgãos de proteção ao crédito, bastando para tanto, em atendimento ao artigo 43 do Código de Defesa do Consumidor, informado a conseqüente inscrição no SPC, SERASA ou outro órgão do gênero, assim como poderá encaminhar ao Tabelião/Cartório de Protesto as parcelas referentes às mensalidades não pagas em seu vencimento.
                    <br>18.	A presente cláusula configura anuência expressa do TITULAR para que a CONTRATADA assim proceda, não podendo, o inadimplente alegar qualquer dano pela inscrição de seu nome nos cadastros de proteção ao crédito ou por qualquer ação da CONTRATADA nos moldes acima.
                    <br>19.	Na hipótese de encaminhamento do débito para escritório (s) jurídico (s) o CONTRATRANTE assume o pagamento de honorários, desde já fixados em 10 % (dez por cento) em caso de pagamento extrajudiucal, e 20% (vinte por cento) em caso de encaminhamento judicial.
                    <br>20.	O CONTRATANTE está ciente que o atraso superior a 30 dias no pagamento de qualquer parcela, implica na suspensão dos serviços prestados, até a efetiva regularização do débito.
                    <br>21. A cobrança administrativa e/ou judicial será feita pela CONTRATADA ou por terceiros, a seu critério.
                    <br>22. A correção do valor autorizado será reajustada do mês de janeiro de cada ano com base no IGPM integral da FGV do ano anterior
                    independentemente da data de assinatura, sendo esta divulgada em jornal de grande circulação no estado federativo onde se situa a
                    CONTRATADA.
                    <br>23. As partes elegem o fórum da Comarca de Itumbiara Estado de Goiás, para dirimir quaisquer dúvidas ou litígios oriundos do presente
                    contrato.

                </p>
            </div>
        </div>
        <div id="col_contratada" style="border:0; height: auto; width: 98%; margin: 20px auto;">

            <div class="uk-article">
                <div style="float:left;">
                    <h1 class="uk-article-lead" style="font-size: 9pt; font-weight: bold;">DETALHES DA COBRANÇA</h1>

                    <div class="uk-article">

                        <p style="font-size: 8pt;">Forma de pagamento: ___________________________</p>
                        <p style="font-size: 8pt;">Dia de Vencimento: _____ de cada mês. </p>
                        <p style="font-size: 8pt;">Contratante: _____________________________________</p>
                        <p style="font-size: 8pt;">RG: </p>
                        <p style="font-size: 8pt;">CPF: </p>

                    </div>


                </div>
                <div style="float:right;">
                    <p style="font-size: 8pt; text-align: justify; margin-top: 30px; text-transform: capitalize;">

                    </p>
                    <p style="font-size: 8pt; text-align: justify; margin-top: 20px;">Assinatura e CPF do contratante.</p>
                    <p style="font-size: 8pt; text-align: justify; margin-top: 5px;">________________________________</p>

                </div>

            </div>


        </div>
    </div> <!-- final print -->
    <script type="text/javascript">
        function Print(action) {
            $("#print_c").print();
        }
    </script>

