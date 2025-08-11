<?php require_once"../../sessao.php"; ?>

<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');



$FRM_aut_id      = isset( $_GET['autId'])      ? $_GET['autId']              : tool::msg_erros("O Campo id da autorização é Obrigatorio.");


$dadosautorizacao = med_autorizacoes::find_by_sql("SELECT
                                                  med_procedimentos.vlr_custo, med_procedimentos.tx_adm,
                                                  Count(med_proc_autorizacoes.med_autorizacoes_id) AS total,
                                                  SUM(med_procedimentos.vlr_custo + med_procedimentos.tx_adm) AS vlr_total,
                                                  med_autorizacoes.matricula, convenios.tac_faturar, med_autorizacoes.matricula,med_autorizacoes.operador_id
                                                FROM
                                                  med_autorizacoes
                                                  LEFT JOIN med_proc_autorizacoes ON med_proc_autorizacoes.med_autorizacoes_id = med_autorizacoes.id 
                                                  LEFT JOIN med_procedimentos ON med_procedimentos.id = med_proc_autorizacoes.med_procedimentos_id
                                                  LEFT JOIN convenios ON convenios.id = med_autorizacoes.convenios_id
                                                WHERE
                                                  med_autorizacoes.id='".$FRM_aut_id."'");
 $tac_faturar = $dadosautorizacao[0]->tac_faturar;

/*VERIFICAMOS SE O DATA ATUAL É MENOR QUE DIA 18*/

if( date("d") <= 18 ){

  $dt_base = date("d/m/Y",strtotime(date("Y-m-d", strtotime(date("Y-m")."-01")) . " +1 month"));

}else{

  $dt_base = date("d/m/Y",strtotime(date("Y-m-d", strtotime(date("Y-m")."-01")) . " +2 month"));
}


$queryfatu=faturamentos::find_by_sql("SELECT SQL_CACHE id, referencia, dt_vencimento FROM faturamentos WHERE matricula = '".$dadosautorizacao[0]->matricula."' AND  status = '0' AND referencia >= '".$dt_base."' AND titulos_bancarios_id='0' LIMIT 1 ");


$listfat= new ArrayIterator($queryfatu);
while($listfat->valid()):


 $dtvenc  = new ActiveRecord\DateTime($listfat->current()->dt_vencimento);
 $dt_base = $dtvenc->format('d/m/Y');

$listfat->next();
endwhile;


?>
</div>



<div id="BigGridAut" style="height:500px; margin: 0; padding: 10px;">


  <form class="uk-form uk-form-tab" id="Frmpgto" style="padding:0; margin-top: 0; ">
    <label>
      <span>Codigo Aut</span>
      <input value="<?php echo tool::CompletaZeros("11",$FRM_aut_id); ; ?>"  name="autIdPgto" type="text" class="input_text w_100 uk-text-center " readonly id="autIdPgto"  />
    </label>

    <label>
      <span>Valor da parcela</span>
      <div class="uk-form-icon">
        <i class="uk-icon-money"></i>
        <input name="vlr_pro" type="text" class="input_text w_100 center"  id="vlr_pro" value=" <?php echo number_format($dadosautorizacao[0]->vlr_total,2,",",".");?>"  disabled="true"/>
        <input name="vlr_base" type="hidden" class="input_text w_100 center"  id="vlr_base" value=" <?php echo number_format($dadosautorizacao[0]->vlr_total,2,",",".");?>"  disabled="true"/>
      </div>
    </label>

    <label>
      <span>Faturar</span>
      <div class="uk-form-controls">
          <label >
            <input class="faturar" type="radio"  name="faturar" value="1"> Sim
            <input class="faturar" type="radio"  name="faturar" value="0"> Não
          </label>
      </div>
    </label>

    <label >
      <span>Parcelar ?</span>
      <div class="uk-form-controls">
          <label >
            <input class="parcelar" type="radio"  name="parcelar" value="1" disabled="true"> Sim
            <input class="parcelar" type="radio"  name="parcelar" value="0" disabled="true" checked="true"> Não
          </label>
      </div>
    </label>

    <label >
      <span>Nº de Parcelas</span>
      <select id="parcelas" class="select" disabled="true">
        <option value="1" selected>1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
      </select>
    </label>

    <label >
    <span>Vencimento</span>
    <div class="uk-form-icon">
      <i class="uk-icon-calendar"></i>
      <input name="dt_venc" type="text" class="input_text w_100 center" id="dt_venc"  />
    </div>
  </label>

</form>


<div style="height: 30px; width: 100%; text-align: right;" >

  <button class="uk-button uk-button-primary" type="button" id="Btn_Pgto_001">Confirmar</button>

</div>

</div>
<script type="text/javascript">


jQuery(".faturar").click(function(){


  var dt_base1 = "<?php echo $dt_base; ?>";
  var dt_base2 = "<?php echo date("d/m/Y"); ?>";

  alert("base 1 "+dt_base1);

    alert("base 2 "+dt_base2);


  if(jQuery(this).val() == 1){

     jQuery("#dt_venc").val(""+dt_base1+"");
     jQuery('.parcelar').attr("disabled", false);


  }else{

    jQuery("#dt_venc").val(""+dt_base2+"");
    jQuery('.parcelar').attr("disabled", true);
  }

});


jQuery(".parcelar").click(function(){


  var dt_base1 = "<?php echo $dt_base; ?>";
  var dt_base2 = "<?php echo date("d/m/Y"); ?>";


if(jQuery(this).val() == 1){

       UIkit.notify("Atenção o valor deste procedimento será acrescido de encargos administrativos.", {status:'danger',timeout: 4000});
       jQuery('#parcelas').attr("disabled", false);

}else{

  jQuery('#parcelas').attr("disabled", true);
}

});



jQuery("#Btn_Pgto_001").click(function(event){


  UIkit.modal.confirm("Prezado usuario sua solicitação pode levar até 5 minutos para ser processada.", function(){


      /* verificamos qual check da modalidade faturar está setada*/
      var check_faturar = "";

      jQuery('input:radio[name=faturar]').each(function() {

        //Verifica qual está selecionado
        if (jQuery(this).is(':checked'))

          check_faturar = parseInt($(this).val());
      });

      /* verificamos qual check da modalidade parcelar está setada*/

      var check_parcelar= "";

      jQuery('input:radio[name=parcelar]').each(function() {

        //Verifica qual está selecionado
        if (jQuery(this).is(':checked'))

          check_parcelar = parseInt($(this).val());
      });


      if(check_faturar >= '0'){


        var autIdPgto = jQuery("#autIdPgto").val();
        var vlr_pro   = jQuery("#vlr_pro").val();
        var faturar   = check_faturar;
        var parcelar  = check_parcelar;
        var parcelas  = jQuery("#parcelas").val();
        var dt_venc   = jQuery("#dt_venc").val();


    // mensagen de carregamento
    jQuery("#msg_loading").html(" Aguarde ... ");

    //abre a tela de preload
    modal.show();

    event.preventDefault();

    jQuery.ajax({
      async: true,
      url: "assets/medautorizacao/Controller_autorizacoes.php",
      type: "post",
      data:"action=2&autIdPgto="+autIdPgto+"&vlr_pro="+vlr_pro+"&faturar="+faturar+"&parcelar="+parcelar+"&parcelas="+parcelas+"&dt_venc="+dt_venc+"&operador_id=<?php echo $dadosautorizacao[0]->operador_id; ?>",
      success: function(resultado){

        if(jQuery.isNumeric(resultado)){

              // recarrega a pagina
              jQuery("#"+jQuery("#Frmpgto").closest('.Window').attr('id')+"").remove();
              jQuery("#"+jQuery("#FrmAutConsultas").closest('.Window').attr('id')+"").remove();
              jQuery("#"+jQuery("#Frmautexames").closest('.Window').attr('id')+"").remove();
              jQuery("#"+jQuery("#GridAutorizacoesAssoc").closest('.Window').attr('id')+"").remove();
              New_window('file-text-o','950','500','Autorizações','assets/medautorizacao/Grid_autorizacoes.php?matricula=<?php echo $dadosautorizacao[0]->matricula; ?>',true,false,'Carregando...');
              //New_window('list','700','360','Solicitar Autorização','assets/medautorizacao/Frm_aut_consultas.php?matricula=<?php echo $dadosautorizacao[0]->matricula; ?>&autId='+autIdPgto+'',true,false,'Carregando...');

            }else{
           //abre a tela de preload
           modal.hide();
           UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500});
         }
       },
       error:function (){
        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
        modal.hide();
      }
    });

  }else{
    UIkit.notify("Atenção! campo faturar incorreto.", {status:'danger',timeout: 4000}); return false;
  }

});

});


jQuery("#parcelas").change(function(event) {

  var num_parcelas = jQuery(this).val();
  var vlr_base     = jQuery("#vlr_base").val();
  var tac          = "<?php echo $tac_faturar; ?>";

  // mensagen de carregamento
  jQuery("#msg_loading").html(" Aguarde ... ");

  //abre a tela de preload
  modal.show();

// Passando tipo por parametro para a pagina ajax-marca.php
jQuery.post("assets/medautorizacao/ajax/ajax_tac_parcelamento.php",
  {num_parcelas:num_parcelas,vlr_base:vlr_base,tac:tac},
    // Carregamos o resultado acima para o campo marca
    function(valor){
       modal.hide();
      jQuery("#vlr_pro").val(valor);

    });

});

</script>

