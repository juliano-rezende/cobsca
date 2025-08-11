<?php
require_once "../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_action = isset($_GET['action']) ? $_GET['action'] : tool::msg_erros("O Campo ação é Obrigatorio.");


if ($FRM_action == "edit"):

    $FRM_id = isset($_GET['id']) ? $_GET['id'] : tool::msg_erros("O Campo id é Obrigatorio.");
// recupera os dados do associado
    $dependentes = dependentes::find_by_sql("SELECT SQL_CACHE
										  dependentes.*,
										  dependentes.id as dep_id,
										  associados.empresas_id,
										  associados.convenios_id,
										  parentescos.descricao as parentesco,
										  parentescos.id
										FROM
										  dependentes
										  LEFT JOIN associados ON dependentes.matricula =
											associados.matricula
										  LEFT JOIN parentescos ON dependentes.parentescos_id = parentescos.id
										WHERE
										  dependentes.id = '" . $FRM_id . "'");
    $FRM_matricula = $dependentes[0]->matricula;

else:

    $FRM_matricula = isset($_GET['matricula']) ? $_GET['matricula'] : tool::msg_erros("O Campo matricula é Obrigatorio.");

endif;
?>
</div>

<form method="post" id="Frmdependente" class="uk-form" style="margin-top: 0; padding-top: 0;">

    <fieldset style="margin-top: 0; padding-top: 10px;">

        <label>
            <span>Codigo</span>

            <input name="Dep_Empresa_id" type="text" class="input_text center w_40" id="Dep_Empresa_id" value="<?php
            if ($FRM_action == "edit"): echo tool::CompletaZeros(3, $dependentes[0]->empresas_id);
            else:echo "000";endif; ?>" readonly="readonly">

            <input name="Dep_Convenio_id" type="text" class="input_text center w_40" id="Dep_Convenio_id" value="<?php
            if ($FRM_action == "edit"): echo tool::CompletaZeros(3, $dependentes[0]->convenios_id);
            else:echo "000";endif; ?>" readonly="readonly">
            <input name="Dep_Matricula" type="text" class="input_text center w_80" id="Dep_Matricula" value="<?php echo tool::CompletaZeros(10, $FRM_matricula); ?>" readonly="readonly">
            <input name="Dep_Sequencia" type="text" class="input_text center w_20" id="Dep_Sequencia" value="<?php
            if ($FRM_action == "edit"): echo tool::CompletaZeros(3, $dependentes[0]->sequencia);
            else:echo "0";endif; ?>" readonly="readonly">
            <input name="Dep_id" type="hidden" id="Dep_id" value="<?php if ($FRM_action == "edit"): echo $dependentes[0]->dep_id; endif; ?>">
        </label>
        <!-- -->
        <label>
            <span>Nome completo</span>
            <input value="<?php if ($FRM_action == "edit") {
                echo $dependentes[0]->nome;
            } else {
            } ?>" type="text" class=" w_400" name="Dep_nome" id="Dep_nome"/>
        </label>
        <!-- -->
        <label>
            <span>Data Nasc</span>
            <input value="<?php if ($FRM_action == "edit"):$now = new ActiveRecord\DateTime($dependentes[0]->dt_nascimento);
                echo $now->format('d/m/Y'); endif; ?>" type="text" class="uk-text-center w_100 center" name="Dep_data_nasc" id="Dep_data_nasc" placeholder="00/00/0000"/>
            <?php
            if ($FRM_action == "edit"):
                $idde = tool::CalcularIdade($now->format("Y-m-d"), date("Y-m-d"));
                echo "<div id=\"Dep_idade\" class=\"uk-badge uk-badge-primary\">( " . $idde . " ) Anos</div>";
            endif;
            ?>
        </label>
        <!-- -->
        <label>
            <span>Documento(CPF)</span>
            <input value="<?php if ($FRM_action == "edit"):echo tool::MascaraCampos("???.???.???-??", $dependentes[0]->cpf); endif; ?>" type="text" class="uk-text-center w_100 " name="Dep_cpf"
                   id="Dep_cpf" placeholder="000.000.000-00"/>
        </label>
        <!-- -->
        <label>
            <span>Identidade(RG)</span>
            <input value="<?php if ($FRM_action == "edit"):echo $dependentes[0]->rg; endif; ?>" type="text" class="uk-text-center w_100" name="Dep_rg" id="Dep_rg"/>
        </label>
        <!-- -->
        <label>
            <span>Certidão Nasc</span>
            <input value="<?php if ($FRM_action == "edit"):echo $dependentes[0]->cn; endif; ?>" type="text" class="uk-text-center w_100" name="dep_cn" id="dep_cn"/>
        </label>
        <!-- -->
        <label>
            <span>Parentesco</span>
            <select class="select" name="Dep_Parentesco" id="Dep_Parentesco">
                <?php
                if ($FRM_action == "edit"):
                    echo '<option selected="selected"  value="' . $dependentes[0]->parentescos_id . '" >' . utf8_encode(strtoupper($dependentes[0]->parentesco)) . '</option>';
                    $parentescos = parentesco::find('all', array('conditions' => array('id != ?', '' . $dependentes[0]->parentescos_id . '')));
                    $descricao = new ArrayIterator($parentescos);
                    while ($descricao->valid()):
                        echo '<option  value="' . $descricao->current()->id . '" >' . utf8_encode(strtoupper($descricao->current()->descricao)) . '</option>';
                        $descricao->next();
                    endwhile;
                else:
                    $parentescos = parentesco::find('all');
                    $descricao = new ArrayIterator($parentescos);
                    echo '<option selected="selected"  value="" >Selecionar</option>';
                    while ($descricao->valid()):
                        echo '<option  value="' . $descricao->current()->id . '" >' . utf8_encode(strtoupper($descricao->current()->descricao)) . '</option>';
                        $descricao->next();
                    endwhile;
                endif;
                ?>
            </select>
        </label>
    </fieldset>
</form>

<a id="Btn_dep_1" class="uk-button uk-button-primary" style="right: 10px; margin-right:5px; width: 120px;bottom:35px; position:absolute; z-index: 2;" data-uk-tooltip="{pos:'left'}"
   title="Salvar Alterações" data-cached-title="Salvar Alterações"><i class="uk-icon-pencil "></i> Salvar</a>

<a id="Btn_dep_2" class="uk-button uk-button-primary" style=" right: 10px; margin-right:5px;width: 120px;bottom:35px; position:absolute;z-index: 2;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo "
   data-cached-title="Gravar Novo "><i class="uk-icon-floppy-o"></i> Gravar</i></a>


<script type="text/javascript">
    // altera a cor de fundo do menu flutuante
    jQuery("#menu-float").css("background-color", "" + $("#" + $("#menu-float").closest('.Window').attr('id') + "").css('border-left-color') + "");
    // altera a cor de fundo
    jQuery("#Empresa_id,#Convenio_id,#Matricula,#Sequencia").css("background-color", "#f5f5f5");

    // trata os botes inicialmente
    <?php if($FRM_action == "edit"):?>
    jQuery("#Btn_dep_2").hide();
    jQuery("#Btn_dep_1").show();
    <?php else:  ?>

    jQuery("#Btn_dep_2").show();
    jQuery("#Btn_dep_1").hide();

    <?php endif; ?>

    // mascara para os campos
    jQuery("#Dep_cpf").mask("999.999.999-99");
    jQuery("#Dep_data_nasc").mask("99/99/9999");


    // grava os dados no banco de dados
    jQuery(function () {

        jQuery("#Btn_dep_1").click(function (event) {

            // mensagen de carregamento
            jQuery("#msg_loading").html("Alterando...");

            //abre a tela de preload
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            jQuery.ajax({
                async: true,
                url: "assets/dependente/Controller_dependente.php",
                type: "post",
                data: 'acao=edit&' + jQuery("#Frmdependente").serialize(),
                success: function (resultado) {
                    if (jQuery.isNumeric(resultado)) {
                        // mensagen de carregamento
                        jQuery("#msg_loading").html(" Carregando ");
                        // variavel com id
                        var matricula = resultado;

                        jQuery("#" + jQuery("#Frmdependente").closest('.Window').attr('id') + "").remove();
                        jQuery("#" + jQuery("#GridDependentes").closest('.Window').attr('id') + "").remove();

                        New_window('users', '780', '500', 'Dependentes', 'assets/dependente/Veiw_dependente.php?matricula=<?php echo $FRM_matricula;?>', true, false, 'Carregando...');

                    } else {
                        //abre a tela de preload
                        modal.hide();
                        UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500});
                    }

                    modal.hide();
                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide();
                }
            });
        });
    });

    // grava os dados no banco de dados
    jQuery(function () {

        jQuery("#Btn_dep_2").click(function (event) {
            jQuery("#msg_loading").html("Gravando...");
            modal.show();
            event.preventDefault();
            jQuery.ajax({
                async: true,
                url: "assets/dependente/Controller_dependente.php",
                type: "post",
                data: 'acao=save&' + $("#Frmdependente").serialize(),
                success: function (resultado) {
                    if (jQuery.isNumeric(resultado)) {
                        jQuery("#msg_loading").html(" Carregando ");
                        var matricula = resultado;
                        jQuery("#" + jQuery("#Frmdependente").closest('.Window').attr('id') + "").remove();
                        jQuery("#" + jQuery("#GridDependentes").closest('.Window').attr('id') + "").remove();

                        New_window('users','780','500','Dependentes','assets/dependente/Veiw_dependente.php?matricula=<?php echo $FRM_matricula;?>',true,false,'Carregando...');
                    } else {
                        modal.hide();
                        UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500});
                    }
                    modal.hide();
                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");
                    modal.hide();
                }
            });
        });
    });
    </script>