<?php 

require_once"../../sessao.php"; 
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_matricula      = isset( $_GET['matricula'])      ? $_GET['matricula']              : tool::msg_erros("O Campo matricula é Obrigatorio.");
?>
<div class="tabs-spacer" style="display:none;">
<?php

$dadoslimite = procedimentos::find_by_sql("SELECT SQL_CACHE 
  SUM(valor) as limt_utilizado, 
  (SELECT limite FROM associados WHERE matricula ='".$FRM_matricula."') as limit_autorizado 
  FROM procedimentos WHERE matricula='".$FRM_matricula."'");

?>
</div>
<nav class="uk-navbar" style="background-color: transparent; padding: 5px;">

Limite autorizado: <div class="uk-badge uk-badge-primary " style=" padding: 5px;"> R$ <?php  if(isset($dadoslimite)){echo number_format($dadoslimite[0]->limit_autorizado,2,",","."); }?></div> 
Limite utilizado: <div class="uk-badge uk-badge-warning" style=" padding: 5px;"> R$ <?php  if(isset($dadoslimite)){echo number_format($dadoslimite[0]->limt_utilizado,2,",","."); }?></div> 
Saldo a utilizar: <div class="uk-badge uk-badge-success" style=" padding: 5px;"> R$ <?php  if(isset($dadoslimite)){echo number_format(($dadoslimite[0]->limit_autorizado-$dadoslimite[0]->limt_utilizado),2,",","."); }?></div>

</nav>
  <table  class="uk-table uk-gradient-cinza" >
    <thead >
      <tr style="line-height: 30px;">
        <th class="uk-width uk-text-center" style="width:100px;" >Aut</th>
        <th class="uk-width uk-text-center" style="width:50px;" >Tipo</th>
        <th class="uk-width uk-text-center" style="width:120px;">Dt Inclusão</th>
        <th class="uk-width uk-text-center" style="width:120px;">Dt Realização</th>
        <th class="uk-text-left">Solicitante</th>
        <th class="uk-width uk-text-center" style="width:150px;">Situação</th>
      </tr>
    </thead>
  </table>
<div id="GridAutorizacoesAssoc" style="height:385px; overflow-y:scroll;">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div>
<nav class="uk-navbar" style=" padding: 5px; text-align: right;">
<button class="uk-button uk-button-primary" type="button" id="Btn_aut_0"><i class="uk-icon-plus"></i> Consultas</button>
<button class="uk-button uk-button-success" type="button" id="Btn_aut_1"><i class="uk-icon-plus"></i> Exames</button>
</label>
</nav>
<script type="text/javascript" >


  jQuery("#GridAutorizacoesAssoc").load('assets/medautorizacao/ajax/ajax_autorizacoes_matricula.php?matricula=<?php echo $FRM_matricula; ?>');


  jQuery("#Btn_aut_0").click(function(){
    New_window('list','700','360','Autorização de Consulta','assets/medautorizacao/Frm_aut_consultas.php?matricula=<?php echo $FRM_matricula; ?>',true,false,'Carregando...');
  });
  jQuery("#Btn_aut_1").click(function(){
    New_window('list','700','550','Autorização de Exames','assets/medautorizacao/Frm_aut_exames.php?matricula=<?php echo $FRM_matricula; ?>',true,false,'Carregando...');
  });
  
  /*Função para controle do relogio de tempo da sessão do usuario*/
  function reloadAut(){

  jQuery("#GridAutorizacoesAssoc").load('assets/medautorizacao/ajax/ajax_autorizacoes_matricula.php?matricula=<?php echo $FRM_matricula; ?>');
    
  }
  /* Chama a função ao carregar a tela*/

window.onload = setInterval("reloadAut()", 10000); // verifica a cada 3 minutos


</script>
