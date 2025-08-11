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

<nav class="uk-navbar" style=" padding:8px 8px 8px 0px; text-align: right;">
  <a data-uk-tooltip="" class="uk-button uk-button-primary" title="" data-cached-title="Adcionar Historico" onclick="new_hist();"> <i class="uk-icon-plus "></i> Adcionar </a>
</nav>


<div id="grid_historico" style=" height: 490px; width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; padding-top: 5px; ">

<?php
$list= new ArrayIterator($Q_historico);
while($list->valid()):

echo'<article class="uk-comment">';
echo'<header class="uk-comment-header" ">';
echo $list->current()->historico;

if($list->current()->usuarios_id == $COB_Usuario_Id or $COB_Acesso_Id == 4){
echo '<div style="position:absolute; right:15px; margin:-20px 0;"><a data-uk-tooltip="" title="" data-cached-title="Remover Historico" id="Btn_hist_1"  onclick="remove_hist('.$list->current()->id.')"> <i class="uk-icon-remove uk-icon-small uk-text-danger "></i> </a></div>';

}
echo'</header>';
echo'</article>';

$list->next();
endwhile;


?>

</div>

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

                        if(resultado == 1){
                            /* exibe a msg */
                            UIkit.notify('Erro ao remover histórico.', {timeout: 2000,status:'danger'});
                            /* fecha o preload */
                            modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                        }else{

                            /* exibe a msg de retorno*/
                            UIkit.notify('Histórico removido com sucesso', {timeout: 2000,status:'success'});
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

