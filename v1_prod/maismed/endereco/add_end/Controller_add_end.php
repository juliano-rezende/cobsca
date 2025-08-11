<?php
require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');



/* verificamos se a variavel ação esta presente*/
$FRM_acao	=	isset( $_POST['acao']) 		? $_POST['acao']	: tool::msg_erros("O Campo acao é Obrigatorio.");



/* se apra adcionar um novo estado no banco de dados */
if($FRM_acao == 'uf'){

	$FRM_uf	= isset( $_POST['nm_uf']) 	? strtoupper($_POST['nm_uf'])	: tool::msg_erros("O Campo new_uf é Obrigatorio.");

	// array contendo todos os estados do pais
	$estadosbr = array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");

	// cria um novo estado no banco de dados
	$query 		= estados::create(array('descricao' => utf8_decode($estadosbr[$FRM_uf]),'sigla' => $FRM_uf));
	$estadolast	= estados::all();

	// laço que loopa os lançamentos dos convenios  agrupando por data
$estadoall= new ArrayIterator($estadolast);

while($estadoall->valid()):

	if($estadoall->current()->sigla == $FRM_uf){
		echo '<option selected="selected" value="'.$estadoall->current()->id.'">'.utf8_encode($estadoall->current()->descricao).'</option>';
	}else{
		echo '<option value="'.$estadoall->current()->id.'">'.utf8_encode($estadoall->current()->descricao).'</option>';
	}

$estadoall->next();
endwhile;
}

/* se apra adcionar uma nova cidade no banco de dados */
if($FRM_acao == 'cidade'){

	$FRM_uf_id	= isset( $_POST['nm_uf_cid']) 		? strtoupper($_POST['nm_uf_cid'])	: tool::msg_erros("O Campo nm_uf_cid é Obrigatorio.");
	$FRM_cidade	= isset( $_POST['nm_cidade']) 	? strtoupper($_POST['nm_cidade'])	: tool::msg_erros("O Campo new_cidade é Obrigatorio.");


	// cria um novo estado no banco de dados
	$query 		= cidades::create(array('descricao' => utf8_decode($FRM_cidade),'estados_id' => $FRM_uf_id));
	$cidadelast	= cidades::last();
	echo '<option selected="selected" value="">Selecione a Cidade</option>
		 <option value="'.$cidadelast->id.'">'.utf8_encode($cidadelast->descricao).'</option>';

}

/* se apra adcionar uma novo bairro no banco de dados */
if($FRM_acao == 'bairro'){

	$FRM_uf_id		= isset( $_POST['nm_uf_bai']) 		? strtoupper($_POST['nm_uf_bai'])	: tool::msg_erros("O Campo nm_uf_bai é Obrigatorio.");
	$FRM_cidade_id	= isset( $_POST['nm_cidade_bai']) 	? strtoupper($_POST['nm_cidade_bai'])	: tool::msg_erros("O Campo nm_cidade_bai é Obrigatorio.");
	$FRM_bairro		= isset( $_POST['nm_bairro']) 	? strtoupper($_POST['nm_bairro'])	: tool::msg_erros("O Campo nm_bairro é Obrigatorio.");


	// cria um novo estado no banco de dados
	$query = bairros::create(array('descricao' => utf8_decode($FRM_bairro),'estados_id' => $FRM_uf_id,'cidades_id' => $FRM_cidade_id));
	$bairrolast= bairros::last();
	echo '<option selected="selected" value="">Selecione o bairro</option>
		 <option value="'.$bairrolast->id.'">'.utf8_encode($bairrolast->descricao).'</option>';

}

/* se apra adcionar uma novo logradouro no banco de dados */
if($FRM_acao == 'logradouro'){

	$FRM_uf_id		= isset( $_POST['nm_uf_log']) 			? strtoupper($_POST['nm_uf_log'])			: tool::msg_erros("O Campo new_uf_log é Obrigatorio.");
	$FRM_cidade_id	= isset( $_POST['nm_cidade_log']) 		? strtoupper($_POST['nm_cidade_log'])		: tool::msg_erros("O Campo new_cidade_log é Obrigatorio.");
	$FRM_bairro_id	= isset( $_POST['nm_bairro_log']) 		? strtoupper($_POST['nm_bairro_log'])		: tool::msg_erros("O Campo nm_bairro_log é Obrigatorio.");
	$FRM_compl		= isset( $_POST['nm_compl']) 		? strtoupper($_POST['nm_compl'])		: tool::msg_erros("O Campo nm_compl é Obrigatorio.");
	$FRM_logradouro	= isset( $_POST['nm_logradouro']) 	? strtoupper($_POST['nm_logradouro'])	: tool::msg_erros("O Campo nm_logradouro é Obrigatorio.");
	$FRM_cep		= isset( $_POST['cep_new_log']) 	? tool::LimpaString(strtoupper($_POST['cep_new_log']))		: tool::msg_erros("O Campo cep_new_log é Obrigatorio.");

/* validamos o nome do logradouro para verificar se não ta vindo dados incovenientes junto*/
$opt = array("QD","LT","APTO");
if (array_key_exists($FRM_logradouro,$opt)){

echo 'erro';

}else{


// cria um novo estado no banco de dados
$query = logradouros::create(
								array('descricao'	=>utf8_decode($FRM_logradouro),
									  'complemento' =>$FRM_compl,
									  'cep'			=>$FRM_cep,
									  'estados_id'  => $FRM_uf_id,
									  'cidades_id'  => $FRM_cidade_id,
									  'bairros_id'  => $FRM_bairro_id));
$logradourolast= logradouros::last();
echo '<option selected="selected" value="">Selecione o Logradouro</option>
		 <option value="'.$logradourolast->id.'">'.$logradourolast->complemento." ".utf8_encode($logradourolast->descricao).'</option>';
}

}
?>