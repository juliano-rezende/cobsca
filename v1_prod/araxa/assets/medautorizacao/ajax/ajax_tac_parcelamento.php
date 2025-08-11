<?php
// blibliotecas
require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
sleep(2);


$FRM_num_parcelas   = isset( $_POST['num_parcelas'])  ? $_POST['num_parcelas']  : tool::msg_erros("O Campo num_parcelas é Obrigatorio.");
$FRM_valor_base     = isset( $_POST['vlr_base'])      ? tool::limpaMoney($_POST['vlr_base'])      : tool::msg_erros("O Campo vlr_base é Obrigatorio.");
$FRM_tac        	= isset( $_POST['tac'])      	 ? $_POST['tac']      	  : tool::msg_erros("O Campo tac é Obrigatorio.");


function jurosComposto($valor, $taxa, $parcelas) {
        $taxa = $taxa / 100;
 
        $valParcela = $valor * pow((1 + $taxa), $parcelas);
        $valParcela = number_format($valParcela / $parcelas, 2, ",", ".");
 
        return $valParcela;
}



if($FRM_num_parcelas == 1){
	echo jurosComposto($FRM_valor_base, 0, $FRM_num_parcelas); 
}else{
	echo jurosComposto($FRM_valor_base, $FRM_tac, $FRM_num_parcelas); 
}


?>