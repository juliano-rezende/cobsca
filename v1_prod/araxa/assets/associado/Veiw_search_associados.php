<?php
require_once("../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
//classe de validação de cnpj e cpf
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
?>
</div>
<?php

/* neste caso se a pesquisa estiver sendo feita pela empresa conveniada a consulta retornara apenas associados do convenio */
if($COB_Acesso_Id == 5){

$FRM_Convenios_id = " AND convenios_id='".$COB_Convenio_Id."'";

}else{

$FRM_Convenios_id="";

}

$erro=0;
$msg="";

if(isset($_POST['acao'])){

	$Vlr = trim($_POST['vl']);

	if(empty ($Vlr)){// matricula

	    $vl_campo   = "";
	    $where      = " AND matricula <'21'".$FRM_Convenios_id;
		$erro=0;

	}elseif(is_numeric($Vlr)){

	    $vl_campo   = tool::limpaString($Vlr);
	    $where      = " AND matricula ='".$vl_campo."'".$FRM_Convenios_id;

	}elseif(ctype_alpha(tool::limpaString($Vlr))){// pesquisa pelo nome

	    $where      = " AND nm_associado LIKE '".$Vlr."%'".$FRM_Convenios_id;

	}elseif(strstr($Vlr,"-")){// pesquisa pelo cpf

		if(is_numeric(tool::limpaString($Vlr))){

			// Cria um objeto sobre a classe
			$cpf_cnpj = new ValidaCPFCNPJ(tool::limpaString($Vlr));

			// Verifica se o CPF ou CNPJ é válido
			if ( !$cpf_cnpj->valida() ) {
				$erro	=1;
				$msg	="CPF Invalido";
				$where	= "";
			}else{

				$vl_campo   = tool::limpaString($Vlr);
		    	$where      = " AND cpf ='".$vl_campo."'".$FRM_Convenios_id;
			}
		}else{
			$erro	= 1;
			$where	= "";
			$msg	= "Nenhum resultado encontrado para sua pesquisa.";
		}

	}elseif(strstr($Vlr,"/")){// data de nascimento

		if(strlen($Vlr) < 10){
			$erro	=1;
			$msg	="A data deve ser no formato 00/00/0000";
			$where	= "";
		}else{

			$vl_campo   = tool::limpaString($Vlr);
			$where      = " AND dt_nascimento ='".tool::InvertDateTime($vl_campo,0)."'".$FRM_Convenios_id;
		}

	}elseif($_POST['vl'] == ""){
	        $erro	=	1;
			$where	= "";
			$msg	="Não é possivel realizar sua pesquisa dados insuficientes.";
	}else{
		$erro	= 1;
		$where	= "";
		$msg	= "Nenhum resultado encontrado para sua pesquisa.";
	}

	$query="SELECT SQL_CACHE
			status,
			cpf,
			nm_associado,
			convenios_id,
			dt_nascimento,
			matricula,
			dt_cadastro,
			contrato,
			(SELECT nm_usuario FROM usuarios WHERE id =  associados.usuarios_id) as nm_usuario,
			(SELECT nm_fantasia FROM convenios WHERE id =  associados.convenios_id) as nm_convenio,
			(SELECT nm_fantasia FROM sub_convenios WHERE id =  associados.sub_convenios_id) as nm_subconvenio,
			(SELECT count(id) FROM dependentes WHERE status = '1' and matricula  = associados.matricula) as total_dep,
			(SELECT count(id) FROM faturamentos  WHERE matricula = associados.matricula) as total_fat,
			(SELECT count(id) FROM faturamentos  WHERE status = '0' and matricula = associados.matricula and referencia <= '".date("Y-m")."-01') as total_fat_venc,
			(SELECT count(id) FROM faturamentos  WHERE status = '0' and matricula = associados.matricula) as total_fat_ab,
			(SELECT count(id) FROM faturamentos  WHERE status = '1' and matricula = associados.matricula) as total_fat_pgto,
			(SELECT count(id) FROM faturamentos  WHERE status = '2' and matricula = associados.matricula) as total_fat_cancel
			FROM associados WHERE empresas_id='".$COB_Empresa_Id."' ".$where." ORDER BY matricula asc";
}else{

$query="SELECT
		status,
		cpf,
		nm_associado,
		convenios_id,
		dt_nascimento,
		matricula,
		dt_cadastro,
		contrato,
		(SELECT nm_usuario FROM usuarios WHERE id =  associados.usuarios_id) as nm_usuario,
		(SELECT nm_fantasia FROM convenios WHERE id =  associados.convenios_id) as nm_convenio,
		(SELECT nm_fantasia FROM sub_convenios WHERE id =  associados.sub_convenios_id) as nm_subconvenio,
		(SELECT count(id) FROM dependentes WHERE status = '1' and matricula  = associados.matricula) as total_dep,
		(SELECT count(id) FROM faturamentos  WHERE matricula = associados.matricula) as total_fat,
		(SELECT count(id) FROM faturamentos  WHERE status = '0' and matricula = associados.matricula and referencia <= '".date("Y-m")."-01') as total_fat_venc,
		(SELECT count(id) FROM faturamentos  WHERE status = '0' and matricula = associados.matricula) as total_fat_ab,
		(SELECT count(id) FROM faturamentos  WHERE status = '1' and matricula = associados.matricula) as total_fat_pgto,
		(SELECT count(id) FROM faturamentos  WHERE status = '2' and matricula = associados.matricula) as total_fat_cancel
		FROM associados WHERE empresas_id='".$COB_Empresa_Id."' ".$FRM_Convenios_id." ORDER BY matricula ASC LIMIT 5";
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

$query_a=associados::find_by_sql($query);

?></div><?php
$associados= new ArrayIterator($query_a);
$totaldelinhas=count($associados);
$i=1;

while($associados->valid()):

$matricula=tool::CompletaZeros("10",$associados->current()->matricula);
$convenio=tool::CompletaZeros("2",$associados->current()->convenios_id);

if($associados->current()->status == 0){
					$st		="Cancelado";
					$class	=" uk-badge-danger";
                }else{
					$st		="Ativo";
					$class	="";
                    }

/* definimos a avaliação do cliente */
/* sem parcelas em atrazo 5 estrelas */
/* 1 parcela em atrazo 4 estrelas */
/* 2 parcelas em atrazo 3 estrelas */
/* 3 parcelas em atrazo 2 estrelas */
/* 4 parcelas em atrazo 1 estrelas */
/* acima 4 parcelas em atrazo 0 estrelas */


switch ($associados->current()->total_fat_venc) {
    case 0:
        $star = "five";
        break;
    case 1:
        $star = "one";
        break;
    case 2:
        $star = "two";
        break;
    case 3:
        $star = "tree";
        break;
    case 4:
        $star = "for";
        break;
    default:
       $star = "zero";
}


/* validação do contrato do associado */
$datacad= new ActiveRecord\DateTime($associados->current()->dt_cadastro);

$data_diff1 = $datacad->format("Y-m-d");
$data_diff2 = date("Y-m-d");


$date = new DateTime($data_diff1); // Data de Nascimento
$idade_acompanhamento = $date->diff(new DateTime($data_diff2)); // Data do Acompanhamento
$data_Year = $idade_acompanhamento->format('%Y')*12;
$data_month = $idade_acompanhamento->format('%m');
$total_meses = $data_Year+$data_month;



?>
    <article class="uk-comment">
        <header class="uk-comment-header " style="padding: 5px;">
            <img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
				 <?php
					 echo ($associados->current()->nm_associado." - [ ".$associados->current()->nm_convenio." ][ ".$associados->current()->nm_subconvenio." ]");

				if($associados->current()->status == 1){ ?>

                <div class="uk-badge uk-width uk-star-<?php echo $star; ?>" style="width: 99px; min-width: 99px; background-color: transparent;">&nbsp;</div>

                <?php } ?>
            </h4>
			<div class="uk-coment-action">
           			<div class="uk-button-group">
           			<button class="uk-button" style="float: left;">Ações</button>
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class="uk-button" style="margin:0 -2px;"><i class="uk-icon-caret-down"></i></button>
						<div class="uk-dropdown uk-dropdown-small" >
							<ul class="uk-nav uk-nav-dropdown">
								<li><a  href="JavaScript:void(0);" onclick="D_Actions_Assoc('edit','<?php echo $matricula;?>',null);"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
								<li><a  href="JavaScript:void(0);" onclick="D_Actions_Assoc('dep','<?php echo $matricula;?>',null);"><i class="uk-icon-users"></i> Dependentes</a></li>
                                <li><a  href="JavaScript:void(0);" onclick="D_Actions_Assoc('fat','<?php echo $matricula;?>','<?php echo $convenio;?>');"><i class="uk-icon-list"></i> Faturamento</a></li>
							</ul>
						</div>

					</div>
				</div>
    		</div>

            <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Matricula: <?php echo tool::CompletaZeros(10,$associados->current()->matricula); ?> |
                CPF: <?php echo tool::MascaraCampos("###.###.###-##",$associados->current()->cpf); ?> |
                Data Nascimento: <?php  $datanasc = new ActiveRecord\DateTime($associados->current()->dt_nascimento);echo $datanasc->format('d/m/Y');?> |
                Data Cadastro: <?php  $datacad= new ActiveRecord\DateTime($associados->current()->dt_cadastro);echo $datacad->format('d/m/Y'); ?> |
                Usúario: <?php  echo ucfirst($associados->current()->nm_usuario); ?>
            </div>
            <div class="uk-comment-meta uk-text-bold ">

                Status:
                <div class="uk-badge <?php echo  $class; ?>"><?php echo  $st; ?></div>
                Dependentes:
                <div class="uk-badge uk-badge-success"> <?php echo $associados->current()->total_dep; ?></div>
                Faturamento:
                <div class="uk-badge uk-badge-success">Pagas <?php echo $associados->current()->total_fat_pgto; ?></div>
                <div class="uk-badge uk-badge-warning">Em aberto <?php echo $associados->current()->total_fat_ab; ?></div>
                <div class="uk-badge uk-badge-danger">Canceladas <?php echo $associados->current()->total_fat_cancel; ?></div>
                <div class="uk-badge uk-badge-primary">Total <?php echo $associados->current()->total_fat; ?></div>
				<?php
					if($associados->current()->contrato == 0 && $total_meses > 12 && $associados->current()->status == 1){
				?>
				<div class="uk-badge uk-badge-danger" style="float: right;">Contrato Vencido Favor Emitir um Novo!</div>
				<?php
				}
				?>

			</div>
        </header>
    </article>

    <?php
    $i++;
    $associados->next();
    endwhile;

    ?>

<script type="text/javascript" >

function D_Actions_Assoc(action,val,conv){

/* abre o dependente em modo de edição */
if(action=='edit'){

	LoadContent('assets/associado/Frm_associado.php?matricula='+val+'','content');
	jQuery(".uk-dropdown").hide();
	//var modal = UIkit.modal("#modal01");
	//modal.show();

	}

/* abre o dependente em modo de visão */
if(action=='fat'){

	// mensagen de carregamento
	// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde ");
//abre a tela de preload
modal.show();
//fecha o drop
jQuery(".uk-dropdown").hide();
	New_window('list','950','500','Faturamento','assets/faturamento/Frm_faturamento.php?matricula='+val+'&convenio_id='+conv+'',true,false,'Carregando...');

	}

/* window  dependente*/
if(action=='dep'){

// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde ");
//abre a tela de preload
modal.show();
//fecha o drop
jQuery(".uk-dropdown").hide();

New_window('users','780','500','Dependentes','assets/dependente/Veiw_dependente.php?matricula='+val+'',true,false,'Carregando...');

}



}

</script>
