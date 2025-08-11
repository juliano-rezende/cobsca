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
	    $query="SELECT * FROM convenios WHERE id < '21' AND empresas_id='".$COB_Empresa_Id."' ORDER BY id asc";
	// retorna a pesquisa pelo id enviado
	}elseif(is_numeric ($_POST['vl'])){
	    $query="SELECT * FROM convenios WHERE id ='".tool::limpaString($_POST['vl'])."' AND empresas_id='".$COB_Empresa_Id."'"; // pesquisa pelo id do cliente
	}elseif (ctype_alpha(tool::limpaString($_POST['vl']))) {
	    $query="SELECT *
				FROM  empresas
				WHERE (razao_social LIKE '%".tool::limpaString($_POST['vl'])."%' OR  (nm_fantasia like '%".tool::limpaString($_POST['vl'])."%'))  AND empresas_id='".$COB_Empresa_Id."'ORDER BY id asc"; // pesquisa pela razao social ou nome fantasia
	}elseif(strstr($_POST['vl'],"/")) {
		// Cria um objeto sobre a classe
		$cpf_cnpj = new ValidaCPFCNPJ(tool::limpaString($_POST['vl']));
		// Verifica se o CNPJ é válido
		if ( !$cpf_cnpj->valida() ) {
			$erro	=1;
			$msg	="CNPJ Invalido";
		}else{
		    $query="SELECT * FROM convenios WHERE cnpj='".tool::limpaString($_POST['vl'])."' AND empresas_id='".$COB_Empresa_Id."'"; // pesquisa pelo cnpj da empresa fornecedora
		}

	}elseif($_POST['vl'] == ""){
			$erro	=1;
			$msg	="Não é possivel realizar sua pesquisa dados insuficientes.";
	    }
}else{
	    $query="SELECT * FROM convenios WHERE id < '30' AND empresas_id='".$COB_Empresa_Id."' ORDER BY id asc ";
}

// corrigi bug do active record
echo'<div class="tabs-spacer" style="display:none;">';
$query_a=convenios::find_by_sql($query);
echo'</div>';
if(!$query_a){
	$erro	=1;
	$msg	="Empresa não encontrada.";
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

$convenios= new ArrayIterator($query_a);
while($convenios->valid()):
?>

    <article class="uk-comment">
        <header class="uk-comment-header ">
            <img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
				 <?php
					 echo utf8_encode($convenios->current()->nm_fantasia);
				 ?>
            </h4>
			<div class="uk-coment-action">
           			<div class="uk-button-group">
           			<button class="uk-button" style="float: left;">Ações</button>
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class="uk-button" style="margin:0 -2px;"><i class="uk-icon-caret-down"></i></button>
						<div class="uk-dropdown uk-dropdown-small" >
							<ul class="uk-nav uk-nav-dropdown">
								<li><a  onclick="D_Actions_Convenios('edit','<?php echo $convenios->current()->id;?>',null);" data-uk-tooltip="{pos:'left'}"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
							</ul>
						</div>

					</div>
				</div>
    		</div>
            <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Codigo: <?php echo tool::CompletaZeros(10,$convenios->current()->id); ?> |
                CNPJ: <?php echo tool::MascaraCampos("??.???.???/????-??",$convenios->current()->cnpj); ?> |
                Data Cadastro: <?php  $datacad= new ActiveRecord\DateTime($convenios->current()->dt_cadastro);echo $datacad->format('d/m/Y');

				?>
            </div>

        </header>
    </article>

<?php

$convenios->next();
endwhile;

}
?>


<script type="text/javascript" >

function D_Actions_Convenios(action,val,conv){

/* abre o dependente em modo de edição */
if(action=='edit'){

	LoadContent('assets/convenio/Frm_convenio.php?conv_id='+val+'','content');
	jQuery(".uk-dropdown").hide();
	//var modal = UIkit.modal("#modal01");
	//modal.show();

	}
}

</script>


