<div class="uk-modal-dialog" style="width:500px;">
    <!--<button type="button" class="uk-modal-close uk-close"></button> -->
    <div class="uk-modal-header ">
        <h2><i class="uk-icon-filter uk-icon-small"></i> Filtro</h2>
    </div>
    <form name="FrmFiltroRecebimentos" method="post" id="FrmFiltroRecebimentos" class="uk-form">
        <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">

            <label>
                <span>Pesquisar por</span>
                <select name="pesquisarpor" class="select " id="pesquisarpor">
                    <option value="" selected="selected"></option>
                    <option value="0">Data da Vencimento</option>
                    <option value="1">Data da Pagamento</option>
                    <option value="3">Conta de recebimento</option>
                    <?php
                    if ($COB_Acesso_Id >= 2) :
                    ?>
                        <option value="2">Operador</option>
                        <option value="4">Todos operadores</option>
                    <?php
                    endif;
                    ?>
                </select>
            </label>
            <?php
            if ($COB_Acesso_Id >= 2) :
            ?>
                <label style="display: none;" id="label2">
                    <span>Conta</span>
                    <select name="cdcontabanco" class="select" id="cdcontabanco">
                        <?php
                        $conta = contas_bancarias::all(array('conditions' => array('status= ? AND empresas_id= ?', '1', $COB_Empresa_Id)));
                        $desconta = new ArrayIterator($conta);
                        while ($desconta->valid()) :
                            echo '<option value="' . $desconta->current()->id . '" >' . utf8_encode($desconta->current()->nm_conta) . '</option>';
                            $desconta->next();
                        endwhile;
                        ?>
                    </select>
                </label>
            <?php
            endif;
            ?>
            <label style="display: none;" id="label1">
                <span>Operador</span>
                <select name="operador" class="select" id="operador">
                    <?php
                    $query_c = users::find_by_sql("SELECT * FROM usuarios WHERE empresas_id='" . $COB_Empresa_Id . "' AND acessos_id < '4' AND status = '1' ORDER BY id ASC ");
                    $usuarios = new ArrayIterator($query_c);
                    while ($usuarios->valid()) :
                        echo '<option value="' . $usuarios->current()->id . '" >' . utf8_encode($usuarios->current()->nm_usuario) . '</option>';
                        $usuarios->next();
                    endwhile;
                    ?>
                </select>
            </label>
            <label>
                <span>Data Inicial</span>
                <div class="uk-form-icon">
                    <i class="uk-icon-calendar"></i>
                    <input name="dtini" value="<?php echo date("d/m/Y"); ?>" type="text" class="input_text w_100 center periodo" id="dtini" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
                </div>
            </label>
            <label>
                <span>Data Final</span>
                <div class="uk-form-icon">
                    <i class="uk-icon-calendar"></i>
                    <input name="dtfim" value="<?php echo date("d/m/Y"); ?>" type="text" class="input_text w_100 center periodo" id="dtfim" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
                </div>
            </label>

        </fieldset>

    </form>
    <div class="uk-modal-footer uk-text-right">
        <a href="JavaScript:void(0);" id="Btn_tt_00" class="uk-button uk-button-small"><i class="uk-icon-search"></i> Visualizar</a>
        <a href="JavaScript:void(0);" id="Btn_tt_01" class="uk-button uk-button-small"><i class="uk-icon-print"></i> Imprimir</a>
        <a href="JavaScript:void(0);" id="Btn_tt_02" class="uk-button uk-button-danger uk-button-small uk-modal-close"><i class="uk-icon-remove"></i> Cancelar</a>
    </div>
</div>

<script type="text/javascript">
    jQuery("#dtini,#dtfim").mask("99/99/9999");
    jQuery("#lab00").hide();


    jQuery("#pesquisarpor").change(function() {


        var $this = jQuery(this).val();

        if ($this == 2) {

            jQuery("#label2").hide();
            jQuery("#label1").show();
            return false;

        }
        if ($this == 3) {

            jQuery("#label1").hide();
            jQuery("#label2").show();
            return false;

        }
        jQuery("#label1").hide();
        jQuery("#label2").hide();


    });

    jQuery(function() {

        jQuery("#Btn_tt_00").click(function(event) {

            // mensagen de carregamento
            jQuery("#msg_loading").html("Pesquisando ");

            //abre a tela de preload
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            console.log(jQuery("#FrmFiltroRecebimentos").serialize());

            jQuery.ajax({
                async: true,
                url: "assets/relatorios/recebimentos/ajax_grid_recebimentos.php",
                type: "post",
                data: jQuery("#FrmFiltroRecebimentos").serialize()+"&frm=1",
                success: function(resultado) {
                    //abre a tela de preload
                    jQuery("#Grid_recebimentos").html(resultado);
                    //abre a tela de preload
                    modal.hide();
                },
                error: function() {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404"); /*erro de caminho invalido do arquivo*/
                    modal.hide();
                }

            });

        });

    });

    document.getElementById('Btn_tt_01').onclick = function() {

        var conteudo = document.getElementById('Grid_recebimentos').innerHTML,
            tela_impressao = window.open('about:blank');

        tela_impressao.document.write(conteudo);
        tela_impressao.window.print();
        tela_impressao.window.close();
    };
</script>