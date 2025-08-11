<?php
ob_start();
set_time_limit(0);

require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

$path    = isset( $_GET['arq'])     ? $_GET['arq'] : tool::msg_erros("O Campo arq Obrigatorio.");
$id      = isset( $_GET['id'])      ? $_GET['id'] : tool::msg_erros("O Campo id Obrigatorio.");
$nm_zip  = isset( $_GET['nm'])      ? $_GET['nm'] : tool::msg_erros("O Campo id Obrigatorio.");

$Query_update=remessas::find($id);
$Query_update->update_attributes(array('dt_download' =>date("Y-m-d"),'status'=>1));


// Criando o pacote chamado "teste.zip"
$arq=explode(".",$nm_zip);

$arquivo=$path."/".$arq[0].".REM";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=".basename($arquivo)."");
header("Pragma: no-cache");
header("Expires: 0");
readfile("$arquivo");
exit;

?>