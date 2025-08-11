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


<div id="BigGridAut" style="height:500px; margin: 0; padding: 10px;">

    
    <form class="uk-form uk-form-tab" id="FrmAutorizacao" style="padding:0; margin-top: 0; ">
      <label>
        <span>Codigo</span>
        <input value="<?php  if(isset($dadosautorizacao)){ echo tool::CompletaZeros("11",$dadosautorizacao->id); }; ?>"  name="autId" type="text" class="input_text w_100 uk-text-center " readonly id="autId"  />
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
      <span>Area Med</span>
      <select name="areaId" id="areaId" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
        <?php

        if(isset($dadosautorizacao)){

          $areas=med_areas::find('all',array('conditions'=>array('status= ?','1')));
          $descricao= new ArrayIterator($areas);
          while($descricao->valid()):

            if($descricao->current()->id == $dadosautorizacao->med_areas_id){$select='selected="selected"';}else{$select="";}

            echo'<option value="'.$descricao->current()->id.'"  '.$select.'>'.(strtoupper($descricao->current()->descricao)).'</option>';
            $descricao->next();
          endwhile;

        }else{

          $areas=med_areas::find('all',array('conditions'=>array('status= ?','1')));
          $descricao= new ArrayIterator($areas);
          echo'<option value="" selected></option>';
          while($descricao->valid()):
            echo'<option value="'.$descricao->current()->id.'" >'.(strtoupper($descricao->current()->descricao)).'</option>';
            $descricao->next();
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
      <span>Operador</span>
      <select name="opeId" id="opeId" class="select w_400" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
        <?php

        if(isset($dadosautorizacao)){

          $Qusuarios=users::find_by_sql("SELECT id, nm_usuario FROM usuarios WHERE (acessos_id ='3' OR acessos_id ='6') ");
          $listUsers= new ArrayIterator($Qusuarios);

          echo'<option value="" selected></option>';
          while($listUsers->valid()):

            if($listUsers->current()->id == $dadosautorizacao->usuarios_id){$select='selected="selected"';}else{$select="";}

            echo'<option value="'.$listUsers->current()->id.'" '.$select.'>'.(strtoupper($listUsers->current()->nm_usuario)).'</option>';
            $listUsers->next();
          endwhile;

          }else{

            $Qusuarios=users::find_by_sql("SELECT id, nm_usuario FROM usuarios WHERE (acessos_id ='3' OR acessos_id ='6') ");
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
            <input  name="dt_realizacao" id="dt_realizacao" placeholder="00/00/0000" data-uk-datepicker="{format:'DD/MM/YYYY'}" type="text" class="input_text w_150 uk-text-center" value="
        <?php 
        if(isset($dadosautorizacao)):
        $now = new ActiveRecord\DateTime($dadosautorizacao->dt_realizacao);
        echo $now->format('d/m/Y'); 
        endif; 
        ?>"/ <?php if(isset($dadosautorizacao)){echo "disabled";} ?>> Hora <select name="time" id="time" class="select w_100" <?php if(isset($dadosautorizacao)){echo "disabled";} ?>>
          <?php
          $intervalo=15;
          for($h=strtotime('7:00'); $h<strtotime('19:00'); $h =strtotime('+'.$intervalo.'minutes', $h)){

            echo'<option value="'.date('H:i', $h).'" >'.date('H:i', $h).'</option>';

          }

          ?>
        </select>
      </div> 
    </label>


    <label> 
      <span>Procedimento</span>
      <div class="uk-form-icon">
        <i class="uk-icon-tasks" ></i>
        <input onkeyup="autocompletPro()" <?php if(!isset($dadosautorizacao)){echo"disabled";}?> autocomplete="off" placeholder="Digite o Nome do Procedimento"  name="procedimento" id="procedimento" type="text" class="input_text w_400" />
        <table id="tbListProcedimentos" class="uk-table uk-table-hover" style=" border:1px solid #ccc; background-color:#FFF;width:405px; position:absolute; margin:-0px 0px; z-index:1000;"  >
        <tbody id="list_procedimento">
          <tr><td>teste</td></tr>
        </tbody> 
      </table> 
    </div>
  </label>

</form>

<div style="height: 150px; width: 99.9%; border: 1px solid #ccc; margin-bottom: 15px;" id="GridProAut"><span style="margin: 0 45%;">Carregando...</span></div>

<div style="height: 30px; width: 100%; text-align: right;" >

  <?php

  $btn1=0; 
  $btn2=0;  
  $btn3=0;  
  $btn4=0;  

  if(isset($dadosautorizacao)){

    if($dadosautorizacao->status == 0){
      $btn1=1; 
      $btn2=1;  
      $btn3=0;  
      $btn4=0;  
    }elseif($dadosautorizacao->status == 1){

      $btn1=0; 
      $btn2=0;  
      $btn3=1;  
      $btn4=0; 
    }
  }else{
    $btn1=0; 
    $btn2=0;  
    $btn3=0;  
    $btn4=1; 
  }

  ?>

  <button <?php if($btn1 == 0){echo "disabled";}?> class="uk-button uk-button-primary" type="button" id="Btn_Aut_001">Confirmar</button>
  <button <?php if($btn2 == 0){echo "disabled";}?> class="uk-button uk-button-danger"  type="button" id="Btn_Aut_002">Remover</button>
  <button <?php if($btn3 == 0){echo "disabled";}?> class="uk-button uk-button-success" type="button" id="Btn_Aut_003">Imprimir</button>
  <button <?php if($btn4 == 0){echo "disabled";}?> class="uk-button uk-button-primary" type="button" id="Btn_Aut_004">Gravar</button>

</div>

</div>
<script type="text/javascript" >

/********************************************************************************************************/
jQuery("#dt_realizacao").mask("99/99/9999");
jQuery('#tbListProcedimentos').hide();
jQuery("#GridProAut").load('assets/medautorizacao/Grid_procedimentos.php?autId='+jQuery("#autId").val()+'');

/********************************************************************************************************/



/********************************************************************************************************/
jQuery("#areaId").change(function(){

  var parceiro = jQuery("#parId").val();
  var area     = jQuery(this).val();
  
    if(parceiro == "" || area == ""){return false;}// retorna falso se for vazio

    // Exibimos no campo marca antes de concluirmos
    jQuery("select[name=espId]").html('<option value="">Carregando...</option>');

    // Passando tipo por parametro para a pagina ajax-marca.php
    jQuery.post("assets/medautorizacao/ajax/ajax_especialidades.php",
      {parId:parceiro,areaId:area},
    // Carregamos o resultado acima para o campo marca
    function(valor){
      jQuery("select[name=espId]").html(valor);
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
    New_window('credit-card','400','500','Pagamento','assets/medautorizacao/Frm_pgto.php?autId='+autId+'',true,false,'Carregando...');
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
              jQuery("#"+jQuery("#FrmAutorizacao").closest('.Window').attr('id')+"").remove();
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
    data:"action=0&mat=<?php echo $FRM_matricula; ?>&"+jQuery("#FrmAutorizacao").serialize(),
    success: function(resultado) {
        if(jQuery.isNumeric(resultado)){
            // mensagen de carregamento
            jQuery("#msg_loading").html(" Carregando ");
            // variavel com id
            var stringId  = resultado;
            var autId     = stringId.trim();
            // recarrega a pagina
            jQuery("#"+jQuery("#FrmAutorizacao").closest('.Window').attr('id')+"").remove();
            jQuery("#"+jQuery("#GridAutorizacoesAssoc").closest('.Window').attr('id')+"").remove();
            New_window('file-text-o','950','500','Autorizações','assets/medautorizacao/Grid_autorizacoes.php?matricula=<?php echo $FRM_matricula; ?>',false,false,'Carregando...');
            New_window('list','700','550','Solicitar Autorização','assets/medautorizacao/Frm_autorizacoes.php?matricula=<?php echo $FRM_matricula; ?>&autId='+autId+'',true,false,'Carregando...');

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


/********************************************************************************************************/

/* preenchimento dos procedimentos */
// autocomplet : this function will be executed every time we change the text
function autocompletPro() {

  var min_length            = 1; // min caracters to display the autocomplete
  var keywordpro            = jQuery('#procedimento').val();
  var med_parceiros_id      = jQuery('#parId').val();
  var med_especialidades_id = jQuery('#espId').val();
  var med_areas_id          = jQuery('#areaId').val();







  var gridPro= jQuery('#tbodyGridProc').html();
  var result= gridPro.trim().length;


  if(med_areas_id == 1 && result > 0 ){

    UIkit.notify("Prezado usuario para modalidade consultas não é permitido mais de um procedimento na mesma autorização!", {status:'danger',timeout: 4000});
    jQuery('#procedimento').attr("disabled", true);
    return;
  }


  if(keywordpro!=""){


    if (keywordpro.length >= min_length) {


      jQuery.ajax({
        url: 'assets/medautorizacao/ajax/ajax_refresh_procedimento.php',
        type: 'POST',
        data: {keywordpro:keywordpro,parceiros_id:med_parceiros_id,especialidades_id:med_especialidades_id},
        success:function(resultado){

            jQuery('#tbListProcedimentos').show();
            jQuery('#list_procedimento').html(resultado);
            jQuery('#list_procedimento').show();
         
        }
      });

    } else {
      jQuery('#tbListProcedimentos').hide();
      jQuery('#list_procedimento').hide();
    }

  }else{
    jQuery('#tbListProcedimentos').hide();
    jQuery('#list_procedimento').hide();
  }

}
/********************************************************************************************************/


/********************************************************************************************************/
// set_item : this function will be executed when we select an item
function set_item_pro(proId) {

// mensagen de carregamento
jQuery("#msg_loading").html(" Adcionando ");
//abre a tela de preload
modal.show();

var autId= jQuery("#autId").val();

//requisição para grava o procedimento na guia
jQuery.post("assets/medautorizacao/Controller_procedimentos.php",
  {action:0,autId:autId,proId:proId},
// Carregamos o resultado acima 
function(resultado){

  if(jQuery.isNumeric(resultado)){
      // mensagen de carregamento
      jQuery("#msg_loading").html(' Atualizando ');
      
      jQuery("#GridProAut").load('assets/medautorizacao/Grid_procedimentos.php?autId='+autId+'',function(){

                  modal.hide();// fecha o loader
                  jQuery('#list_procedimento').hide();
                  jQuery("#procedimento").val("").focus();

                });
      


    }else{
      UIkit.modal.alert(""+resultado+"");

    } 

});//fim do post

}
/********************************************************************************************************/


/********************************************************************************************************/
//pega o id da linha para remover do procedimento
function RemoverProcedimento(proId){

// mensagen de carregamento
jQuery("#msg_loading").html(" Removendo ");

//abre a tela de preload
modal.show();

var autId= jQuery("#autId").val();

//faz a requisição
jQuery.post("assets/medautorizacao/Controller_procedimentos.php",
  {action:1,autId:autId,proId:proId},
    // Carregamos o resultado acima 
    function(resultado){
      if(jQuery.isNumeric(resultado)){

        // mensagen de carregamento
        jQuery("#msg_loading").html(" Atualizando Grid ");
        jQuery("#GridProAut").load('assets/medautorizacao/Grid_procedimentos.php?autId='+jQuery("#autId").val()+'',function(){
        // mensagen de carregamento
        jQuery("#msg_loading").html(" Carregando ");
        //abre a tela de preload
        modal.hide();

      });

      }else{
        UIkit.modal.alert(""+resultado+"");
      }  
    });
}

</script>