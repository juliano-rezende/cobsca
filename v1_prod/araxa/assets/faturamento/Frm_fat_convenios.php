<?php require_once"../../sessao.php"; ?>
<div class="tabs-spacer" style="display:none;">
<?php
include("../../conexao.php");
$cfg->set_model_directory('../../models/');
$descricaoconvenio=convenios::find('all',array('conditions'=>array('status= ? and empresas_id= ? and tipo_convenio=  ?','1',$COB_Empresa_Id,'J')));
$descricao= new ArrayIterator($descricaoconvenio);


?>
</div>
<form method="post" id="FrmFatGeral" class="uk-form" style="padding: 10px 0; margin-top: 0;padding-top: 30px; ">
    <label>
        <span >Convênio</span>
        <select class="select w_300" name="convenio_id" id="convenio_id" >
            <option value="0" selected></option>
            <?php
            while($descricao->valid()):
                echo'<option value="'.$descricao->current()->id.'" >'.utf8_encode(strtoupper($descricao->current()->nm_fantasia)).'</option>';
                $descricao->next();

            endwhile;
            ?>
        </select>
    </label>
    <label id="labelsubconvenio">
        <span>Sub Convênios</span>
        <select class="select w_300" name="subconvenio" id="subconvenio" >
            <option value="0">Aguardando...</option>
        </select>
    </label> 
    <label>
        <span >Mês/Ano</span>
        <select class="select w_80" name="ftg_m" id="ftg_m" >
            <option value="0"></option>
            <option value="01">Jan</option>
            <option value="02">Fev</option>
            <option value="03">Mar</option>
            <option value="04">Abr</option>
            <option value="05">Mai</option>
            <option value="06">jun</option>
            <option value="07">Jul</option>
            <option value="08">Ago</option>
            <option value="09">Set</option>
            <option value="10">Out</option>
            <option value="11">Nov</option>
            <option value="12">Dez</option>
        </select>
        /
        <select name="ftg_y" id="ftg_y" class="select w_80">
            <option value="0"></option>
            <?php
            $intervalo=date("Y");
            for ($ref =(date("Y")-1); $ref <= $intervalo+1; $ref += 1) {
                ?>
                <option value="<?php echo $ref; ?>"><?php echo $ref; ?></option>
                <?php
            }
            ?>
        </select>
    </label>
    <a  id="Btn_fat_G_1" class="uk-button uk-button-primary" style="right:10px;margin-right:5px; position:absolute;top:200px; width: 120px;" data-uk-tooltip="{pos:'left'}" title="Confirmar" data-cached-title="Confirmar" >Confirmar</a>

</form>

<script type="text/javascript">

jQuery("#labelsubconvenio").hide();

// envia os dados para gerar os boleto ou receber
jQuery("#Btn_fat_G_1").click(function(event) {

        // mensagen de carregamento
        jQuery("#msg_loading").html("Aguarde...");

        //abre a tela de preload
        modal.show();

        var ref= jQuery("#ftg_y").val()+"-"+jQuery("#ftg_m").val()+"-01";

        New_window('list','800','500','Detalhamento','assets/faturamento/Grid_fat_convenios.php?convenio_id='+jQuery("#convenio_id").val()+'&subconvenio='+jQuery("#subconvenio").val()+'&referencia='+ref+'',true,false,'Carregando...');
});

/* função para inserir o select contendo os subconvenios */

jQuery(function() {

    jQuery("#convenio_id").change(function(event) {

        if(jQuery(this).val() == ""){return false;}

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
                        data:"action=ajaxSub&Conv_id="+jQuery(this).val()+"",
                        success: function(resultado) {
                            if(jQuery.isNumeric(resultado)){
                                jQuery("#labelsubconvenio").hide();modal.hide();
                            }else{
                                jQuery("#labelsubconvenio").show(); modal.hide(); jQuery("#subconvenio").html(resultado);   /*retorna o resultado da ação*/
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