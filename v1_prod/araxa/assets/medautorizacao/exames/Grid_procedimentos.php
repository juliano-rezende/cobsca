<div class="tabs-spacer" style="display:none;">
<?php
require_once"../../../sessao.php";
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
$FRM_autId	=	isset( $_GET['autId'])	? $_GET['autId']: 	tool::msg_erros("O Campo Obrigatorio codigo de autorização Faltando.");

?>
</div>
<?php

$dadosProcedimentos=med_proc_autorizacoes::find_by_sql("SELECT med_proc_autorizacoes.*,med_procedimentos.descricao AS descpro,med_procedimentos.vlr_custo,med_procedimentos.tx_adm, med_areas.descricao AS descarea, med_autorizacoes.status AS st_aut FROM med_proc_autorizacoes LEFT JOIN med_procedimentos ON med_proc_autorizacoes.med_procedimentos_id = med_procedimentos.id LEFT JOIN med_autorizacoes ON med_proc_autorizacoes.med_autorizacoes_id = med_autorizacoes.id LEFT JOIN med_areas ON med_autorizacoes.med_areas_id = med_areas.id WHERE med_proc_autorizacoes.med_autorizacoes_id='".$FRM_autId."'");
?>
</div>
<table class="uk-table">
	<thead class="uk-gradient-cinza">
		<tr style="line-height:20px;">
			<th class="uk-width uk-text-center" style="width:100px;" >Cod</th>
			<th class="uk-text-left" >Procedimento</th>
			<th class="uk-width uk-text-center" style="width:100px;" >Valor</th>
			<th class="uk-width uk-text-center" style="width:60px;"></th>
		</tr>
	</thead>
</table>
<div style="background-color:#fff; overflow-y:scroll; height:140px;">
	<table class="uk-table uk-table-striped uk-table-hover">
		<table  class="uk-table uk-table-striped uk-table-hover" >
			<tbody id="tbodyGridProc">
				<?php
				$total=0;
				$linha=0;
				$procedimentos= new ArrayIterator($dadosProcedimentos);
				while($procedimentos->valid()):
					?>
					<tr style="cursor:pointer; line-height: 20px;" >
						<td class="uk-width uk-text-center" style="width:100px;"><?php echo tool::CompletaZeros("8",$procedimentos->current()->id) ;?></td>
						<td class="uk-text-left">
							<?php
							echo strtoupper($procedimentos->current()->descarea.' '.$procedimentos->current()->descpro);
							?>
						</td>
						<td class="uk-width uk-text-center" style="width:100px;"><?php echo number_format(($procedimentos->current()->vlr_custo+$procedimentos->current()->tx_adm),2,',','.') ;?></td>
						<td class="uk-width uk-text-center" style="width:50px;">
							<?php
							if($procedimentos->current()->st_aut==0){
								?>
								<a href="JavaScript:void(0);" data-uk-tooltip="" title="" data-cached-title="Remover Procedimento" style="padding-top:3px;" class="uk-button uk-button-danger uk-button-mini" onClick="RemoverProcedimento('<?php echo $procedimentos->current()->id; ?>')"><i class="uk-icon-trash "></i></a>
							<?php } ?>
						</td>
					</tr>
					<?php
					$total+=($procedimentos->current()->vlr_custo+$procedimentos->current()->tx_adm);
					$linha++;
					$procedimentos->next();
				endwhile;
				?>
			</tbody>
		</table>
</div>
<table class="uk-table" style="border-top: 1px solid #ccc;">
	<thead class="uk-gradient-cinza">
		<tr style="line-height:20px;">
			<th class="uk-text-left" colspan="2" ><?php echo $linha;?> Procedimentos</th>
			<th class="uk-width uk-text-center" style="width:150px;" > Total <?php echo number_format($total,2,",","."); ?></th>
			<th class="uk-width uk-text-center" style="width:60px;"></th>
		</tr>
	</thead>
</table>