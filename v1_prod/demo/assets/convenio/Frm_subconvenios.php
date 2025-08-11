<?php
require_once"../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_convenio_id    = isset( $_GET['convenio_id'])    ? $_GET['convenio_id']            : tool::msg_erros("O Campo convenio_id é Obrigatorio.");

$Query_subconvenios =sub_convenios::find_by_sql("SELECT * FROM sub_convenios WHERE empresas_id='".$COB_Empresa_Id."' AND convenios_id='".$FRM_convenio_id."' ORDER BY id");
$List_subcovenios   = new ArrayIterator($Query_subconvenios);


$lf="1";
?>
</div>

<style>
#menu-float a{ background-color:transparent;}
.uk-text-warning {
    color: #F90 !important;
}
#NovoSubconvenio  span{  width:150px;  }
</style>

<div id="menu-float" style="text-align:center;margin:0 900px;top:35px;border:0;background-color:#546e7a;">

    <a class="uk-icon-button uk-icon-file-o" style="margin-top:2px;text-align:center;" data-uk-modal="{target:'#NovoSubconvenio'}" data-uk-tooltip="{pos:'left'}" title="Adcionar Novo Subconvenio" data-cached-title="Adcionar Novo Subconvenio" ></a>

</div>

<nav class="uk-navbar">
<table  class="uk-table" >
  <thead >
    <tr style="line-height: 20px;">
        <th class="uk-width uk-text-left" style="width:20px;"></th>
        <th class="uk-width uk-text-left" style="width:250px;"  >Subconvenio</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Cnpj</th>
        <th class="uk-width uk-text-center" style="width:100px;">Fone Fixo</th>
        <th class="uk-text-left" >Contato</th>
        <th class="uk-width uk-text-center" style="width:30px;" >Ações</th>
    </tr>
    </thead>
 </table>
</nav>

<div id="GridSubconvenios" style="height:380px; overflow-y:scroll;">
<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody >
<?php
// laço que loopa os lançamentos dos convenios  agrupando por data
$List_sub= new ArrayIterator($List_subcovenios);
while($List_sub->valid()):

?>
    <tr style="line-height: 20px;" data-sub="<?php echo $List_sub->current()->id; ?>">
        <th class="uk-width uk-text-center " style="width:20px;" ><?php echo $lf; ?></th>
        <td class="uk-width uk-text-left" style="width:250px;"  ><?php echo $List_sub->current()->nm_fantasia; ?></td>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo $List_sub->current()->cnpj; ?></td>
        <td class="uk-width uk-text-center" style="width:100px;"><?php echo $List_sub->current()->fone_fixo; ?></td>
        <td class="uk-text-left"><?php echo $List_sub->current()->nm_fantasia; ?></td>
        <td class="uk-text-center" ><td class="uk-width uk-text-center" style="width:30px;" >
        <?php if($List_sub->current()->status == 0): ?>
        <a class="uk-icon-check uk-icon-medium BtnEnabledSub" style="margin-left:10px;" data-uk-tooltip="{pos:'left'}" title="Ativar" data-cached-title="Ativar"></a>
        <?php else: ?>
        <a class="uk-icon-times-circle uk-icon-medium BtnDisabledSub" style="margin-left:10px;" data-uk-tooltip="{pos:'left'}" title="Desativar" data-cached-title="Desativar"></a>
        <?php endif; ?>
        </td>
    </tr>
<?php
$lf++;
$List_sub->next();
endwhile;
?>
</tbody>
</table>
</div>
<!-- inicio do formúlario de cadastro para novos subconvênios-->
<form class="uk-form uk-form-tab" id="FrmNovoSubconvenio" style="padding: 0; margin: 0;">
<div id="NovoSubconvenio" class="uk-modal">
    <div class="uk-modal-dialog" style="width:700px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
        <h2><i class="uk-icon-plus" ></i> Adcionar Novo Subconvenio</h2>
        </div>
            <label>
            <span>Razão Social</span>
            <input  name="subRzsc" type="text" class="uk-text-left w_400 " id="subRzsc"  />
        </label>
        <label>
            <span>Nome Fantasia</span>
            <input name="subNmfant" type="text" class="uk-text-left w_400 " id="subNmfant"  />
        </label>
        <label>
            <span>Cnpj</span>
            <input name="subCnpj" type="text" class="uk-text-center w_150 " id="subCnpj"  />
        </label>
        <label>
            <span>Fone fixo</span>
            <div class="uk-form-icon">
                <i class="uk-icon-fax"></i>
                <input name="subFonefx" type="text" class="uk-text-center w_150  " id="subFonefx">
            </div>
        </label>
        <label>
            <span>Contato</span>
            <div class="uk-form-icon">
                <i class="uk-icon-user"></i>
                <input name="subContact" type="text" class="uk-text-left w_400" id="subContact">
            </div>
        </label>        
        <div class="uk-modal-footer uk-text-right">
            <a id="Btn_subConvenio_001" class="uk-button uk-button-primary" ><i class="uk-icon-check" ></i> Confirmar</a>
            <a id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
    </div>
</div>
</form>



<script type="text/javascript" >

jQuery(document).ready(function(){

// mascara para os campos
jQuery("#subCnpj").mask("99.999.999/9999-99");
jQuery("#subFonefx").mask("(99) 9999-9999");
jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");// define a cor do menu lateral

});

// faz o update dos dados
jQuery(function() {

    jQuery("#Btn_subConvenio_001").click(function(event) {

         // mensagen de carregamento
         jQuery("#msg_loading").html(" Aguarde... ");

         //abre a tela de preload
         modal.show();

         //desabilita o envento padrao do formulario
         event.preventDefault();

         jQuery.ajax({
                        async: true,
                        url: "assets/convenio/Controller_subconvenios.php",
                        type: "post",
                        data:"action=new&Conv_id=<?php echo $FRM_convenio_id; ?>&"+jQuery("#FrmNovoSubconvenio").serialize(),
                        success: function(resultado) {
                                                        if(jQuery.isNumeric(resultado)){
                                                                // mensagen de carregamento
                                                                jQuery("#msg_loading").html(" Carregando ");
                                                                // variavel com id
                                                                var conv_id=resultado;
                                                                // recarrega a pagina
                                                                jQuery("#"+jQuery("#FrmNovoSubconvenio").closest('.Window').attr('id')+"").remove();
                                                                New_window('list-alt','900','400','Subconvenios','assets/convenio/Frm_subconvenios.php?convenio_id=<?php echo $FRM_convenio_id; ?>',true,false,'Carregando...');

                                                        }else{
                                                                //abre a tela de preload
                                                                modal.hide();
                                                                UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
                                                            }
                        },
                        error:function (){
                            UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                            modal.hide();
                        }

        });

    });
});


/* desativar sub convenio */
/* por questoes de segurança não é permitido remover o subconvenios aplicando apenas a desativação*/
// faz o update dos dados
jQuery(function() {

    jQuery(".BtnDisabledSub").click(function(event) {

         // mensagen de carregamento
         jQuery("#msg_loading").html(" Aguarde... ");

         //abre a tela de preload
         modal.show();

         //desabilita o envento padrao do formulario
        var subConvId = jQuery(this).closest('tr').attr('data-sub');
    
        event.preventDefault();

        jQuery.ajax({
                        async: true,
                        url: "assets/convenio/Controller_subconvenios.php",
                        type: "post",
                        data:"action=disabled&SubConvId="+subConvId+"",
                        success: function(resultado) {
                                                        if(jQuery.isNumeric(resultado)){
                                                                // mensagen de carregamento
                                                                jQuery("#msg_loading").html(" Carregando ");
                                                                // variavel com id
                                                                var conv_id=resultado;
                                                                // recarrega a pagina
                                                                jQuery("#"+jQuery("#FrmNovoSubconvenio").closest('.Window').attr('id')+"").remove();
                                                                New_window('list-alt','900','400','Subconvenios','assets/convenio/Frm_subconvenios.php?convenio_id=<?php echo $FRM_convenio_id; ?>',true,false,'Carregando...');
                                                        }else{
                                                                //abre a tela de preload
                                                                modal.hide();
                                                                UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
                                                            }
                        },
                        error:function (){
                            UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                            modal.hide();
                        }
        });
    });
});


/* ativar sub convenio */
// faz o update dos dados
jQuery(function() {

    jQuery(".BtnEnabledSub").click(function(event) {

         // mensagen de carregamento
         jQuery("#msg_loading").html(" Aguarde... ");

         //abre a tela de preload
         modal.show();

         //desabilita o envento padrao do formulario
        var subConvId = jQuery(this).closest('tr').attr('data-sub');
   
        event.preventDefault();

        jQuery.ajax({
                        async: true,
                        url: "assets/convenio/Controller_subconvenios.php",
                        type: "post",
                        data:"action=enabled&SubConvId="+subConvId+"",
                        success: function(resultado) {
                                                        if(jQuery.isNumeric(resultado)){
                                                                // mensagen de carregamento
                                                                jQuery("#msg_loading").html(" Carregando ");
                                                                // variavel com id
                                                                var conv_id=resultado;
                                                                // recarrega a pagina
                                                                jQuery("#"+jQuery("#FrmNovoSubconvenio").closest('.Window').attr('id')+"").remove();
                                                                New_window('list-alt','900','400','Subconvenios','assets/convenio/Frm_subconvenios.php?convenio_id=<?php echo $FRM_convenio_id; ?>',true,false,'Carregando...');
                                                        }else{
                                                                //abre a tela de preload
                                                                modal.hide();
                                                                UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
                                                            }
                        },
                        error:function (){
                            UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                            modal.hide();
                        }
        });
    });
});

</script>