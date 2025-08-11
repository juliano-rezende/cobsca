<?php

require_once("../../sessao.php");

// corrigi bug do active record
echo'<div class="tabs-spacer" style="display:none;">';
//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
echo'</div>';

// variaveis de callback
$erro=0;
$msg="";

if(isset($_POST['acao'])){
	// retorna todos menor que 21 pois definimos 21 registros por pagina
	if(empty ($_POST['vl'])){
	    $query="SELECT SQL_CACHE  * FROM clientes_fornecedores WHERE id < '21' and tipo='2' ORDER BY id asc";
	// retorna a pesquisa pelo id enviado
	}elseif(is_numeric ($_POST['vl'])){
	    $query="SELECT SQL_CACHE  * FROM clientes_fornecedores WHERE id ='".tool::limpaString($_POST['vl'])."' and tipo='2'"; // pesquisa pelo id do cliente
	}elseif (ctype_alpha(tool::limpaString($_POST['vl']))) {
	    $query="SELECT SQL_CACHE  * FROM clientes_fornecedores WHERE nm_cliente LIKE '".tool::limpaString($_POST['vl'])."%' and tipo='2' ORDER BY id asc"; // pesquisa pelo nome do cliente
	}elseif(strstr($_POST['vl'],"-")) {
		// Cria um objeto sobre a classe
		$cpf_cnpj = new ValidaCPFCNPJ(tool::limpaString($_POST['vl']));
		// Verifica se o CPF ou CNPJ é válido
		if ( !$cpf_cnpj->valida() ) {
			$erro	=1;
			$msg	="CPF Invalido";
		}else{
		   $query="SELECT SQL_CACHE * FROM clientes_fornecedores WHERE cpf='".tool::limpaString($_POST['vl'])."' and tipo='2'"; // pesquisa pelo cpf do cliente
		}

	}elseif($_POST['vl'] == ""){
			$erro	=1;
			$msg	="Não é possivel realizar sua pesquisa dados insuficientes.";
	    }
}else{
	    $query="SELECT SQL_CACHE  * FROM clientes_fornecedores WHERE id < '21' and tipo='1' ORDER BY id asc";
}
// corrigi bug do active record
echo'<div class="tabs-spacer" style="display:none;">';
$query_a=clientes_fornecedores::find_by_sql($query);
echo'</div>';
if(!$query_a){
	$erro	=1;
	$msg	="Cliente não encontrado.";
}

// se houver erro acima para aqui
if($erro == 1){
	echo '<article class="uk-comment center ">
                <header class="uk-comment-header">
                    <i class="uk-icon-exclamation-triangle uk-text-danger  uk-icon-small"> '.$msg.'</i>
                    <br />
                </header>
            </article>';
	return false;
}else{

$clientes= new ArrayIterator($query_a);
while($clientes->valid()):
?>
    <article class="uk-comment">
        <header class="uk-comment-header ">
            <img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
				 <?php
					 echo utf8_encode($clientes->current()->nm_cliente);
				 ?>
            </h4>
			<div class="uk-coment-action">
           			<div class="uk-button-group">
           			<button class="uk-button" style="float: left;">Ações</button>
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class="uk-button" style="margin:0 -2px;"><i class="uk-icon-caret-down"></i></button>
						<div class="uk-dropdown uk-dropdown-small" >
							<ul class="uk-nav uk-nav-dropdown">
								<li><a  onclick="D_Actions_Clientes('edit','<?php echo $clientes->current()->id;?>',null);" data-uk-tooltip="{pos:'left'}"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
							</ul>
						</div>

					</div>
				</div>
    		</div>            
            <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Codigo: <?php echo tool::CompletaZeros(10,$clientes->current()->id); ?> |
                CPF: <?php echo tool::MascaraCampos("???.???.???-??",$clientes->current()->cpf); ?> |
                Data Cadastro: <?php  $datacad= new ActiveRecord\DateTime($clientes->current()->dt_cadastro);echo $datacad->format('d/m/Y');

				?>
            </div>

        </header>
    </article>

<?php

$clientes->next();
endwhile;

}
?>


<script type="text/javascript" >

function D_Actions_Clientes(action,val,conv){

/* abre o dependente em modo de edição */
if(action=='edit'){

	LoadContent('assets/cliente/Frm_cliente.php?cli_id='+val+'','content');
	jQuery(".uk-dropdown").hide();
	//var modal = UIkit.modal("#modal01");
	//modal.show();

	}
}

</script>


