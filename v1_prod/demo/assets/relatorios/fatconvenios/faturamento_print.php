<?php require_once("../../../sessao.php"); ?>
<div class="tabs-spacer" style="display:none;">
<?php
// 
require_once("../../../conexao.php");
require_once("../../../config_sys.php");		
require_once("../../../functions/funcoes.php");
require_once("../../../functions/funcoes_data.php");
$cfg->set_model_directory('../../../models/');


if($_GET['status']!=""){$status=" AND status='".$_GET['status']."'";}else{$status="";}
if($_GET['fpgto']!=""){$fpgto=" AND cdformapagamento='".$_GET['fpgto']."'";}else{$fpgto="";}

if($_GET['datafim']==""){
$inicio=$_GET['dataini'];
$fim=$_GET['dataini'];
}else{
$inicio=$_GET['dataini'];
$fim=$_GET['datafim'];
}


$data="AND datavencimento BETWEEN '".invertdatain($inicio,"-")." 00:00:00' AND '".invertdatain($fim,"-")." 23:59:59' ";
$cdconvenio=" AND cdconvenio='".$_GET['cdconvenio']."'";
		
$query="SELECT * FROM  ".$Prefixo_SYS."tbfaturamentos WHERE cdempresa='".$SCM_Id_empresa."'  ".$cdconvenio."   ".$status." ".$fpgto." ".$data."";

//query
$QueryConsultas=faturamento::find_by_sql($query);

// recupera os dados do convenio
$descconvenio=convenio::find_by_cdconvenio($_GET['cdconvenio']);

// recupera o endereço
$endereço=vwlogradouro::find_by_cdlogradouro($descconvenio->cdlogradouro);

// recupera os dados da empresa
$descempresa=empresa::find_by_cdempresa($SCM_Id_empresa);

?>
</div>

<div  id="print_rel_faturamento" style=" height:494px; overflow:auto;">

<link rel="stylesheet" href="css/print.rel.css">

<table class="uk-table-rel " style="font-weight:normal; border-bottom:2px double #ccc;" >
  <tr>
    <th colspan="5" align="center" valign="middle" style="border:0;font-size:15px; text-align:center; background-color:transparent;" >EXTRATO DE FATURAMENTO CONVÊNIO</th>
  </tr>
  <tr>
    <td width="2%" align="left" valign="middle" style="border:0;">&nbsp;</td>
    <td colspan="3" align="center" valign="middle" style="border:0; ">&nbsp;</td>
    <td width="18%" rowspan="6" align="left" valign="top" style="border:0;"><img src="imagens/logoempresas/<?php echo $descempresa->logomarca;?>.png" style=" width:150px; height:100px;"  alt=""/></td>
  </tr>
  <tr>
    <td width="2%" align="left" valign="middle" style="border:0;">&nbsp;</td>
    <td align="left" valign="middle" style="border:0;  margin-left:10px;text-transform:uppercase;"><strong>Convênio: </strong><?php echo $descconvenio->razao; ?></td>
    <td align="left" valign="middle" style="border:0;  margin-left:10px;text-transform:uppercase;">&nbsp;</td>
    <td align="left" valign="middle" style="border:0;  margin-left:10px;text-transform:uppercase;">&nbsp;</td>
  </tr>
  <tr>
    <td width="2%" rowspan="2" align="left" valign="middle" style="border:0;">&nbsp;</td>
    <td colspan="3" align="left" valign="middle" style="border:0;text-transform:uppercase; margin-left:10px;"><strong>Rua/Av:</strong> <?php echo $endereço->rua." Nº:  ".$descconvenio->num." - ".mascara_campos("?????-???",$endereço->cep); ?></td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="middle" style="border:0;text-transform:uppercase; margin-left:10px;"><strong>Bairro:</strong> <?php echo $endereço->bairro; ?> Cidade: <?php echo $endereço->bairro." - ".$endereço->cidade."/".$endereço->sigla; ?></td>
  </tr>
  <tr>
    <td width="2%" align="left" valign="middle" style="border:0;">&nbsp;</td>
    <td width="48%" colspan="2" align="left" valign="middle" style="border:0; margin-left:10px;text-transform:uppercase;"><strong>Cnpj:</strong> <?php echo mascara_campos("??.???.???/????-??",$descconvenio->cnpj); ?></td>
    <td width="32%" align="left" valign="middle" style="border:0; margin-left:10px;text-transform:uppercase;"><strong>Inscrição Estadual:</strong> <?php echo $descconvenio->ie; ?></td>
  </tr>
  <tr>
    <td width="2%" height="18" align="left" valign="middle" style="border:0;">&nbsp;</td>
    <td colspan="2" align="left" valign="middle" style="border:0; margin-left:10px;text-transform:uppercase;"><strong>Periodo:</strong> <?php echo $inicio." a ".$fim; ?></strong></td>
    <td align="left" valign="middle" style="border:0; margin-left:10px;text-transform:uppercase;"><strong>Serviços:</strong> CONSULTAS E EXAMES</td>
  </tr>
</table>


<table  class="uk-table-rel " style="border:0px solid #ccc; margin-top:3px;">

<table class="uk-table-rel">
	<thead>
		<tr>
            <th>Codigo</th>
            <th>Codigo</th>
            <th>Data Cad</th>
            <th>Data Venc</th>
            <th>Paciente/Funcionario</th>
            <th>Histórico</th>
            <th class="uk-text-center">Valor</th>        
		</tr>
	</thead>	
 	<tbody>
<?php
$t=$total=0;
// laço que loopa os lançamentos dos convenios  agrupando por data
$ListCons= new ArrayIterator($QueryConsultas);
$line=1;
while($ListCons->valid()):
?>    
<tr style="line-height:20px;" >
	<td><?php echo $line;?></td>
	<td>
	<?php
		echo $ListCons->current()->cdfaturamento;
	?>
	</td>
	<td>
	<?php
		$now = new ActiveRecord\DateTime($ListCons->current()->datacadastro);
		echo $now->format('d/m/Y');
	?>
	</td>
	<td>
	<?php
		$now = new ActiveRecord\DateTime($ListCons->current()->datavencimento);
		echo $now->format('d/m/Y');
	?>
	</td>
	<td style=" text-transform:capitalize;">
	<?php
		$paciente=pacientes::find_by_cdpaciente($ListCons->current()->cdpaciente);
		echo $paciente->titularconvenio;
	?>
	</td>
	<td style="text-transform:capitalize;"><?php echo strtolower($ListCons->current()->historico);	?></td>
	<td class="uk-text-center"><?php echo number_format($ListCons->current()->valor,2,",",".");?></td>        
</tr>
<?php
$line++;
$total+=$ListCons->current()->valor;
$ListCons->next();
endwhile;
?>
</tbody>
	<tfoot>
		<tr>
		<td colspan="6"><h3>Total => </h3></td>
		<td class="uk-text-center"><h3><?php echo number_format($total,2,",","."); ?></h3></td>
		</tr>
	</tfoot>

</table>
</div>

<div style="padding:5px; width:970px;position:absolute; text-align:right;border:0; border-top:1px solid #ccc;margin:auto;" class="uk-gradient-cinza" >
	<a href="JavaScript:void(0);" onClick="imprimir();"  class="uk-button uk-button-primary uk-button-small" ><i class="uk-icon-check" ></i> Confirmar Impressão</a>
</div>

<script language="JavaScript">
// fecha o caregamento
modal.hide();
// função para confirmar impressao
function imprimir(){
	$( "#print_rel_faturamento" ).print();
	
}
</script>