<?php
require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


$FRM_cep		=	isset( $_POST['cep']) 		 ? $_POST['cep']					: tool::msg_erros("O Campo cep é Obrigatorio.");
$FRM_uf			=	isset( $_POST['uf']) 		 ? strtoupper($_POST['uf'])			: tool::msg_erros("O Campo uf é Obrigatorio.");
$FRM_cidade		=	isset( $_POST['cidade']) 	 ? strtoupper($_POST['cidade'])		: tool::msg_erros("O Campo cidade  é Obrigatorio.");
$FRM_bairro		=	isset( $_POST['bairro']) 	 ? strtoupper($_POST['bairro'])		: tool::msg_erros("O Campo bairro é Obrigatorio.");
$FRM_logradouro	=	isset( $_POST['logradouro']) ? strtoupper($_POST['logradouro'])	: tool::msg_erros("O Campo logradouro é Obrigatorio.");


function removeAccentsUppercase($string)
{
    $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    $string = strtoupper($string);
    return $string;
}

$FRM_logradouro = removeAccentsUppercase($FRM_logradouro);
$FRM_bairro = removeAccentsUppercase($FRM_cidade);
$FRM_cidade = removeAccentsUppercase($FRM_logradouro);


if($FRM_uf == "") {echo tool::msg_erros("UF invalida.");}
if($FRM_cidade == "") {echo tool::msg_erros("CIDADE invalida.");}
if($FRM_bairro == "") {echo tool::msg_erros("BAIRRO invalido.");}
if($FRM_logradouro == "") {echo tool::msg_erros("LOGRADOURO invalido.");}



// separa o logradouro dao complemento
$Separa_complemento = explode(" ",$FRM_logradouro);

$FRM_complemento	= $Separa_complemento[0]; // complemento da rua
$FRM_logradouro		= ""; // define o logradouro vazio antes de monta de forma padrão

// verifica quantas quebras deu e remonta o logradouro
for($i=1; count($Separa_complemento) > $i; $i++ )
{
$FRM_logradouro 	.=" ".$Separa_complemento[$i];
}

///////////////////////////////////////////////////////////////////// verifica se já existe o estado
$Query_estado=estados::find_by_sigla($FRM_uf);

if(empty($Query_estado)){

    // array contendo todos os estados do pais
	$estadosbr = array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");

	// cria um novo estado no banco de dados
	$query 		= estados::create(array('descricao' => utf8_decode($estadosbr[$FRM_uf]),'sigla' => $FRM_uf));
	$estadolast	= estados::last();
	$estado_id	= $estadolast->id;

}else{

	$estado_id	= $Query_estado->id;

}

///////////////////////////////////////////////////////////////////// verifica se já existe a cidade
$Query_cidade=cidades::find_by_sql("SELECT id FROM cidades WHERE descricao = '".$FRM_cidade."'");

if(empty($Query_cidade)){

	// cria um novo cidade no banco de dados
	$query 		= cidades::create(array('descricao' => utf8_decode($FRM_cidade),'estados_id' => $estado_id));
	$cidadelast	= cidades::last();
	$cidade_id 	= $cidadelast->id;

}else{

	$cidade_id 	= $Query_cidade->id;

}

///////////////////////////////////////////////////////////////////// verifica se já existe o bairro
//$Query_bairro=bairros::find_by_descricao_and_cidades_id($FRM_bairro,$cidade_id);

$Query_bairro=bairros::find_by_sql("SELECT id FROM bairros WHERE descricao='$FRM_bairro' AND cidades_id='$cidade_id'");

if(empty($Query_bairro)){

	// cria um novo bairro no banco de dados
	$query = bairros::create(array('descricao' => utf8_decode($FRM_bairro),'estados_id' => $estado_id,'cidades_id' => $cidade_id));
	$bairrolast= bairros::last();
	$bairro_id 	= $bairrolast->id;


}else{

	$bairro_id 	= $Query_bairro->id;

}


///////////////////////////////////////////////////////////////////// verifica se já existe o logradouro
//$Query_logradouro=logradouros::find_by_descricao_and_cidades_id_and_bairros_id($FRM_logradouro,$cidade_id,$bairro_id);

$Query_logradouro=logradouros::find_by_sql("SELECT id FROM logradouros WHERE descricao='$FRM_logradouro' AND cidades_id='$cidade_id' AND bairros_id='$bairro_id)'");

if(empty($Query_logradouro)){
	// cria um novo logradouro no banco de dados
	$query = logradouros::create(
								array('descricao'	=>utf8_decode($FRM_logradouro),
									  'complemento' =>$FRM_complemento,
									  'cep'			=>$FRM_cep,
									  'estados_id'  => $estado_id,
									  'cidades_id'  => $cidade_id,
									  'bairros_id'  => $bairro_id));

	//$logradouro_last= logradouros::last();
	//echo $logradouro_last->id;
}

?>