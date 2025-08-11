<?php
require_once"../../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_parceiro_id    = isset( $_GET['par_id'])    ? $_GET['par_id']            : tool::msg_erros("O Campo parceiro id é Obrigatorio.");


$Query_especialidades =med_especialidades::find_by_sql("SELECT med_especialidades.*,med_areas.descricao AS desc_area,
    (SELECT count(id) FROM med_procedimentos WHERE med_especialidades_id  = med_especialidades.id) as total_proc   
    FROM med_especialidades 
    LEFT JOIN med_areas ON med_especialidades.med_areas_id = med_areas.id 
    WHERE med_especialidades.empresas_id='".$COB_Empresa_Id."' AND med_especialidades.med_parceiros_id='".$FRM_parceiro_id."' ORDER BY med_especialidades.id");

$List_especialidades   = new ArrayIterator($Query_especialidades);


?>
</div>

<style>
#menu-float a{ background-color:transparent;}
</style>

<div id="menu-float" style="text-align:center;margin:0 800px;top:35px;border:0;background-color:#546e7a;">

    <a class="uk-icon-button uk-icon-plus" id="Btn_MedEsp_001" style="margin-top:2px;text-align:center;"  data-uk-tooltip="{pos:'left'}" title="Adcionar Nova Especialidade" data-cached-title="Adcionar Nova Especialidade" ></a>

</div>

<div id="GridMedEspecialidades" style="height:395px; overflow-y:scroll; margin: 0; width: 100%;">

<?php
// laço que loopa os lançamentos dos convenios  agrupando por data
$List= new ArrayIterator($List_especialidades);
while($List->valid()):

?>
    <article class="uk-comment" style="padding: 5px;">
        <header class="uk-comment-header ">            
            <i class="uk-icon-stethoscope uk-icon-small uk-comment-avatar"></i>
            <h4 class="uk-comment-title uk-text-bold">
                 <?php
                      echo $List->current()->desc_area." [ ".$List->current()->descricao." ]"; 
                 ?>
            </h4>
            <div class="uk-coment-action">
              <div class="uk-button-group" style="margin:-15px -10px;">
                <button class="uk-button" style="float: left;">Ações</button>
                <div data-uk-dropdown="{pos:'left-center',mode:'click'}">
                    <button class="uk-button" ><i class="uk-icon-caret-down"></i></button>
                    <div class="uk-dropdown uk-dropdown-small" >
                        <ul class="uk-nav uk-nav-dropdown">
                               <li><a data-id="<?php echo $List->current()->id;?>" data-uk-tooltip="{pos:'left'}" data-action="0"><i class="uk-icon-edit"></i> Editar/Ver</a></li>
                               <li><a data-id="<?php echo $List->current()->id;?>" data-uk-tooltip="{pos:'left'}" data-action="1"><i class="uk-icon-plus"></i> Procedimentos</a></li>
                         </ul>
                     </div>
                 </div>
             </div>
            </div>
            <div class="uk-comment-meta uk-text-bold "  style="height:10px;">
                Procedimentos: <div class="uk-badge uk-badge-success"><?php echo $List->current()->total_proc; ?></div>
                Status Especialidade: <div class="uk-badge uk-badge-<?php if($List->current()->status == 1){ echo "primary"; }else{ echo "danger"; } ?>"><?php if($List->current()->status == 1){ echo "Ativo"; }else{ echo "Inativo"; } ?></div>
            </div>
        </header>
    </article>

<?php               
$List->next();
endwhile;
?>

</div>


<script type="text/javascript" >

jQuery(document).ready(function(){

jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");// define a cor do menu lateral

});

//Abre uma janela para adcionar especialidade
jQuery("#Btn_MedEsp_001").click(function(){

  jQuery("#"+jQuery("#FrmEsp").closest('.Window').attr('id')+"").remove();// remove a janela atual contendo o formulario especialidades para abrir novamente com a nova chamada
  New_window('plus','700','250','Adcionar especialidade','assets/parceiros/especialidades/Frm_especialidades.php?par_id=<?php echo $FRM_parceiro_id; ?>',true,false,'Carregando...');

});

// abre a janela para edição da especialidade
jQuery("#GridMedEspecialidades a").click(function(){

    var action = jQuery(this).attr('data-action');//pega a linha de exibição
    var esp_id = jQuery(this).attr('data-id');//pega a linha de exibição


    if(action == 0){

    jQuery("#"+jQuery("#FrmEsp").closest('.Window').attr('id')+"").remove();// remove a janela atual contendo o formulario especialidades para abrir novamente com a nova chamada
    New_window('edit','700','250','Editar especialidade','assets/parceiros/especialidades/Frm_especialidades.php?par_id=<?php echo $FRM_parceiro_id; ?>&esp_id='+esp_id+'',true,false,'Carregando...');
    }
    else if(action == 1){
      New_window('list','800','400','Grid Procedimentos','assets/parceiros/procedimentos/Frm_pesquisa_procedimentos.php?par_id=<?php echo $FRM_parceiro_id; ?>&esp_id='+esp_id+'',true,false,'Carregando...');
    }

});

</script>
