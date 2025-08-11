<?php
require_once "../../sessao.php";
?>

<nav class="uk-navbar uk-gradient-cinza">
    <div class="uk-navbar-content uk-hidden-small uk-form">
        <input type="search" id="vl" autocomplete="off" class="uk-width" style="width:600px;" placeholder="MATRICULA | NOME | DATA NASCIMENTO | CPF | NOME DEPENDENTE" onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { BuscaAssociado(); }">
        <label style="margin:2px 10px;"> <input type="radio" name="tp" value="0" checked="checked"> Titular
            <input type="radio" name="tp" value="1"> dependente </label>
        <a class="uk-icon-search uk-icon-small" style="margin-left:10px;" onclick="BuscaAssociado();"></a>
    </div>
        <div class="uk-navbar-content uk-navbar-flip">
            <a id="btn_new_ass" class="uk-button uk-button-primary"><i class="uk-icon-user"></i> Criar novo</a>
        </div>
</nav>

<div id="gridassociado" style="height:464px; overflow-y:auto; padding:5px; margin-top: 2px; ">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div><!-- fim gridassociado -->

<script type="text/javascript">

    jQuery("#gridassociado").load('assets/associado/Veiw_search_associados.php');
    jQuery('#btn_new_ass').click(function () {
        LoadContent('assets/associado/Frm_associado.php', 'content');
    });		/*janelas de cadastro area de atendimento*/


    function BuscaAssociado() {

// inicia o loader
        modal.show();

        // variaveis recuperadas
        var vl = $("#vl").val();
        var tp = $("input[name='tp']:checked").val();

        // pesquisa na tabela associados
        if (tp == 0) {

            // envia os dados para o banco
            $.post("assets/associado/Veiw_search_associados.php",
                {acao: 1, vl: vl},
                // Carregamos o resultado acima
                function (resultado) {
                    $("#gridassociado").html(resultado);//carrega o resulta da requisição na div
                    modal.hide();
                });
        } else {
            // envia os dados para o banco
            $.post("assets/associado/Veiw_search_dependentes.php",
                {vl: vl},
                // Carregamos o resultado acima
                function (resultado) {
                    $("#gridassociado").html(resultado);//carrega o resulta da requisição na div
                    modal.hide();
                });
        }
    }
</script>