<?php

include("../../sessao.php");

echo'<div class="tabs-spacer" style="display:none;">';

include("../../conexao.php");

$cfg->set_model_directory('../../models/');

$FRM_matricula    = isset( $_GET['matricula'])  ? $_GET['matricula']: tool::msg_erros("O Campo matricula Obrigatorio faltando.");

$Q_historico= historicos::find_by_sql("SELECT * FROM historicos WHERE matricula = '".$FRM_matricula."'");

echo'</div>';
?>
<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/accordion.min.css">

<nav class="uk-navbar" style=" padding:8px 8px 8px 0px; text-align: right;">

  <a data-uk-tooltip="" title="" data-cached-title="Adcionar Historico" onclick="new_hist('<?php echo $FRM_matricula; ?>')"> <i class="uk-icon-plus uk-icon-medium "> </i> </a>

</nav>


<div id="grid_historico" style=" height: 490px; width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; ">


    <?php
    $list= new ArrayIterator($Q_historico);
    while($list->valid()):

        echo'<article class="uk-comment">';
        echo'<header class="uk-comment-header" ">';
        echo $list->current()->historico;

        if($list->current()->usuarios_id == $COB_Usuario_Id){
            echo '<div style=" margin-top:-40px; margin-right:10px;float:right;"><a data-uk-tooltip="" title="" data-cached-title="Editar Historico" id="Btn_hist_1"  onclick="remove_hist('.$list->current()->id.','.intval($FRM_matricula).')"> <i class="uk-icon-remove uk-icon-small uk-text-danger "></i> Remover</a></div>';

        }
        echo'</header>';
        echo'</article>';

        $list->next();
    endwhile;


    ?>

</div>

<script type="text/javascript" src="framework/uikit-2.24.0/js/components/accordion.min.js"></script>

<script type="text/javascript">

    function new_hist(matricula){New_window('plus','700','300','Adcionar Histórico','assets/associado/Frm_historico.php?matricula='+matricula+'',true,false,'Carregando...');}

    function remove_hist(id,mat){

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
                        New_window('file-text-o','950','550','Histórico do Contrato','assets/associado/Historico_contrato.php?matricula='+mat+'',true,false,'Carregando...');
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

