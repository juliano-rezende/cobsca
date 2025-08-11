<?php

// blibliotecas
require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_parceiro_id    = isset( $_GET['par_id'])    ? $_GET['par_id']            : tool::msg_erros("O Campo parceiro id é Obrigatorio.");
$FRM_esp_id         = isset( $_GET['esp_id'])    ? $_GET['esp_id']            : tool::msg_erros("O Campo especialidade id é Obrigatorio.");


if(isset($_GET['proc_id'])){


    $FRM_proc_id    = isset( $_GET['proc_id'])    ? $_GET['proc_id']            : tool::msg_erros("O Campo procedimento id é Obrigatorio.");


    $Query_procedimentos =med_procedimentos::find_by_sql("SELECT * FROM med_procedimentos  WHERE id='".$FRM_proc_id."'");

}
?>

<!-- inicio do formúlario de cadastro para novos especialidade-->
<form class="uk-form uk-form-tab" id="FrmProc" style="padding: 10px 0; margin-top: 0;padding-top: 30px; ">
    <label>
      <span>Codigo</span>

      <input  name="procId" type="text" class="uk-text-left w_120 " id="procId" value="<?php  if(isset($Query_procedimentos)): echo tool::CompletaZeros(11,$Query_procedimentos[0]->id); endif; ?>"readonly="readonly"  />
  </label>
  <label >
    <span>Status</span>
    <div class="uk-form-controls">
        <label >
            <input type="radio"  name="st" value="1" <?php  if(isset($Query_procedimentos)){if($Query_procedimentos[0]->status == "1"){echo"checked";} }?> >
            <?php  if(isset($Query_procedimentos) && $Query_procedimentos[0]->status == 1): ?><div class="uk-badge uk-badge-primary>">Ativo</div> <?php else: echo "Ativo"; endif; ?>
            <input type="radio"  name="st" value="0" <?php  if(isset($Query_procedimentos)){if($Query_procedimentos[0]->status == "0"){echo"checked";} }?> >
            <?php  if(isset($Query_procedimentos) && $Query_procedimentos[0]->status == 0): ?><div class="uk-badge uk-badge-danger">Inativo</div> <?php else: echo "Inativo"; endif; ?>

        </label>
    </div>
    </label>
    <label>
        <span>Descrição</span>
        <input  name="dsc" type="text" class="uk-text-left w_400 " id="dsc" value="<?php if(isset($Query_procedimentos)): echo  $Query_procedimentos[0]->descricao; endif; ?>" />
    </label>
    <label>
        <span>Valor Parceiro</span>
        <input name="vlr_custo"  type="text" class=" w_100 uk-text-center " id="vlr_custo"  value="<?php
        if(isset($Query_procedimentos)):   echo number_format($Query_procedimentos[0]->vlr_custo,2,",","."); else: echo"0,00";endif ; ?>" placeholder="0,00"/>
    </label>
    <label>
        <span>Taxa Adm</span>
        <input name="tx_adm"  type="text" class=" w_100 uk-text-center " id="tx_adm"  value="<?php
        if(isset($Query_procedimentos)):   echo number_format($Query_procedimentos[0]->tx_adm,2,",","."); else: echo"0,00";endif ; ?>" placeholder="0,00"/>
    </label>



<a  id="Btn_Medproc_002" class="uk-button uk-button-primary" style="right:10px; margin-right:5px; position:absolute;bottom:40px;  width: 120px;" data-uk-tooltip="{pos:'left'}" title="Confirmar" data-cached-title="Confirmar" >Confirmar</a>

</form>


<script type="text/javascript" >
jQuery(document).ready(function() {

    //abre a tela de preload
    modal.hide();
    jQuery("#Detalhes").hide();

    // mascara para os campos
    jQuery('#tx_adm,#vlr_custo').maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true});

});

// faz o update ou inseri dados
jQuery(function() {

    jQuery("#Btn_Medproc_002").click(function(event) {

         // mensagen de carregamento
         jQuery("#msg_loading").html(" Aguarde... ");

         //abre a tela de preload
         modal.show();

         //desabilita o envento padrao do formulario

         event.preventDefault();

         jQuery.ajax({
            async: true,
            url: "assets/parceiros/procedimentos/Controller_procedimentos.php",
            type: "post",
            data:"esp_id=<?php echo $FRM_esp_id;?>&par_id=<?php echo $FRM_parceiro_id;?>&"+jQuery("#FrmProc").serialize(),
            success: function(resultado) {
                if(jQuery.isNumeric(resultado)){

                                                        // mensagen de carregamento
                                                        jQuery("#msg_loading").html(" Carregando ");
                                                        // recarrega a pagina
                                                        jQuery("#"+jQuery("#procId").closest('.Window').attr('id')+"").remove();

                                                        jQuery("#"+jQuery("#GridMedProcedimentos").closest('.Window').attr('id')+"").remove();

                                                        New_window('list','800','400','Grid Procedimentos','assets/parceiros/procedimentos/Frm_pesquisa_procedimentos.php?par_id=<?php echo $FRM_parceiro_id; ?>&esp_id=<?php echo $FRM_esp_id; ?>',true,false,'Carregando...');


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
