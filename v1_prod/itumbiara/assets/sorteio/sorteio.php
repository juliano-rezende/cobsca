<?php
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
$ref = date("Ym")."01";
$Query_fat=faturamentos::find_by_sql("SELECT fat.matricula, assoc.nm_associado,assoc.cpf FROM faturamentos fat INNER JOIN associados assoc ON fat.matricula = assoc.matricula WHERE fat.status = 1 and fat.referencia = '".date("Y-m")."-01' ORDER BY rand() limit 1");
$callback["matricula"] = $Query_fat[0]->matricula;
$callback["nome"] = strtoupper($Query_fat[0]->nm_associado);
$json =  json_encode($callback);
echo $json;
