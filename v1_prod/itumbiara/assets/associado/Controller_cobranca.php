<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
require_once("../../config_ini.php");
$cfg->set_model_directory('../../models/');


$FRM_matricula			=	isset( $_POST['mat']) 		? intval($_POST['mat'])	: tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_historico			=	isset( $_POST['historico']) ? $_POST['historico']	: tool::msg_erros("O Campo historico é Obrigatorio.");
$FRM_idProtesto			=	$_POST['id_prot'];

if(empty($FRM_idProtesto)){

    $createProtesto = protestos::create(array('matricula' => $FRM_matricula, 'dt_envio_adv' => date("Y-m-d h:m:s"), 'status' => 0));
    $lastProtesto = protestos::find("last");//recupera o ultimo id
    $createDetalhes = protestos_detalhes::create(array('protesto_id' => $lastProtesto->id, 'detalhes' => $FRM_historico, 'tipo' => 0, 'usuario_id' => $COB_Usuario_Id));

    if($createProtesto && $createDetalhes ){echo $FRM_matricula;}else{echo tool::msg_erros("Erro ao inserir cobrança.");}

}else {

    $createDetalhes = protestos_detalhes::create(array('protesto_id' => $FRM_idProtesto, 'detalhes' => $FRM_historico, 'tipo' => 1,'usuario_id' => $COB_Usuario_Id));

    if($createDetalhes ){echo $FRM_idProtesto;}else{echo tool::msg_erros("Erro ao adcionar observação.");}

}


