<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$Query_all=associados::find_by_sql("SELECT
	CASE
	WHEN med_autorizacoes.dependente = '1' THEN (SELECT dependentes.nome
	FROM dependentes
	WHERE dependentes.id = med_autorizacoes.dependentes_id)
	ELSE (SELECT associados.nm_associado FROM associados
	WHERE associados.matricula = med_autorizacoes.matricula)
	END AS solicitante,
	CASE WHEN med_parceiros.tp_parceiro = 'J' THEN med_parceiros. nm_fantasia ELSE med_parceiros.nm_parceiro END AS parceiro,
	med_autorizacoes.dt_inclusao,
	med_autorizacoes.dt_vencimento, 
	med_autorizacoes.dt_realizacao,
	med_autorizacoes.hr_realizacao, 
	convenios.razao_social AS convenio,
	med_especialidades.descricao,
	associados.nm_associado AS titular
	FROM
	med_autorizacoes 
	LEFT JOIN
	associados ON associados.matricula = med_autorizacoes.matricula
	LEFT JOIN
	convenios ON convenios.id = med_autorizacoes.convenios_id 
	LEFT JOIN
	med_parceiros ON med_parceiros.id =	med_autorizacoes.med_parceiros_id 
	LEFT JOIN
	med_especialidades ON med_especialidades.id = med_autorizacoes.med_especialidades_id");

$List= new ArrayIterator($Query_all);


?>
</div>

<nav class="uk-navbar ">
<table  class="uk-table" >
  <thead >
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px"></th>
      <th class="uk-width uk-text-left" style="width:200px;" >Titular</th>
      <th class="uk-width uk-text-left" style="width:150px;" >Solicitante</th>
      <th class="uk-width uk-text-center uk-hidden" style="width:100px;" >Dt Inclusão</th>
      <th class="uk-width uk-text-center uk-hidden" style="width:100px;">Dt Realização</th>
      <th class="uk-width uk-text-center uk-hidden" style="width:100px;" >Dt Vencimento</th>
      <th class="uk-width uk-text-left" style="width:150px;" >Convênio</th>
      <th class="uk-width uk-text-left" style="width:150px;" >Parceiro</th>
      <th class="uk-width uk-text-left" style="width:150px;" >Especialidade</th>
      <th class="uk-text-center"  ></th>
    </tr>
    </thead>
 </table>
</nav>
<div id="Grid_Convenios" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-40;?>px; overflow-y:auto;">


<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody>
  <?php
  $linha=0;
  while($List->valid()):

  $linha++;

  $dt_inc   = new ActiveRecord\DateTime($List->current()->dt_inclusao);
  $dt_rea   = new ActiveRecord\DateTime($List->current()->dt_realizacao);
  $dt_venc  = new ActiveRecord\DateTime($List->current()->dt_vencimento);

?>
      <tr style="line-height:22px;" class="<?php echo $st; ?>">
        <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:150px;max-width: 200px;"><?php echo $List->current()->titular; ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:150px;max-width: 150px;"><?php echo $List->current()->solicitante; ?></td>
        <td class="uk-width uk-text-center uk-hidden" style="width:100px;" ><?php echo $dt_inc->format('d/m/Y') ?></td>
        <td class="uk-width uk-text-center uk-hidden" style="width:100px;" ><?php echo $dt_rea->format('d/m/Y') ?></td>
        <td class="uk-width uk-text-center uk-hidden" style="width:100px;" ><?php echo $dt_venc->format('d/m/Y') ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:150px;max-width: 150px;"><?php echo $List->current()->convenio; ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:150px;max-width: 150px;"><?php echo $List->current()->parceiro; ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:150px;max-width: 150px;"><?php echo $List->current()->descricao; ?></td>
        <td class="uk-text-center"></td>
      </tr>
  <?php
  $List->next();
  endwhile;
  ?>
  </tbody>
</table>

</div>