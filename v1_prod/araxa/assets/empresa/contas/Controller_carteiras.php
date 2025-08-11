<?php
include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


echo contas_bancarias_cob::tipo_carteira($_POST['cod_banco'],$_POST['carteira']);

?>