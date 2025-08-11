<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

// variavel com o valor da acao que é para ser execultada
$acao	= isset( $_POST['acao']) 	 ? $_POST['acao'] : tool::msg_erros("O Campo acao Obrigatorio faltando.");

####################################################### se a acao for para recuperar o valor atual do plano ######################################################################
if($acao==0){

	// variaveis de parametros;
	$convenio_id	= isset( $_POST['convenio_id']) 	? $_POST['convenio_id'] 	 : tool::msg_erros("O Campo convenio_id Obrigatorio faltando.");


	$formas_encontradas=formas_cobranca::find('all',array('conditions'=>array('convenios_id=?',''.$convenio_id.'')));//

	$formas= new ArrayIterator($formas_encontradas);
	echo'<option value="">Selecionar Forma de Cobrança</option>';
	while($formas->valid()):
	?>
	<option value="<?php  echo $formas->current()->id; ?>"><?php  echo utf8_encode($formas->current()->descricao); ?></option>
	<?php
	$formas->next();
	endwhile;
######################################################  Se a acao for para recuperar o valor atual do plano #######################################################################
}elseif($acao==1){

	// variaveis de parametros;
	$forma_cobranca_id	= isset( $_POST['forma_cobranca_id']) 	? $_POST['forma_cobranca_id'] 	 : tool::msg_erros("O Campo forma_cobranca_id Obrigatorio faltando.");

	$planos_encontradas=planos::find('all',array('conditions'=>array('forma_cobranca_id=?',''.$forma_cobranca_id.'')));

	$planos= new ArrayIterator($planos_encontradas);
	echo'<option value="">Selecionar o Plano</option>';
	while($planos->valid()):
	?>
	<option value="<?php  echo $planos->current()->id; ?>"><?php  echo utf8_encode($planos->current()->descricao)." - ".number_format($planos->current()->valor,2,",","."); ?></option>
	<?php
	$planos->next();
	endwhile;
################################################################ se a acao for para calculo do novo valor #########################################################################

}elseif($acao==2){

	// variaveis de parametros;
	$plano_id	= isset( $_POST['plano_id']) 	? $_POST['plano_id'] 	 : tool::msg_erros("O Campo id do plano Obrigatorio faltando.");
	$tipo		= isset( $_POST['tiporeajuste'])? $_POST['tiporeajuste'] : tool::msg_erros("O Campo tiporeajuste Obrigatorio faltando.");

	$dadosplanos=planos::find($plano_id);

	$valoratual= $dadosplanos->valor;

	// se o reajuste for monetario
	if($tipo==0){

		$reajuste=tool::TiraVirgula($_POST['vreajuste']);
		$novovalor=number_format($valoratual+$reajuste,2,",",".");

	}else{// se o reajuste for porcentagen

		$valor = $valoratual; // valor original
		$reajuste=$_POST['vreajuste'];
		$percentual = $reajuste / 100; // 8%
		$novovalor = number_format($valor + ($percentual * $valor),2,",",".");
	}

	echo $novovalor;#
################################################################ se a acao for para calculo do novo valor ########################################################################
}elseif($acao==3){

// variaveis de parametros;
$plano_id	= isset( $_POST['plano_id']) 	? $_POST['plano_id']
											: tool::msg_erros("O Campo id do plano Obrigatorio faltando.");

	$dados_plano=planos::find($plano_id);

	echo number_format($dados_plano->valor,2,",",".");

############################################################### Faz a atualização dos planos ######################################################################################
}elseif($acao==4){


// variaveis de parametros;
$plano_id	= isset( $_POST['plano_id']) 	? $_POST['plano_id']
											: tool::msg_erros("O Campo id do plano Obrigatorio faltando.");
$tipo		= isset( $_POST['tiporeajuste'])? $_POST['tiporeajuste']
											: tool::msg_erros("O Campo valor de tiporeajuste Obrigatorio faltando.");
$vreajuste  = isset( $_POST['vreajuste'])	? tool::limpaMoney($_POST['vreajuste'])
											: tool::msg_erros("O Campo valor de vreajuste Obrigatorio faltando.");


// define a variavel de controle de erros
$query_errors 	="";

// recupera o valor atual do plano
$dadosplanos=planos::find($plano_id);
$valoratual= $dadosplanos->valor;

// se o reajuste for monetario
if($tipo==0){

		$reajuste =$vreajuste;
		$novovalor=($valoratual+$reajuste);

}else{// se o reajuste for porcentagen

		$valor      = $valoratual; // valor original
		$reajuste   =$vreajuste;
		$percentual = $reajuste / 100; // 8%
		$novovalor  = ($valor + ($percentual * $valor));
}

// faz a atualização
$update = planos::find($plano_id);
$update->valor = ''.$novovalor.'';
$update->save();


// verificamos se ocorreu tudo bem
if(!$update){
	$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao reajsutar plano  nº ".$plano_id.".</br");
}


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
if($query_errors !=""){
	echo '":"","callback":"1","msg":"'.$query_errors.'","plano_id":"'.$plano_id.'","status":"warning';
}else{
	echo '":"","callback":"0","msg":"Plano atualizado","plano_id":"'.$plano_id.'","status":"success';
}
}
?>