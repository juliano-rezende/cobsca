<?php
ob_start();
set_time_limit(0);

require_once"../../../sessao.php";

$arq    = isset( $_GET['arq'])     ? $_GET['arq'] : tool::msg_erros("O Campo arq Obrigatorio.");
$caminho ="arquivos/empresa_".$COB_Empresa_Id."/";//diretorio

$arquivo=$caminho.$arq;

header("Content-type: application/zip");
header("Content-Disposition: attachment; filename=".basename($arquivo)."");
header("Pragma: no-cache");
header("Expires: 0");
readfile("$arquivo");
exit;

?>