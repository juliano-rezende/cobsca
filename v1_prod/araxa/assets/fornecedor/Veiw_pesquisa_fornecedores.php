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
	    $query="SELECT SQL_CACHE * FROM clientes_fornecedores WHERE tipo='2' ORDER BY id asc limit 6";
	// retorna a pesquisa pelo id enviado
	}elseif(is_numeric ($_POST['vl'])){
	    $query="SELECT SQL_CACHE * FROM clientes_fornecedores WHERE id ='".tool::limpaString($_POST['vl'])."'"; // pesquisa pelo id do cliente
	}elseif (ctype_alpha(tool::limpaString($_POST['vl']))) {
	    $query="SELECT SQL_CACHE *
				FROM  clientes_fornecedores
				WHERE (razao_social LIKE '".tool::limpaString($_POST['vl'])."%' OR  (nm_fantasia like '".tool::limpaString($_POST['vl'])."%') OR (nm_cliente LIKE '".tool::limpaString($_POST['vl'])."%')) and tipo='2' ORDER BY id asc"; // pesquisa pela razao social ou nome fantasia
	}elseif(strstr($_POST['vl'],"/")) {
		// Cria um objeto sobre a classe
		$cpf_cnpj = new ValidaCPFCNPJ(tool::limpaString($_POST['vl']));
		// Verifica se o CNPJ é válido
		if ( !$cpf_cnpj->valida() ) {
			$erro	=1;
			$msg	="CNPJ Invalido";
		}else{
		    $query="SELECT SQL_CACHE * FROM clientes_fornecedores WHERE cnpj='".tool::limpaString($_POST['vl'])."' and tipo='2'"; // pesquisa pelo cnpj da empresa fornecedora
		}
	}elseif($_POST['vl'] == ""){
			$erro	=1;
			$msg	="Não é possivel realizar sua pesquisa dados insuficientes.";
	    }
}else{
	    $query="SELECT SQL_CACHE * FROM clientes_fornecedores WHERE tipo='2' ORDER BY id asC limit 6";
}

// corrigi bug do active record
echo'<div class="tabs-spacer" style="display:none;">';
$query_a=clientes_fornecedores::find_by_sql($query);
echo'</div>';

if(!$query_a){
	$erro	=1;
	$msg	="Fornecedor não encontrado ou não cadastrado.";
}
// se houver erro acima para aqui
if($erro == 1){
	echo '<article class="uk-comment center ">
                <header class="uk-comment-header uk-text-center">
                    <i class="uk-icon-exclamation-triangle uk-text-danger  uk-icon-small"> '.$msg.'</i>
                    <br />
                </header>
            </article>';
	return false;
}else{

$fornecedores= new ArrayIterator($query_a);
while($fornecedores->valid()):
?>
    <article class="uk-comment">
        <header class="uk-comment-header " style="padding: 5px;">
            <img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
				 <?php
					 if($fornecedores->current()->tp_pessoa == 1){echo utf8_encode($fornecedores->current()->nm_fantasia);}else{echo utf8_encode($fornecedores->current()->nm_cliente);}
				 ?>
            </h4>
			<div class="uk-coment-action">
           			<div class="uk-button-group">
           			<button class="uk-button" style="float: left;">Ações</button>
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class="uk-button" style="margin:0 -2px;"><i class="uk-icon-caret-down"></i></button>
						<div class="uk-dropdown uk-dropdown-small" >
							<ul class="uk-nav uk-nav-dropdown">
								<li><a  onclick="D_Actions_Fornecedores('edit','<?php echo $fornecedores->current()->id;?>',null);" data-uk-tooltip="{pos:'left'}"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
							</ul>
						</div>

					</div>
				</div>
    		</div>
         <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Codigo: <?php echo tool::CompletaZeros(10,$fornecedores->current()->id); ?> |
                CNPJ: <?php echo tool::MascaraCampos("??.???.???/????-??",$fornecedores->current()->cnpj); ?> |
                Data Cadastro: <?php  $datacad= new ActiveRecord\DateTime($fornecedores->current()->dt_cadastro);echo $datacad->format('d/m/Y');
				?>
            </div>
        </header>
    </article>
<?php
$fornecedores->next();
endwhile;

}
?>
<script type="text/javascript" >
function D_Actions_Fornecedores(action,val,conv){
/* abre o dependente em modo de edição */
if(action=='edit'){

	LoadContent('assets/fornecedor/Frm_fornecedor.php?for_id='+val+'','content');
	jQuery(".uk-dropdown").hide();
	//var modal = UIkit.modal("#modal01");
	//modal.show();
	}
}
</script>