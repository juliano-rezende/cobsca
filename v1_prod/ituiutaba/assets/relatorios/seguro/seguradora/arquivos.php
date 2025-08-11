<?php 
require_once("../../../sessao.php");
?>
<link rel="stylesheet" href="css/style_table.css?<?php echo microtime(); ?>" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
  <thead class="thead">
        <tr style="height:28px;" >
        <th width="100"  class="text_center" >Situação</th>
        <th width="150"  class="text_center" >Data Criação</th>
        <th width="140"  class="text_center" >Data envio</th>
        <th  class="text_left" >Nome do Arquivo</th>
        <th width="170"  class="text_center">Ações</th>
      </tr>
    </thead>
</table>
<div id="grid_rel_arquivos" style=" height:490px; background-color:#fff; width:100%; border:0; padding:0; overflow:auto; ">
</div>
<script type="text/javascript">
$("#grid_rel_arquivos").load('relatorios/seguro/seguradora/grid_arquivos.php');
</script>