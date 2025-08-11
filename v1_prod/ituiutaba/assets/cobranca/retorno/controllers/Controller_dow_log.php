<?php
ob_start();
set_time_limit(0);

require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

$id      = isset( $_GET['id'])      ? $_GET['id'] : tool::msg_erros("O Campo id Obrigatorio.");

$retorno = retornos::find($id);

$arquivo=$retorno->path_log;

header("Content-Type: application/force-download");
header("Content-type: application/octet-stream;");
header("Content-Disposition: attachment; filename=".basename($arquivo)."");
header("Pragma: no-cache");
header("Expires: 0");
readfile("$arquivo");
flush();
exit;


?>