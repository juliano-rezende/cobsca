<?php require_once("../../sessao.php"); ?>
<div class="tabs-spacer" style="display:none;">
<?php
//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
?>
</div>
<?php
$erro=0;
$msg="";

if(isset($_POST['acao'])){

// codigo
if(empty ($_POST['vl'])){

    $vl_campo   = "";
    $where      = "id <'21'";
	$erro=0;

}if(is_numeric ($_POST['vl'])){// pesquisa pelo codigo

    $vl_campo   = tool::limpaString($_POST['vl']);
    $where      = "id ='".$vl_campo."'";

}if (ctype_alpha(tool::limpaString($_POST['vl']))) {// pesquisa pelo nome

    $where      = "nm_associado LIKE '".$_POST['vl']."%'";

}if(strstr($_POST['vl'],"-")) {// pesquisa pelo cpf

		// Cria um objeto sobre a classe
	$cpf_cnpj = new ValidaCPFCNPJ(tool::limpaString($_POST['vl']));

	// Verifica se o CPF ou CNPJ é válido
	if ( !$cpf_cnpj->valida() ) {
		$erro	=1;
		$msg	="CPF Invalido";
		$where	= "";
	}else{

	$vl_campo   = tool::limpaString($_POST['vl']);
    $where      = "cpf ='".$vl_campo."'";
	}
}if($_POST['vl'] == ""){
        $erro	=	1;
		$where	= "";
		$msg	="Não é possivel realizar sua pesquisa dados insuficientes.";

    }

$query="SELECT * FROM vendedores WHERE ".$where." ORDER BY id asc";
}else{

$query="SELECT * FROM vendedores ORDER BY id ASC LIMIT 21";

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
}
?><div class="tabs-spacer" style="display:none;"><?php

$query_a=vendedores::find_by_sql($query);

?></div><?php
$vendedores= new ArrayIterator($query_a);
$totaldelinhas=count($vendedores);

$i=1;
while($vendedores->valid()):
$codigo=tool::CompletaZeros("3",$vendedores->current()->id);


if($vendedores->current()->status == 0){
					$st		="Cancelado";
					$class	=" uk-badge-danger";
                }else{
					$st		="Ativo";
					$class	="";
                    }
?>

    <article class="uk-comment">
        <header class="uk-comment-header ">
            <img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
				 <?php
					 echo utf8_encode($vendedores->current()->nm_vendedor);
				 ?>
            </h4>
			<div class="uk-coment-action">
           			<div class="uk-button-group">
           			<button class="uk-button" style="float: left;">Ações</button>
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class="uk-button" style="margin:0 -2px;"><i class="uk-icon-caret-down"></i></button>
						<div class="uk-dropdown uk-dropdown-small" >
							<ul class="uk-nav uk-nav-dropdown">
								<li><a  onclick="D_Actions_Vendedores('edit','<?php echo $codigo;?>',null);" data-uk-tooltip="{pos:'left'}"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
							</ul>
						</div>

					</div>
				</div>
    		</div>
            <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Codigo: <?php echo tool::CompletaZeros(3,$vendedores->current()->id); ?> |
                CPF: <?php echo tool::MascaraCampos("###.###.###-##",$vendedores->current()->cpf); ?> |
                Data Cadastro: <?php  $datacad= new ActiveRecord\DateTime($vendedores->current()->dt_cadastro);echo $datacad->format('d/m/Y');

				?>
            </div>

        </header>
    </article>

    <?php
    $i++;
    $vendedores->next();
    endwhile;

    ?>

<script type="text/javascript" >

function D_Actions_Vendedores(action,val,conv){

/* abre o dependente em modo de edição */
if(action=='edit'){

	LoadContent('assets/vendedor/Frm_vendedor.php?vendor_id='+val+'','content');
	jQuery(".uk-dropdown").hide();
	//var modal = UIkit.modal("#modal01");
	//modal.show();

	}

}

</script>
