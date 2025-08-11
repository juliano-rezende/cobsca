<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');


$FRM_acao					=	isset( $_POST['acao']) 				? $_POST['acao']					: tool::msg_erros("O Campo acao é Obrigatorio.");

// tras os plano da forma de cobranca
if($FRM_acao == 0){

$FRM_forma_cobranca_id		=	isset( $_POST['forma_cobranca_id']) 	? $_POST['forma_cobranca_id']	: tool::msg_erros("O Campo forma_cobranca_id é Obrigatorio.");

$planos_encontrados=planos::find('all',array('conditions'=>array('forma_cobranca_id=?',''.$_POST['forma_cobranca_id'].'')));
$planos= new ArrayIterator($planos_encontrados);

	echo'<option value="">Selecionar</option>';

	while($planos->valid()):
	?>
	<option value="<?php  echo $planos->current()->id; ?>"><?php  echo utf8_encode($planos->current()->descricao)." - ".number_format($planos->current()->valor,2,",","."); ?></option>
	<?php
	$planos->next();
	endwhile;
// tras a descrição do plano
}if($FRM_acao == 1){

	$FRM_plano_id		=	isset( $_POST['plano_id']) 	? $_POST['plano_id']	: tool::msg_erros("O Campo plano_id é Obrigatorio.");

	$planos_encontrados=planos::find($FRM_plano_id);
	echo $planos_encontrados->obs_plano;

	}
?>