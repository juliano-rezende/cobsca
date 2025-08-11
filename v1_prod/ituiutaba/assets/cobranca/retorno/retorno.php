<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../sessao.php");

require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
</div>
<style>
.group_button a {border-radius:0; border:0;}
</style>
<div id="Form_0" class="uk-modal">
<div class="uk-modal-dialog" style="width:500px;">
    <!--<button type="button" class="uk-modal-close uk-close"></button> -->
    <div class="uk-modal-header ">
    <h2><i class="uk-icon-bank  uk-icon-small" ></i> Filtrar Banco</h2>
    </div>
      <form name="FrmFiltroRetorno" method="post" id="FrmFiltroRetorno" class="uk-form">
        <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
          <label id="lab00">  <!--pesquisar por conta bancaria -->
          <span>Banco</span>
          <select name="cod_contas_id_grid" class="select w_300" id="cod_contas_id_grid" >
          <option value="" selected></option>
            <?php
              $contas=contas_bancarias::find('all',array('conditions'=>array('status= ? AND tp_conta!= ?','1','0')));
              $listcontas= new ArrayIterator($contas);
              while($listcontas->valid()):
              echo'<option value="'.$listcontas->current()->id.'" >'.$listcontas->current()->cod_banco." ".utf8_encode($listcontas->current()->nm_conta).'</option>';
              $listcontas->next();
              endwhile;
            ?>
          </select>
          </label>
        </fieldset>
      </form>
      <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="BtnRet00" class="uk-button uk-button-small" ><i class="uk-icon-angle-double-right" ></i> Pesquisar</a>
    </div>
</div>
</div>
<div id="Form_1" class="uk-modal">
<div class="uk-modal-dialog" style="width:500px;">
    <button type="button" class="uk-modal-close uk-close"></button>
    <div class="uk-modal-header ">
    <h2><i class="uk-icon-file-text-o  uk-icon-small" ></i> Importar retorno</h2>
    </div>
    <form action="controllers/Controller_upload_retorno.php" method="post" id="form_upload" enctype="multipart/form-data" class="uk-form" >

        <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
        <label id="lab00">
          <input type="file" name="arquivo[]" id="arquivo[]" class="w_400 file" multiple>
        </label>
        <label id="lab00">  <!--pesquisar por conta bancaria -->
          <span style="width: 120px; text-align: left;">Banco</span>
          <select name="cod_contas_id_ret" class="select w_400" id="cod_contas_id_ret" >
          <option value="" selected></option>
            <?php
              $contas=contas_bancarias::find_by_sql("SELECT contas_bancarias.id,contas_bancarias.nm_conta,contas_bancarias.cod_banco, contas_bancarias_cobs.tipo_arquivo
                                                     FROM contas_bancarias
                                                     LEFT JOIN contas_bancarias_cobs
                                                     ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
                                                     WHERE  contas_bancarias.status = '1' AND  contas_bancarias.tp_conta != '0'");
              $listcontas= new ArrayIterator($contas);
              while($listcontas->valid()):
              echo'<option value="'.$listcontas->current()->id.'-'.$listcontas->current()->tipo_arquivo.'" >'.$listcontas->current()->cod_banco." ".utf8_encode($listcontas->current()->nm_conta).'</option>';
              $listcontas->next();
              endwhile;
            ?>
          </select>
          </label>
          <label>
              <div class="uk-progress" style="display: none;">
              <div class="uk-progress-bar" style="width:0%;">0%</div>
          </div>
          </label>
        </fieldset>
      </form>
      <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="BtnRet01" class="uk-button uk-button-small" ><i class="uk-icon-angle-double-right" ></i> Enviar</a>
    </div>
</div>
</div>
<nav class="uk-navbar ">
        <div class="uk-button-group group_button"  style="border:0px solid #ccc;float:right; padding: 5px; ">
        <a href="JavaScript:void(0);" class="uk-button uk-button-small uk-button-success" data-uk-modal="{target:'#Form_0'}" style="border-left:1px solid #ccc;padding-top:2px; line-height: 30px;" >
        <i class="uk-icon-filter " ></i> Pesquisar Banco</a>
        <a href="JavaScript:void(0);" class="uk-button uk-button-small uk-button-primary" data-uk-modal="{target:'#Form_1'}" style="border-left:1px solid #ccc;padding-top:2px; line-height: 30px;" >
        <i class="uk-icon-file-text-o" ></i> Importar Retorno</a>
        </div>
</nav>
<nav class="uk-navbar ">
<table  class="uk-table" >
    <thead >
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:90px;" >Codigo</th>
        <th class="uk-width uk-text-center" style="width:50px;" >banco</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Status</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Imp</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Proc</th>
        <th class="uuk-text-left" >Arquivo</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Entradas</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Baixas</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Compesações</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Erros</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Total</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Lote Retorno</th>
        <th class="uk-width uk-text-center" style="width:80px;" ></th>
      </tr>
    </thead>
 </table>
</nav>
<div id="Grid_retorno" style="background-color: #fff; overflow-x:hidden;  height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-80;?>px;">
</div>



<script src="js/jquery/plugins/jquery_form/jquery.form.js"></script>

<script type="text/javascript" charset="utf-8" async defer>


jQuery(function() {

 jQuery("#BtnRet00").click(function(event) {

 // mensagen de carregamento
 jQuery("#msg_loading").html("Aguarde ");

 //abre a tela de preload
 modal.show();

 //desabilita o envento padrao do formulario
 event.preventDefault();

 jQuery.ajax({
        async: true,
        url: "assets/cobranca/retorno/ajax_grid_retorno.php",
        type: "post",
        data:jQuery("#FrmFiltroRetorno").serialize(),
        success: function(resultado) {

                    jQuery("#Grid_retorno").html(resultado);
                    modal.hide();
        },
        error:function (){
          UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
          modal.hide();
        }
    });
  });
});

/* Faz o upload do arquivo */
jQuery(document).ready(function(){

    jQuery("#BtnRet01").click( function(event){

        /* reseta a ação padrao do form*/
        event.preventDefault();

        /* mensagen de carregamento*/
        jQuery("#msg_loading").html("Fazendo upload de arquivos.");
        /*abre a tela de preload*/
        modal.show();

        /* abre a barra de progresso*/
        jQuery('.uk-progress').show();

        /* envia os dados para upload */
        jQuery("#form_upload").ajaxForm({

            url: 'assets/cobranca/retorno/controllers/Controller_upload_retorno.php',
            uploadProgress: function(event, position, total, percentComplete) {
                jQuery('.uk-progress-bar').html(percentComplete+'%').css('width',percentComplete+'%');
            },
            success: function(result) {


              /*prepara o formulario para um novo upload*/
              jQuery(".file").val("");
              jQuery('.uk-progress').hide();
              jQuery('.uk-progress-bar').css('width','0%');

              /* recupera o codigo do banco para atualizar o grid*/
              var str = jQuery("#cod_contas_id_ret").val();
              var contas_b_id = str.split("-");

              /* exibe o resultado do upload*/
              UIkit.modal.alert(""+result+"");

              /* mensagen de carregamento*/
              jQuery("#msg_loading").html("Aguarde. ");

              /* atualiza o grid*/
              $.post("assets/cobranca/retorno/ajax_grid_retorno.php",{cod_contas_id_grid:contas_b_id[0]},function(data){

                jQuery("#Grid_retorno").html(data);
                /* fecha o loading*/
                modal.hide();
              });

       },
            error: function(){
              UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
              modal.hide();
            }

        }).submit()

    });


jQuery("#arquivo").change(function(){jQuery('.uk-progress-bar').html('0%');jQuery('.uk-progress-bar').css('width','0%'); });

});


</script>