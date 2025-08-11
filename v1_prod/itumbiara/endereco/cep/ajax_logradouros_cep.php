<?php
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$tabela		=	$_POST['tabela'];
$cep		=	tool::LimpaString($_POST['cep']);


if($tabela =='estados'){

echo '<option value="">Selecionar</option>';
$estados=logradouros::find_by_sql("SELECT
									estados.descricao,
									estados.id
								   FROM
									logradouros
								   INNER JOIN estados ON estados.id = logradouros.estados_id
								   WHERE
									logradouros.cep = '".$cep."'
								   GROUP BY
									estados.id");


echo '<option selected="selected" value="'.$estados[0]->id.'">'.$estados[0]->descricao.'</option>';

}if($tabela =='cidades'){

$cidade=logradouros::find_by_sql("SELECT
								  cidades.descricao,
								  cidades.id
								FROM
								  logradouros
								  INNER JOIN cidades ON cidades.id = logradouros.cidades_id
								WHERE
								  logradouros.cep = '".$cep."'
								GROUP BY
								  cidades.id");


echo '<option selected="selected" value="'.$cidade[0]->id.'">'.utf8_encode($cidade[0]->descricao).'</option>';

}if($tabela =='bairros'){


$bairro=bairros::find_by_sql("SELECT
									  bairros.id,
									  bairros.descricao
									FROM
									  logradouros
									  INNER JOIN bairros ON bairros.id = logradouros.bairros_id
									WHERE
									  logradouros.cep = '".$cep."'
									GROUP BY
									  bairros.id");


echo '<option selected="selected" value="'.$bairro[0]->id.'">'.utf8_encode($bairro[0]->descricao).'</option>';

}if($tabela =='logradouros'){


$logradouro=logradouros::find_by_sql("SELECT
								  *
								FROM
								  logradouros
								WHERE
								  logradouros.cep = '".$cep."'
								");


echo '<option selected="selected" value="'.$logradouro[0]->id.'">'.utf8_encode($logradouro[0]->descricao).'</option>';

}



?>