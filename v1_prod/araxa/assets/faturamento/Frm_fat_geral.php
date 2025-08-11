<?php require_once"../../sessao.php"; ?>
<div class="tabs-spacer" style="display:none;">
<?php
include("../../conexao.php");
$cfg->set_model_directory('../../models/');
$descricaoconvenio=convenios::find('all',array('conditions'=>array('status= ? and empresas_id= ? and tipo_convenio= ?','1',$COB_Empresa_Id,'J')));
$descricao= new ArrayIterator($descricaoconvenio);
?>
</div>
<form method="post" id="FrmFatGeral" class="uk-form" style="padding: 10px 0; margin-top: 0; padding-top: 20px;">
    <label>
        <span >Convênio</span>
        <select class="select w_300" name="ftg_convenio_id" id="ftg_convenio_id" >
        <option value="0" selected></option>
        <?php
            while($descricao->valid()):
            echo'<option value="'.$descricao->current()->id.'" >'.utf8_encode(strtoupper($descricao->current()->nm_fantasia)).'</option>';
            $descricao->next();

             endwhile;
        ?>
        </select>
    </label>
    <label>
 <!--
        <span>Nº de Parcelas</span>
        <select name="nmparcelas" id="nmparcelas" class="select">
              <option value="0" selected></option>
              <option value="1">Uma Parcela</option>
              <option value="2">Duas Parcelas</option>
              <option value="3">Tres Parcelas</option>
              <option value="4">Quatro Parcelas</option>
              <option value="5">Cinco Parcelas</option>
              <option value="6">Seis Parcelas</option>
              <option value="7">Sete Parcelas</option>
              <option value="8">Oito Parcelas</option>
              <option value="9">Nove Parcelas</option>
              <option value="10">Dez Parcelas</option>
              <option value="11">Onze Parcelas</option>
              <option value="12">Doze Parcelas</option>
        </select>
    </label>
-->
    <label>
        <span >Vencimento</span>
        <input name="ftg_venc" type="text" class=" w_100 uk-text-center  uk-text-bold"  id="ftg_venc" placeholder="00/00/0000" data-uk-datepicker="{format:'DD/MM/YYYY'}"/>
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
            for ($ref =date("Y")-1; $ref <= $intervalo+11; $ref += 1) {
            ?>
                  <option value="<?php echo $ref; ?>"><?php echo $ref; ?></option>
            <?php
            }
            ?>
        </select>
    </label>
<a  id="Btn_fat_G_1" class="uk-button uk-button-primary" style="right:10px;margin-right:5px; position:absolute;top:180px; line-height: 40px; width: 120px;" data-uk-tooltip="{pos:'left'}" title="Gerar Faturamento" data-cached-title="Gerar Faturamento" >Confirmar</a>

</form>


<script type="text/javascript">
/* mascara no campo*/
jQuery("#ftg_venc").mask("99/99/9999");


// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_fat_G_1").click(function(event) {

        // mensagen de carregamento
        jQuery("#msg_loading").html("Gerando Faturamento...");

        //abre a tela de preload
        modal.show();

        //desabilita o envento padrao do formulario
        event.preventDefault();

        jQuery.ajax({
                   async: true,
                    url: "assets/faturamento/controllers/Controller_fat_geral.php",
                    type: "post",
                    data:jQuery("#FrmFatGeral").serialize(),
                    success: function(resultado) {

                      New_window('check','900','500','Grid Faturamentos',''+resultado+'',false,true);// não envia msg pois não é load e sim html

                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }

        });
    });

});

</script>