<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


$plano_id		= isset( $_POST['plano_id']) 	 ? $_POST['plano_id']
											     : tool::msg_erros("O Campo id do plano Obrigatorio faltando.");
// define a variavel de controle de erros
$query_errors 	="";


// recupera os dados do plano
$dadosplanos=planos::find($plano_id);


// recupera os dados de cobrança
$dadosdecobranca=dados_cobranca::all(array('conditions' => array('planos_id = ?', $plano_id)));
// joga o resultado em um array
$dados= new ArrayIterator($dadosdecobranca);


$totalalterados	= 0;// seta o valor 0 para o todal de dados

// faz um loop
while($dados->valid()):

// conecta na linha a ser alterada
$up_dados = dados_cobranca::find($dados->current()->id);
// faz o update
$up_dados->update_attributes(array('valor' => $dadosplanos->valor));

if(!$up_dados){
	$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao reajustar dados de cobrança  matricula nº ".$dados->current()->matricula.".</br");
}

//soma + 1 a cada registro
$totalalterados++;

$dados->next();
endwhile;




if($query_errors !=""){
	echo '":"","callback":"1","msg":"'.$query_errors.'","plano_id":"'.$plano_id.'","status":"warning';
}else{
	echo '":"","callback":"0","msg":"Dados atualizados","plano_id":"'.$plano_id.'","status":"success';

}

?>