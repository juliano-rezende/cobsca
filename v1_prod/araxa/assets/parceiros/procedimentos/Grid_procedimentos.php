<?php
require_once"../../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_esp_id         = isset( $_GET['esp_id'])    ? $_GET['esp_id'] : $_POST['esp_id'];
$FRM_parceiro_id    = isset( $_GET['par_id'])    ? $_GET['par_id'] : $_POST['par_id'];




if(isset($_POST['acao'])){

$Vlr = trim($_POST['vl']);

echo $Vlr;

$Query_procedimentos =med_procedimentos::find_by_sql("SELECT med_procedimentos.*,med_especialidades.descricao AS desc_esp 
    FROM med_procedimentos 
    LEFT JOIN med_especialidades ON med_procedimentos.med_especialidades_id = med_especialidades.id 
    WHERE med_procedimentos.med_parceiros_id='".$FRM_parceiro_id."' AND med_procedimentos.med_especialidades_id='".$FRM_esp_id."' AND med_procedimentos.descricao LIKE '%".$Vlr."%' ORDER BY med_especialidades.id");

}else{


$Query_procedimentos =med_procedimentos::find_by_sql("SELECT med_procedimentos.*,med_especialidades.descricao AS desc_esp 
    FROM med_procedimentos 
    LEFT JOIN med_especialidades ON med_procedimentos.med_especialidades_id = med_especialidades.id 
    WHERE med_procedimentos.med_parceiros_id='".$FRM_parceiro_id."' AND med_procedimentos.med_especialidades_id='".$FRM_esp_id."' ORDER BY med_especialidades.id");

}



$List_procedimentos  = new ArrayIterator($Query_procedimentos);


?>
</div>


<div id="GridMedProcedimentos">

<?php
// laço que loopa os lançamentos dos convenios  agrupando por data
$List= new ArrayIterator($List_procedimentos);
while($List->valid()):

?>
    <article class="uk-comment" style="padding: 5px;">
        <header class="uk-comment-header ">            
            <i class="uk-icon-stethoscope uk-icon-small uk-comment-avatar"></i>
            <h4 class="uk-comment-title uk-text-bold">
                 <?php
                      echo $List->current()->descricao." [ ".$List->current()->desc_esp." ]"; 
                 ?>
            </h4>
            <div class="uk-coment-action">
              <div class="uk-button-group" style="margin:-15px -10px;">
                <button class="uk-button" style="float: left;">Ações</button>
                <div data-uk-dropdown="{pos:'left-center',mode:'click'}">
                    <button class="uk-button" ><i class="uk-icon-caret-down"></i></button>
                    <div class="uk-dropdown uk-dropdown-small" >
                        <ul class="uk-nav uk-nav-dropdown">
                               <li><a data-id="<?php echo $List->current()->id;?>" data-uk-tooltip="{pos:'left'}"><i class="uk-icon-edit"></i> Editar/Ver</a></li>
                         </ul>
                     </div>
                 </div>
             </div>
            </div>
            <div class="uk-comment-meta uk-text-bold "  style="height:10px;"> 
                Valor Parceiro: R$ <?php echo number_format($List->current()->vlr_custo,2,",","."); ?> | 
                Taxa Administrativa: R$ <?php echo number_format($List->current()->tx_adm,2,",",".");?> | 
                Status Procedimento: <div class="uk-badge uk-badge-<?php if($List->current()->status == 1){ echo "primary"; }else{ echo "danger"; } ?>"><?php if($List->current()->status == 1){ echo "Ativo"; }else{ echo "Inativo"; } ?></div> 
            </div>
        </header>
    </article>

<?php               
$List->next();
endwhile;
?>

</div>

<script type="text/javascript" >

// abre a janela para edição da especialidade
jQuery("#GridMedProcedimentos a").click(function(){

    var pro_id = jQuery(this).attr('data-id');//pega a linha de exibição
    jQuery("#"+jQuery("#FrmProc").closest('.Window').attr('id')+"").remove(); // remove a janela atual contendo o formulario procedimentos para abrir novamente com a nova chamada
    New_window('list','700','250','Editar Procedimento','assets/parceiros/procedimentos/Frm_procedimentos.php?proc_id='+pro_id+'&par_id=<?php echo $FRM_parceiro_id; ?>&esp_id=<?php echo $FRM_esp_id; ?>',true,false,'Carregando...');
    

});

</script>
