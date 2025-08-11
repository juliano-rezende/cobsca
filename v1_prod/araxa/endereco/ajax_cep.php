<?php
require_once("../conexao.php");
$cfg->set_model_directory('../models/');
$logradouros_encontrados=logradouros::find($_POST['logradouro_id']);
echo tool::MascaraCampos("?????-???",$logradouros_encontrados->cep);
?>