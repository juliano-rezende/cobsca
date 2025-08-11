<?php
include("../../sessao.php");

echo'<div class="tabs-spacer" style="display:none;">';

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_matricula    = isset( $_GET['matricula'])  ? $_GET['matricula']/* variavel com os ids das parcelas*/
: tool::msg_erros("O Campo matricula Obrigatorio faltando.");


$Q_historico= historicos::find_by_sql("SELECT SQL_CACHE * FROM historicos  WHERE matricula = ".$FRM_matricula."");


echo'</div>';
?>
<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/accordion.min.css">


<div id="grid_historico" style=" height:500px; width:98.5%; overflow:auto; padding:5px; margin:0 auto;background:#fff; ">

    <?php
    $list= new ArrayIterator($Q_historico);
    while($list->valid()):

        echo'<article class="uk-comment">';
        echo'<header class="uk-comment-header" ">';
        echo $list->current()->historico;

        if($list->current()->usuarios_id == $COB_Usuario_Id or $COB_Acesso_Id == 4){
            echo '<button class="uk-button uk-button-small uk-button-danger" type="button" style=" float: right; position:relative; margin-top:-30px; onclick="remove_hist('.$list->current()->id.')"><i class="uk-icon-remove"></i> Remover</button>';

        }
        echo'</header>';
        echo'</article>';

        $list->next();
    endwhile;


    ?>

</div>

<nav class="uk-navbar" style="padding:8px 18px 8px 0px; text-align: right; border-top: 1px solid #ccc;">
    <button data-uk-tooltip="" title="" data-cached-title="Adcionar Historico" class="uk-button uk-button-small uk-button-success" type="button" onclick="new_hist();"><i class="uk-icon-plus"></i> Novo</button>
</nav>

<script type="text/javascript" src="framework/uikit-2.24.0/js/components/accordion.min.js"></script>

<script type="text/javascript">

    function new_hist(){New_window('plus','700','300','Adcionar Histórico','assets/associado/Frm_historico.php',true,false,'Carregando...');}

    function remove_hist(id){

// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde...");
//abre a tela de preload
modal.show();

var data="action=remove_historico&id="+id+"";


jQuery.ajax({
 async: true,
 url: "assets/associado/Controller_matricula.php",
 type: "post",
 data:data,
 success: function(resultado) {

    var text = '{"'+resultado+'"}';
    var obj = JSON.parse(text);

                        // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                        if(obj.callback == 1){
                            /* exibe a msg */
                            UIkit.notify(''+obj.msg+'', {timeout: 2000,status:''+obj.status+''});
                            /* fecha o preload */
                            modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                    }else{

                        /* exibe a msg de retorno*/
                        UIkit.notify(''+obj.msg+'', {timeout: 2000,status:''+obj.status+''});
                        /* removemos as janelas abertas */
                        jQuery("#"+jQuery("#grid_historico").closest('.Window').attr('id')+"").remove();
                        jQuery("#"+jQuery("#FrmHistorico").closest('.Window').attr('id')+"").remove();
                        /* abrimos a tela novamente atualizada*/
                        New_window('file-text-o','950','550','Histórico do Contrato','assets/associado/Historico_contrato.php?matricula='+jQuery("#matricula").val()+'',true,false,'Carregando...');
                        /* fechamos o preload*/
                        modal.hide();

                    }
                },
                error:function (){
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                }

            });
}


</script>

