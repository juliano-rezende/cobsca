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
$data="AND datavencimento BETWEEN '".invertdatain($_GET['dataini'],"-")." 00:00:00' AND '".invertdatain($_GET['dataini'],"-")." 23:59:59' ";
}else{
$data="AND datavencimento BETWEEN '".invertdatain($_GET['dataini'],"-")." 00:00:00' AND '".invertdatain($_GET['datafim'],"-")." 23:59:59' ";
}

$cdconvenio=" AND cdconvenio='".$_GET['cdconvenio']."'";

$query="SELECT * FROM  ".$Prefixo_SYS."tbfaturamentos WHERE cdempresa='".$SCM_Id_empresa."' ".$status." ".$cdconvenio." ".$fpgto." ".$data."";

//query
$QueryConsultas=faturamento::find_by_sql($query);

?>
</div>
<table  class="uk-table uk-table-striped uk-table-hover" style="border:1px solid #ccc; ">
<tbody style="text-transform:uppercase;">
<?php
// laço que loopa os lançamentos dos convenios  agrupando por data
$ListCons= new ArrayIterator($QueryConsultas);

$line=1;
while($ListCons->valid()):

?>
    <tr style="line-height:20px;" >
    	<th class="uk-width uk-text-center" style="width:20px; "><?php echo $line;?></th>
        <td class="uk-width uk-text-center" style="width:100px;" >
        <?php
            echo completazeros("11",$ListCons->current()->cdfaturamento);
         ?>
        </td>
        <td class="uk-width uk-text-center" style="width:100px;" >
        <?php
		$now = new ActiveRecord\DateTime($ListCons->current()->datacadastro);
		echo $now->format('d/m/Y');
		?>
        </td>
        <td class="uk-width uk-text-center" style="width:100px" >
        <?php
		$now = new ActiveRecord\DateTime($ListCons->current()->datavencimento);
		echo $now->format('d/m/Y');
		?>
        </td>
        <td class="uk-text-left"  >
        <?php
		$paciente=pacientes::find_by_cdpaciente($ListCons->current()->cdpaciente);
		echo $paciente->titularconvenio;
		?>
        </td>
        <td class="uk-width uk-text-left" style="width:350px;" ><?php echo $ListCons->current()->historico;	?></td>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo number_format($ListCons->current()->valor,2,",",".");?></td>        
    </tr>

<?php
$line++;
$ListCons->next();
endwhile;
?>
</tbody>
</table>
