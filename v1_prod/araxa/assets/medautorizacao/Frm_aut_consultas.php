<?php require_once"../../sessao.php"; ?>

<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


$FRM_matricula      = isset( $_GET['matricula'])      ? $_GET['matricula']              : tool::msg_erros("O Campo matricula é Obrigatorio.");


if(isset($_GET['autId'])){

$FRM_aut_id      = isset( $_GET['autId'])      ? $_GET['autId']              : tool::msg_erros("O Campo id da autorização é Obrigatorio.");


$dadosautorizacao = med_autorizacoes::find($FRM_aut_id);


}


?>
</div>


<div id="BigGridAut" style="height:295px; margin: 0; padding: 10px;">

    
    <form class="uk-form uk-form-tab" id="FrmAutConsultas" style="padding:0; margin-top: 0; ">
      <label>
        <span>Codigo</span>
        <input value="<?php  if(isset($dadosautorizacao)){ echo tool::CompletaZeros("11",$dadosautorizacao->id); }; ?>"  name="autId" type="text" class="input_text w_100 uk-text-center " readonly id="autId"  /> 
        <?php if(isset($dadosautorizacao)){if($dadosautorizacao->veiw_operador == 0 && $dadosautorizacao->status == 5){echo'<div  class="uk-badge uk-badge-warning">Aguardando liberação..</div>'; } }?>
      </label>
      <label >
        <span>Solicitante</span>
        <div class="uk-form-controls">
          <label >
            <input class="slt" type="radio"  name="slt" value="0" <?php  if(isset($dadosautorizacao)){if($dadosautorizacao->dependente == "0"){echo"checked";} }?> <?php if(isset($dadosautorizacao)){echo "disabled";} ?>> Titular
            <input class="slt" type="radio"  name="slt" value="1" <?php  if(isset($dadosautorizacao)){if($dadosautorizacao->dependente == "1"){echo"checked";} }?> <?php if(isset($dadosautorizacao)){echo "disabled";} ?>> Dependente
          </label>
        </div>
      </label>

      <label>
        <span>assegurado</span>
        <select name="assoc" id="assoc" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>

         <?php

         if(isset($dadosautorizacao)){

          if($dadosautorizacao->dependente == 0){ 

            $QueryAssociados=associados::find($dadosautorizacao->matricula);

            echo'<option value="'.$dadosautorizacao->matricula.'">'.(strtoupper($QueryAssociados->nm_associado)).'</option>';

          }else{

            $QueryDependente=dependentes::find($dadosautorizacao->dependentes_id);

            echo'<option value="'.$dadosautorizacao->dependentes_id.'">'.(strtoupper($QueryDependente->nome)).'</option>';
          }
        }
        ?>

      </select>
    </label>

      <label> 
        <span>Parceiro</span>
        <select name="parId" id="parId" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
          <?php


          if(isset($dadosautorizacao)){


            $Qparceiros=med_parceiros::find_by_sql("SELECT id, CASE WHEN tp_parceiro = 'J' THEN nm_fantasia ELSE nm_parceiro END AS parceiro FROM  med_parceiros");
            $listParceiros= new ArrayIterator($Qparceiros);
            echo'<option value="" selected></option>';
            while($listParceiros->valid()):

             if($listParceiros->current()->id == $dadosautorizacao->med_parceiros_id){$select='selected="selected"';}else{$select="";}

             echo'<option value="'.$listParceiros->current()->id.'" '.$select.'>'.(strtoupper($listParceiros->current()->parceiro)).'</option>';
             $listParceiros->next();
            endwhile;


         }else{

          $Qparceiros=med_parceiros::find_by_sql("SELECT id, CASE WHEN tp_parceiro = 'J' THEN nm_fantasia ELSE nm_parceiro END AS parceiro FROM  med_parceiros");
          $listParceiros= new ArrayIterator($Qparceiros);
          echo'<option value="" selected></option>';
          while($listParceiros->valid()):
            echo'<option value="'.$listParceiros->current()->id.'" >'.(strtoupper($listParceiros->current()->parceiro)).'</option>';
            $listParceiros->next();
          endwhile;
        }
        ?>
      </select>
    </label>

    <label> 
      <span>Especialidade</span>
      <select name="espId" id="espId" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
        <?php

        
        if(isset($dadosautorizacao)){


          $query_c=med_especialidades::find_by_sql("
            SELECT
            id,descricao
            FROM
            med_especialidades
            WHERE
            med_parceiros_id ='".$dadosautorizacao->med_parceiros_id."' AND med_areas_id ='".$dadosautorizacao->med_areas_id."' ");

          $especparceiro= new ArrayIterator($query_c);
          
          while($especparceiro->valid()):

            if($especparceiro->current()->id == $dadosautorizacao->med_especialidades_id){$select='selected="selected"';}else{$select="";}

            echo'<option value="'.$especparceiro->current()->id.'" '.$select.'>'.strtoupper($especparceiro->current()->descricao).'</option>';
            
            $especparceiro->next();

          endwhile;

        }

        ?>
      </select>
    </label>

    <label> 
      <span>Profissional</span>
      <select name="proId" id="proId" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
        <?php

        
        if(isset($dadosautorizacao)){


          $query_c=med_procedimentos::find_by_sql("
            SELECT
            id,descricao
            FROM
            med_procedimentos
            WHERE
            med_parceiros_id ='".$dadosautorizacao->med_parceiros_id."' AND med_especialidades_id ='".$dadosautorizacao->med_especialidades_id."' ");

          $Profissional= new ArrayIterator($query_c);
          
          while($Profissional->valid()):

            if($Profissional->current()->id == $dadosautorizacao->med_especialidades_id){$select='selected="selected"';}else{$select="";}

            echo'<option value="'.$Profissional->current()->id.'" '.$select.'>'.strtoupper($Profissional->current()->descricao).'</option>';
            
            $Profissional->next();

          endwhile;

        }

        ?>
      </select>
    </label>
    <label> 
      <span>Operador</span>
      <select name="opeId" id="opeId" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
        <?php

        if(isset($dadosautorizacao)){

          $Qusuarios=users::find_by_sql("SELECT id, nm_usuario FROM usuarios WHERE (acessos_id ='3' OR acessos_id='5' OR acessos_id='5') AND status='1' ");
          $listUsers= new ArrayIterator($Qusuarios);

          while($listUsers->valid()):

            if($listUsers->current()->id == $dadosautorizacao->usuarios_id){$select='selected="selected"';}else{$select="";}

            echo'<option value="'.$listUsers->current()->id.'" '.$select.'>'.(strtoupper($listUsers->current()->nm_usuario)).'</option>';
            
            $listUsers->next();
          endwhile;

          }else{

            $Qusuarios=users::find_by_sql("SELECT id, nm_usuario FROM usuarios WHERE (acessos_id ='3' OR acessos_id='5' OR acessos_id='6') AND status='1' ");
            $listUsers= new ArrayIterator($Qusuarios);

            echo'<option value="" selected></option>';
            while($listUsers->valid()):
              echo'<option value="'.$listUsers->current()->id.'" >'.(strtoupper($listUsers->current()->nm_usuario)).'</option>';
              $listUsers->next();
            endwhile;


          }

          ?>
        </select>
    </label>


    <label> 
        <span>Data Agend</span> 
        <div class="uk-form-icon">
            <i class="uk-icon-calendar-o"></i>
            <input name="dt_realizacao" id="dt_realizacao" autocomplete="off" type="text" class="uk-text-center w_100 " placeholder="00/00/0000" <?php if(isset($dadosautorizacao)){echo "disabled";} ?> value="<?php 
        if(isset($dadosautorizacao)):
        $now = new ActiveRecord\DateTime($dadosautorizacao->dt_realizacao);
        echo $now->format('d/m/Y'); 
        endif; 
        ?>"  data-uk-datepicker="{format:'DD/MM/YYYY'}"/> Hora <select name="hr_realizacao" id="hr_realizacao" class="select w_100" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
          <?php



          $intervalo=15;
          for($h=strtotime('07:00:00'); $h<strtotime('19:00:00'); $h =strtotime('+'.$intervalo.'minutes', $h)){

           
            if( (date('H:i:s', $h)) == $dadosautorizacao->hr_realizacao ){ $select='selected="selected"'; }else{ $select=""; }



            echo'<option value="'.date('H:i:s', $h).'" >'.date('H:i:s', $h).'</option>';

          }

          ?>
        </select>
      </div> 
    </label>




</form>

<div style="height: 30px; width: 98%; text-align: right; " >

  <?php

  $btn1=0; 
  $btn2=0;  
  $btn3=0;$autorizado=0;  
  $btn4=0;


  if(isset($dadosautorizacao)){

    if($dadosautorizacao->status == 0){
      $btn1=1; 
      $btn2=1;  
      $btn3=0;$autorizado=0;  
      $btn4=0; 
    }elseif($dadosautorizacao->status == 1){

      $btn1=0; 
      $btn2=0;  
      $btn3=1;  
      $btn4=0;
      if($dadosautorizacao->veiw_operador == 1){$autorizado = 1; }
    }

  }else{
    $btn1=0; 
    $btn2=0;  
    $btn3=0;$autorizado=0;  
    $btn4=1; 
  }

  ?>

</div>

</div>
  <nav class="uk-navbar" style="padding:8px 18px 8px 0px; text-align: right; border-top: 1px solid #ccc;">
  <button <?php if($btn1 == 0){echo "disabled";}?> class="uk-button uk-button-primary" type="button" id="Btn_Aut_001">Prosseguir</button>
  <button <?php if($btn2 == 0){echo "disabled";}?> class="uk-button uk-button-danger"  type="button" id="Btn_Aut_002">Remover</button>
  <button <?php if($btn3 == 0 or $autorizado == 0){echo "disabled";}?> class="uk-button uk-button-success" type="button" id="Btn_Aut_003">Imprimir</button>
  <button <?php if($btn4 == 0){echo "disabled";}?> class="uk-button uk-button-primary" type="button" id="Btn_Aut_004">Gravar</button>
</nav>
<script type="text/javascript" >

  jQuery("#parId").change(function(){

    // Exibimos no campo marca antes de concluirmos
    jQuery("select[name=espId]").html('<option value="">Carregando...</option>');

    // Passando tipo por parametro para a pagina ajax-marca.php
    jQuery.post("assets/medautorizacao/ajax/ajax_especialidades.php",
      {parId:jQuery(this).val(),areaId:1},
    // Carregamos o resultado acima para o campo marca
    function(valor){
      jQuery("select[name=espId]").html(valor);
    });

  });


  jQuery("#espId").change(function(){

    // Exibimos no campo marca antes de concluirmos
    jQuery("select[name=proId]").html('<option value="">Carregando...</option>');

    // Passando tipo por parametro para a pagina ajax-marca.php
    jQuery.post("assets/medautorizacao/ajax/ajax_list_consultas.php",
      {parId:jQuery('#parId').val(),espId:jQuery(this).val()},
    // Carregamos o resultado acima para o campo marca
    function(valor){
      jQuery("select[name=proId]").html(valor);
    });

  });




jQuery(".slt").click(function(){

  var dep     = jQuery(this).val();
  var mat     = jQuery("#matricula").val();
  

    // Exibimos no campo marca antes de concluirmos
    jQuery("select[name=assoc]").html('<option value="">Carregando...</option>');

    // Passando tipo por parametro para a pagina ajax-marca.php
    jQuery.post("assets/medautorizacao/ajax/ajax_solicitantes.php",
      {dep:dep,mat:mat},
    // Carregamos o resultado acima para o campo marca
    function(valor){
      jQuery("select[name=assoc]").html(valor);
    });

  });
/********************************************************************************************************/


/********************************************************************************************************/
// confirma a autorização
jQuery(function() {
  jQuery("#Btn_Aut_001").click(function(event) {
    var autId = jQuery("#autId").val();
    New_window('credit-card','400','280','Pagamento','assets/medautorizacao/Frm_pgto.php?autId='+autId+'',true,false,'Carregando...');
  });
});
/********************************************************************************************************/


/********************************************************************************************************/
// remove os dados no banco de dados
jQuery(function() {

  jQuery("#Btn_Aut_002").click(function(event) {

  // mensagen de carregamento
  jQuery("#msg_loading").html(" Aguarde ... ");

  //abre a tela de preload
  modal.show();

  //desabilita o envento padrao do formulario
  event.preventDefault();

    jQuery.ajax({
    async: true,
    url: "assets/medautorizacao/Controller_autorizacoes.php",
    type: "post",
    data:"action=1&autId="+jQuery('#autId').val()+"",
    success: function(resultado) {
          if(jQuery.isNumeric(resultado)){
              // mensagen de carregamento
              jQuery("#msg_loading").html(" Carregando ");
              // variavel com id
              var matricula=resultado;
              // recarrega a pagina
              jQuery("#"+jQuery("#FrmAutConsultas").closest('.Window').attr('id')+"").remove();
              jQuery("#"+jQuery("#GridAutorizacoesAssoc").closest('.Window').attr('id')+"").remove();
              New_window('file-text-o','950','500','Autorizações','assets/medautorizacao/Grid_autorizacoes.php?matricula=<?php echo $FRM_matricula; ?>',true,false,'Carregando...');
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

  });


});
/********************************************************************************************************/


// grava os dados no banco de dados
jQuery(function() {

  jQuery("#Btn_Aut_003").click(function(event) {
//abre a pagina de impressao se tudo ocorrer bem
New_window('list','950','550','Impressão','assets/medautorizacao/print_aut_consulta.php?autId=<?php  if(isset($dadosautorizacao)){ echo tool::CompletaZeros("11",$dadosautorizacao->id); }; ?>',true,false,'Carregando...');
});

});

/********************************************************************************************************/

// grava os dados no banco de dados
jQuery(function() {

  jQuery("#Btn_Aut_004").click(function(event) {

  // mensagen de carregamento
  jQuery("#msg_loading").html(" Aguarde ... ");

  //abre a tela de preload
  modal.show();

  //desabilita o envento padrao do formulario
  event.preventDefault();

    jQuery.ajax({
    async: true,
    url: "assets/medautorizacao/Controller_autorizacoes.php",
    type: "post",
    data:"action=0&mat=<?php echo $FRM_matricula; ?>&"+jQuery("#FrmAutConsultas").serialize(),
    success: function(resultado) {
        if(jQuery.isNumeric(resultado)){
            // mensagen de carregamento
            jQuery("#msg_loading").html(" Carregando ");
            // variavel com id
            var stringId  = resultado;
            var autId     = stringId.trim();
            // recarrega a pagina
            jQuery("#"+jQuery("#FrmAutConsultas").closest('.Window').attr('id')+"").remove();
            jQuery("#"+jQuery("#GridAutorizacoesAssoc").closest('.Window').attr('id')+"").remove();
            New_window('file-text-o','950','500','Autorizações','assets/medautorizacao/Grid_autorizacoes.php?matricula=<?php echo $FRM_matricula; ?>',false,false,'Carregando...');
            New_window('list','700','360','Solicitar Autorização','assets/medautorizacao/Frm_aut_consultas.php?matricula=<?php echo $FRM_matricula; ?>&autId='+autId+'',true,false,'Carregando...');

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
  });


});


</script>