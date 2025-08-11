<?php

// blibliotecas
require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_parceiro_id    = isset( $_GET['par_id'])    ? $_GET['par_id']            : tool::msg_erros("O Campo parceiro id é Obrigatorio.");

if(isset($_GET['esp_id'])){

$FRM_esp_id    = isset( $_GET['esp_id'])    ? $_GET['esp_id']            : tool::msg_erros("O Campo especialidade id é Obrigatorio.");

$Query_especialidades =med_especialidades::find_by_sql("SELECT * FROM med_especialidades  WHERE id='".$FRM_esp_id."'");

}
?>
</div>

<!-- inicio do formúlario de cadastro para novos especialidade-->
<form class="uk-form uk-form-tab" id="FrmEsp" style="padding: 10px 0; margin-top: 0;padding-top: 30px; ">
    <label>
      <span>Codigo</span>

    <input  name="espId" type="text" class="uk-text-left w_120 " id="espId" value="<?php  if(isset($Query_especialidades)): echo tool::CompletaZeros(11,$Query_especialidades[0]->id); endif; ?>"readonly="readonly"  />

  </label>
        <label >
            <span>Status</span>
            <div class="uk-form-controls">
                <label >
                    <input type="radio"  name="st" value="1" <?php  if(isset($Query_especialidades)){if($Query_especialidades[0]->status == "1"){echo"checked";} }?> >
                    <?php  if(isset($Query_especialidades) && $Query_especialidades[0]->status == 1): ?><div class="uk-badge uk-badge-primary>">Ativo</div> <?php else: echo "Ativo"; endif; ?>
                    <input type="radio"  name="st" value="0" <?php  if(isset($Query_especialidades)){if($Query_especialidades[0]->status == "0"){echo"checked";} }?> >
                    <?php  if(isset($Query_especialidades) && $Query_especialidades[0]->status == 0): ?><div class="uk-badge uk-badge-danger">Inativo</div> <?php else: echo "Inativo"; endif; ?>
                
                </label>
            </div>
        </label>
    <label>
        <span>Descrição</span>
        <input  name="dsc" type="text" class="uk-text-left w_400 " id="dsc" value="<?php if(isset($Query_especialidades)): echo  $Query_especialidades[0]->descricao; endif; ?>" />
    </label>
    <label> 
        <span>Area Méd</span>
        <select name="area" class="select " id="area" >
        <?php
                if(isset($Query_especialidades)):

                    $areas=med_areas::find('all',array('conditions'=>array('status= ?','1')));

                    $descricao= new ArrayIterator($areas);

                    while($descricao->valid()):


                        if($descricao->current()->id == $Query_especialidades[0]->med_areas_id){$select='selected="selected"';}else{$select="";}

                        echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($descricao->current()->descricao)).'</option>';

                        $descricao->next();
                    endwhile;

                else:

                    $areas=med_areas::find('all',array('conditions'=>array('status= ?','1')));
                    $descricao= new ArrayIterator($areas);
                    echo'<option value="" selected></option>';
                    while($descricao->valid()):
                        echo'<option value="'.$descricao->current()->id.'" >'.utf8_encode(strtoupper($descricao->current()->descricao)).'</option>';
                        $descricao->next();
                    endwhile;
                endif;
                ?>
        </select>
    </label>   
    <a  id="Btn_MedEsp_002" class="uk-button uk-button-primary" style="right:10px; margin-right:5px; position:absolute;bottom:40px;  width: 120px;" data-uk-tooltip="{pos:'left'}" title="Confirmar" data-cached-title="Confirmar" >Confirmar</a>
  
</form>

<script type="text/javascript" >

// faz o update ou inseri dados
jQuery(function() {

    jQuery("#Btn_MedEsp_002").click(function(event) {

         // mensagen de carregamento
         jQuery("#msg_loading").html(" Aguarde... ");

         //abre a tela de preload
         modal.show();

         //desabilita o envento padrao do formulario

         event.preventDefault();

         jQuery.ajax({
                        async: true,
                        url: "assets/parceiros/especialidades/Controller_especialidades.php",
                        type: "post",
                        data:"par_id=<?php echo $FRM_parceiro_id;?>&"+jQuery("#FrmEsp").serialize(),
                        success: function(resultado) {
                                                        if(jQuery.isNumeric(resultado)){
                                                            
                                                        // mensagen de carregamento
                                                        jQuery("#msg_loading").html(" Carregando ");
                                                        // recarrega a pagina
                                                        jQuery("#"+jQuery("#FrmEsp").closest('.Window').attr('id')+"").remove();

                                                        jQuery("#"+jQuery("#GridMedEspecialidades").closest('.Window').attr('id')+"").remove();

                                                        New_window('user-md','800','400','Especialidades Parceiro','assets/parceiros/especialidades/Grid_especialidades.php?par_id=<?php echo $FRM_parceiro_id;?>',true,false,'Carregando...');
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
