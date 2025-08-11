<?php 
require_once("../../../sessao.php");
?>
<link rel="stylesheet" href="css/style_table.css?<?php echo microtime(); ?>" />
<title>Grid Arquivos</title>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../functions/funcoes.php");
require_once("../../../functions/funcoes_data.php");
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$query=arq_seguradora::find_by_sql("SELECT * FROM tbarq_seguradora WHERE cdempresa='".$SCA_Id_empresa."'");
$list= new ArrayIterator($query);
	
?>
</div>
<table width="100%"   cellpadding="0" cellspacing="0" >
<tbody class="tbody" >
<?php
while($list->valid()): 
	
?>
	<tr class="tr" style="cursor:default; height:26px;">
		<td width="100" class="text_center" ><?php 
		    if($list->current()->dataenvio==''){echo'A Transmitir';}else{echo '<i class=" icon-thumbs-up-alt" style="color: #03C" ></i> Transmitido';}
		 ?>
      </td>
		<td width="130" class="text_center" ><?php 
		$now = new ActiveRecord\DateTime($list->current()->datacriacao);
		echo $now->format('d/m/Y h:m:s'); 
		 ?></td>
		<td width="150" class="text_center" >
		<?php 
		  $dte = new ActiveRecord\DateTime($list->current()->dataenvio);   if($list->current()->dataenvio==''){echo'00/00/0000';}else{echo $dte->format('d/m/Y h:m:s');}
		  ?>
		
       </td>
		<td class="text_left" >
		<?php 
		echo $list->current()->arquivo;
		?></td>
      <td width="150" align="center" >
      <?php
	  if($list->current()->dataenvio==''){
	  ?>
      <a href="JavaScript:void(0);" id="linha_<?php echo $list->current()->cdregistro ; ?>" title="Enviar Arquivo Seguradora" onClick="Transmitir('<?php echo $list->current()->arquivo; ?>','<?php echo $list->current()->cdregistro ; ?>')" style=" font-size:12px;" >
      <i class="icon-logout" ></i> Enviar
      </a>
      <?php }?>
      </td> 
	</tr>                
	<?php
    $list->next();
    endwhile; 
    ?>
  </tbody>
</table>
<script type="text/javascript">
function Transmitir(arquivo,cdregistro){
	
//loader
$("#linha_"+cdregistro+"").html('<i class="animate-spin icon-spin3" ></i>Aguarde ...');

// envia o formulario para tratar e inserir no banco de dados
 $.post("relatorios/seguro/seguradora/transmitir_seguradora.php",
		  {arquivo:arquivo},
           // Carregamos o resultado acima 
			function(resultado){
								alert(""+resultado+"");
								$("#linha_"+cdregistro+"").html('<i class="icon-logout" ></i> Enviar');
								$("#grid_rel_arquivos").load('relatorios/seguro/seguradora/grid_arquivos.php');
							  });		
	}
</script>
