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

	$query="SELECT SQL_CACHE *
		FROM  med_parceiros
				WHERE (razao_social LIKE '".tool::limpaString($_POST['vl'])."%' OR  (nm_fantasia like '".tool::limpaString($_POST['vl'])."%') OR (nm_parceiro LIKE '".tool::limpaString($_POST['vl'])."%'))  ORDER BY id ASC"; // pesquisa pela razao social ou nome fantasia
}else{
	$query="SELECT SQL_CACHE * FROM med_parceiros ORDER BY id ASC LIMIT 6";
}

// corrigi bug do active record
echo'<div class="tabs-spacer" style="display:none;">';
$query_a=med_parceiros::find_by_sql($query);
echo'</div>';

if(!$query_a){
	$erro	=1;
	$msg	="Parceiro não encontrado ou não cadastrado.";
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

	$parceiros= new ArrayIterator($query_a);
	while($parceiros->valid()):
		?>
		<article class="uk-comment">
			<header class="uk-comment-header " style="padding: 5px;">
				<img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
				<h4 class="uk-comment-title uk-text-bold">
					<?php
					if($parceiros->current()->tp_parceiro == "J"){echo utf8_encode($parceiros->current()->nm_fantasia);}else{echo utf8_encode($parceiros->current()->nm_parceiro);}
					?>
				</h4>
				<div class="uk-coment-action">
					<div class="uk-button-group">
						<button class="uk-button" style="float: left;">Ações</button>
						<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
							<button class="uk-button" style="margin:0 -2px;"><i class="uk-icon-caret-down"></i></button>
							<div class="uk-dropdown uk-dropdown-small" >
								<ul class="uk-nav uk-nav-dropdown">
									<li><a  onclick="D_Actions_Parceiros('edit','<?php echo $parceiros->current()->id;?>',null);" data-uk-tooltip="{pos:'left'}"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
								</ul>
							</div>

						</div>
					</div>
				</div>
				<div class="uk-comment-meta uk-text-bold "  style="height:30px;">
					Codigo: <?php echo tool::CompletaZeros(10,$parceiros->current()->id); ?> |
					CNPJ/CPF: <?php echo tool::MascaraCampos("??.???.???/????-??",$parceiros->current()->cnpj).tool::MascaraCampos("???.???.???-??",$parceiros->current()->cpf); ?> |
					Data Cadastro: <?php  $datacad= new ActiveRecord\DateTime($parceiros->current()->dt_cadastro);echo $datacad->format('d/m/Y');
					?>
				</div>
			</header>
		</article>
		<?php
		$parceiros->next();
	endwhile;

}
?>
<script type="text/javascript" >
	function D_Actions_Parceiros(action,val,conv){

		/* abre o dependente em modo de edição */
		if(action=='edit'){

			LoadContent('assets/parceiros/Frm_parceiro.php?par_id='+val+'','content');
			jQuery(".uk-dropdown").hide();
	//var modal = UIkit.modal("#modal01");
	//modal.show();
}
}
</script>