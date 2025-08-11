
<?php
require_once "../../sessao.php";
echo '<div class="tabs-spacer" style="display:none;">';

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_ids = isset($_GET['ids']) ? $_GET['ids'] : tool::msg_erros("Campo informado invalido.");
$FRM_matricula = isset($_GET['mat']) ? $_GET['mat'] : tool::msg_erros("Campo matricula faltando.");
$FRM_convenio_id = isset($_GET['convenio_id']) ? $_GET['convenio_id'] : tool::msg_erros("Campo convenios_id faltando.");


//define as variaveis vazias
$parcelas = explode(',', $FRM_ids);
$t_parcelas = count($parcelas);
$vl_t_parcelas = "";
$jurosemulta = "";
$t_debito = "";

// recupera as taxas configuradas na empresa
$configs = configs::find_by_empresas_id($COB_Empresa_Id);

//formas de recebimento do sistema
$Query_frec = formas_recebimentos::find_by_sql("SELECT
                                              formas_recebimentos.id AS f_receb_emp_id,
                                              formas_recebimento_sys.descricao,
                                              formas_recebimentos.acrescimos,
                                              formas_recebimento_sys.id as f_recebe_sys_id
                                            FROM
                                              formas_recebimentos
                                              INNER JOIN formas_recebimento_sys ON formas_recebimento_sys.id =
                                            formas_recebimentos.formas_recebimento_sys_id
                                            WHERE
                                              formas_recebimentos.status = 1;");
$formas = new ArrayIterator($Query_frec);

// contas bancarias habilitadas a emitir boleto
$Query_contas = contas_bancarias::find_by_sql("SELECT id,nm_conta FROM contas_bancarias WHERE empresas_id='" . $COB_Empresa_Id . "' AND tp_conta='2' AND status='1'");
$conta = new ArrayIterator($Query_contas);


foreach ($parcelas as $id) {                                 //   faz um loop usando foreach e recupera os valores

    // recupera ps dados da parcela
    $Query_parcela = faturamentos::find($id);

    // data de vencimento
    $dtv = new ActiveRecord\DateTime($Query_parcela->dt_vencimento);
    $vl_t_parcelas += $Query_parcela->valor;                  // valor total das parcelas
    $t_debito = (faturamentos::Calcula_Juros($Query_parcela->valor, $dtv->format('Y-m-d'), $configs->juros, $configs->multa) - $Query_parcela->valor);      // valor total da divida
    $jurosemulta += $t_debito; // acrescimos

}


echo '</div>';

?>
<form method="post" id="FrmRecebimento" class="uk-form" style="padding-top:0;margin:0;">

    <fieldset style="width:350px; background-color:transparent;padding-top:0;margin:0;">

        <label> <span>Qte Parcelas</span> <input name="quantparcelas" type="text" class=" w_150 uk-text-center" id="quantparcelas" value="<?php echo $t_parcelas; ?>" readonly="readonly" />
            <input type="hidden" id="parcelas" value="<?php echo $FRM_ids; ?>" /> <input type="hidden" id="matricula" value="<?php echo $FRM_matricula; ?>" /> </label> <label> <span>Vl Parcelas</span>
            <div class="uk-form-icon">
                <i class="uk-icon-money "></i> <input name="vtotalparcelas" type="text" class=" w_150 uk-text-center" id="vtotalparcelas" value="<?php echo number_format($vl_t_parcelas, 2, ",", "."); ?>" readonly="readonly" />
            </div>
        </label> <label> <span>Juros e Multas</span>
            <div class="uk-form-icon">
                <i class="uk-icon-plus uk-text-warning"></i>
                <input name="vjurosemultas" type="text" class=" w_150 uk-text-center  uk-text-warning" id="vjurosemultas" value="<?php echo number_format($jurosemulta, 2, ",", "."); ?>" readonly="readonly" />
            </div>
        </label> <label> <span>Sub Total</span>
            <div class="uk-form-icon">
                <i class="uk-icon-check uk-text-primary"></i> <input type="text" id="vl_n" class=" w_150 uk-text-center uk-text-primary" value="<?php echo number_format($vl_t_parcelas + $jurosemulta, 2, ",", "."); ?>" readonly="readonly" />
            </div>
        </label>
        <hr />
        <label> <span>Negociar ? </span> <select name="neg" id="neg" class="select" <?php if ($jurosemulta <= 0) {
                                                                                        echo 'disabled'; }; ?>>
                <option value="0" selected>Não</option>
                <option value="1"> Sim</option>
            </select> <select name="desc_conced" id="desc_conced" class="select w_150" disabled>
                <option value="null" selected>Selecionar</option>
                <option value="<?php echo $configs->desc_um; ?>"> Até R$ 200,00 <?php echo $configs->desc_um . "%"; ?></option>
                <option value="<?php echo $configs->desc_dois; ?>">de R$ 200,00 a R$ 300,00 ( <?php echo $configs->desc_dois . "%"; ?> )</option>
                <option value="<?php echo $configs->desc_tres; ?>">de R$ 300,00 a R$ 400,00 ( <?php echo $configs->desc_tres . "%"; ?> )</option>
                <option value="<?php echo $configs->desc_quatro; ?>">de R$ 400,00 a R$ 500,00 ( <?php echo $configs->desc_quatro . "%" ?> )</option>
                <option value="<?php echo $configs->desc_cinco; ?>">de R$ 500,00 a R$ 600,00 ( <?php echo $configs->desc_cinco . "%"; ?> )</option>
                <option value="<?php echo $configs->desc_seis; ?>">de R$ 600,00 a R$ 700,00 ( <?php echo $configs->desc_seis . "%"; ?> )</option>
                <option value="<?php echo $configs->desc_sete; ?>">de R$ 700,00 a R$ 800,00 ( <?php echo $configs->desc_sete . "%"; ?> )</option>
                <option value="<?php echo $configs->desc_oito; ?>">de R$ 800,00 a R$ 900,00 ( <?php echo $configs->desc_oito . "%"; ?> )</option>
                <option value="<?php echo $configs->desc_nove; ?>">acima de R$ 900,00 ( <?php echo $configs->desc_nove . "%"; ?> )</option>
            </select> </label>
        <hr />
        <label> <span>Forma Receb</span> <select name="f_receb" id="f_receb" class="select w_150">
                <option value="0;0;0" selected>Selecionar</option>
                <?php
                while ($formas->valid()) :
                    echo '<option value="' . $formas->current()->f_receb_emp_id . ';' . $formas->current()->acrescimos . ';' . $formas->current()->f_recebe_sys_id . '" >' . utf8_encode(strtoupper($formas->current()->descricao)) . '</option>';
                    $formas->next();
                endwhile;
                ?>
            </select> </label> <label id="label_doc_cartao"> <span>Nº doc/cheque</span> <input name="num_doc_c" type="text" class=" w_150 uk-text-center " id="num_doc_c" /> </label>
        
            <label> <span>descontos</span>
            <div class="uk-form-icon">
                <i class="uk-icon-minus uk-text-danger"></i>
                <input name="descontos" <?php if ($COB_Acesso_Id <= 2) {  echo "readonly"; } else { echo 'onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { Calcular(); }" onblur ="Calcular();"'; } ?> type="text" class=" w_150 uk-text-center " id="descontos" value="0,00" />
                <!-- onblur="Calcular();" onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { Calcular(); }" -->
            </div>
             </label>

        <label> <span>Total</span>
            <div class="uk-form-icon">
                <i class="uk-icon-money uk-text-success"></i> <input name="ttpg" type="text" readonly class=" w_150 uk-text-center uk-text-success" id="ttpg" value="<?php echo number_format($vl_t_parcelas + $jurosemulta, 2, ",", "."); ?>" />
            </div>
        </label>

        <div id="DadosBoleto" class="uk-modal">
            <div class="uk-modal-dialog">
                <!--<button type="button" class="uk-modal-close uk-close"></button> -->
                <div class="uk-modal-header ">
                    <h2><i class="uk-icon-bank"></i> Banco/Vencimento</h2>
                </div>
                <label> <span>Banco Emissor</span> <select name="banco_emissor" id="banco_emissor" class="select w_200">
                        <?php
                        while ($conta->valid()) :
                            echo '<option selected="selected" value="' . $conta->current()->id . '">' . $conta->current()->nm_conta . '</option>';
                            $conta->next();
                        endwhile;
                        ?>
                    </select> </label> <label> <span>Vencimento</span> <input type="text" class=" w_150 center" name="dt_vencimento" id="dt_vencimento" placeholder="00/00/0000" /> </label>
                <div class="uk-modal-footer uk-text-right">
                    <a id="Btn_Rec_3" class="uk-button uk-button-primary uk-button-small"><i class="uk-icon-search"></i> Confirmar</a>
                    <a id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-button-small uk-modal-close"><i class="uk-icon-remove"></i> Cancelar</a>
                </div>
            </div>
        </div>
    </fieldset>
</form>

<a id="Btn_Rec_2" class="uk-button uk-button-primary" style=" bottom:30px; position:absolute;  right:15px;" data-uk-tooltip="{pos:'left'}" title="Receber" data-cached-title="Receber"><i class="uk-icon-bank"></i> Receber </a>


<script type="text/javascript">
    // OCULTA OS CAMPOS
    jQuery("#Btn_Rec_1,#bancoemissor,#desc_condced,#label_doc_cartao").hide();
    // MASCARA NOS CAMPOS
    jQuery("#dt_vencimento").mask("99/99/9999");
    jQuery("#descontos,#ttpg").maskMoney({
        showSymbol: true,
        symbol: "",
        decimal: ",",
        thousands: "."
    });

    // seta o placeholder em todos os selects do forumlario
    jQuery(function($) {
        //function for placeholder select
        function selectPlaceholder(selectID) {
            var selected = jQuery(selectID + ' option:selected');
            var val = selected.val();
            jQuery(selectID + ' option').css('color', '#333');
            selected.css('color', '#999');
            if (val == "") {
                jQuery(selectID).css('color', '#999');
            };
            jQuery(selectID).change(function() {
                var val = jQuery(selectID + ' option:selected').val();
                if (val == "") {
                    jQuery(selectID).css('color', '#999');
                } else {
                    jQuery(selectID).css('color', '#333');
                };
            });
        };
        selectPlaceholder('.select');
    });


    // SELECT NEGOCIAÇÃO
    jQuery("#neg").change(function() {

        if (jQuery(this).val() == "0") {

            jQuery('#desc_conced').attr('disabled', 'disabled').css('color', '#333');

            jQuery('#desc_conced option[value=null]').prop('selected', true);

            jQuery('#f_receb option[value=0]').prop('selected', true);

            jQuery('#desc_conced').focus();

        } else {
            jQuery('#desc_conced').removeAttr("disabled");

            jQuery('#desc_conced option[value=null]').prop('selected', true);
            jQuery('#f_receb option[value=0]').prop('selected', true);

            jQuery('#desc_conced').focus();

        }

    });


    // SELECT FORMA DE RECEBIMENTO
    jQuery("#f_receb").change(function() {

        var str = jQuery(this).val();
        str = str.split(";");


        // EXIBI OU OCULTA O CAMPO NUMERO DO DOCUMENTO DO CARTÃO DE CREDITO OU CHEQUE
        if (str[2] == "002" || str[2] == "003" || str[2] == "004") {

            jQuery("#label_doc_cartao").show().focus();

        } else {

            jQuery("#label_doc_cartao").hide();
        }

        // SE A FORM DE RECEBIMENTO FOR IGUAL A ZERO NÃO EXECUTA NADA
        if (jQuery(this).val() != 0) {

            // mensagen de carregamento
            jQuery("#msg_loading").html(" Aguarde...");
            //abre a tela de preload
            modal.show();
            // requisição
            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_recebimento.php",
                type: "post",
                data: "action=op&tp=perc&vl_n=" + jQuery("#vl_n").val() + "&desc_conced=" + jQuery("#desc_conced").val() + "&f_receb_id=" + str[0] + "&acrescimos=" + str[1] + "&neg=" + jQuery("#neg").val() + "",
                success: function(resultado) {
                    var text = '{"' + resultado + '"}';
                    var obj = JSON.parse(text);
                    jQuery("#descontos").val(obj.descontos);
                    jQuery("#ttpg").val(obj.total);
                    modal.hide();
                },
                error: function() {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404"); /*erro de caminho invalido do arquivo*/
                    modal.hide();
                }
            });
        }
    });

    // SELECT DESCONTOS
    jQuery("#desc_conced").change(function() {

        jQuery('#f_receb option[value=0]').prop('selected', true);
        jQuery('#f_receb').focus();

    });


    // envia os dados para calcular o desconto
    function Calcular() {

        if (jQuery("#descontos").val() <= 0 || jQuery("#descontos").val() == "") {

            return false;
        }

        // mensagen de carregamento
        jQuery("#msg_loading").html(" Aguarde...");
        //abre a tela de preload
        modal.show();

        jQuery.ajax({
            async: true,
            url: "assets/faturamento/controllers/Controller_recebimento.php",
            type: "post",
            data: "action=op&tp=sub&vl_n=" + jQuery("#vl_n").val() + "&desconto=" + jQuery("#descontos").val() + "",
            success: function(resultado) {

                jQuery("#ttpg").val(resultado).focus();
                modal.hide();
            },
            error: function() {
                UIkit.modal.alert("Erro ao enviar dados! Erro 404"); /*erro de caminho invalido do arquivo*/
                modal.hide();
            }
        });
    };

    // envia os dados para gerar os boleto ou receber
    jQuery(function() {

        jQuery("#Btn_Rec_2").click(function(event) {

            // mensagen de carregamento
            jQuery("#msg_loading").html(" Recebendo...");

            //abre a tela de preload
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            // verifica qual forma de recebimento
            var str = jQuery("#f_receb").val();
            str = str.split(";");

            var data = "action=rec&mat=" + jQuery("#matricula").val() + "&pa_id=" + jQuery("#parcelas").val() + "&total=" + jQuery("#vl_n").val() + "&desc=" + jQuery("#descontos").val() + "&f_receb_id=" + str[0] + "&f_receb_sys_id=" + str[2] + "&num_doc_c=" + jQuery("#num_doc_c").val() + "";

            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_recebimento.php",
                type: "POST",
                data: data,
                success: function(resultado) {

                    var text = '{"' + resultado + '"}';
                    var obj = JSON.parse(text);

                    // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                    if (obj.callback == 1) {

                        // New_window('exclamation-triangle', '500', '250', 'Atenção', '<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">' + obj.msg + '</div>', true, true, 'Aguarde...');
                        UIkit.modal.alert("Rebecimento realizado com sucesso."); /*erro de caminho invalido do arquivo*/
                        modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                    } else {

                        UIkit.notify('' + obj.msg + '', {
                            timeout: 2000,
                            status: '' + obj.status + ''
                        });
                        // /* id da janela dados de cobranca Frmdadoscobranca*/
                        jQuery("#" + jQuery("#FrmRecebimento").closest('.Window').attr('id') + "").remove();
                        jQuery("#" + jQuery("#grid_faturamento").closest('.Window').attr('id') + "").remove();
                        New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando...');
                    }
                },
                error: function() {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404"); /*erro de caminho invalido do arquivo*/
                    modal.hide();
                }
            });
        });
    });
</script>