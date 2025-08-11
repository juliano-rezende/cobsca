<?php
require_once"../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


if($COB_Acesso_Id==3){$empresa="";}else{$empresa=" usuarios.empresas_id='".$COB_Empresa_Id."'";}

$query="SELECT
		  acessos.nivel,
		  usuarios.*
		FROM
		  usuarios
		  INNER JOIN acessos ON acessos.id = usuarios.acessos_id
		WHERE
		  ".$empresa." ORDER BY id ASC ";

$query_c=users::find_by_sql($query);
$usuarios= new ArrayIterator($query_c);

?>
</div>
<div id="gridusuario" style="height:490px; overflow-y:auto; padding:5px; margin-top: 2px;  ">

<?php

$i=1;
while($usuarios->valid()):



	if($usuarios->current()->status == 0){
					$st		="Inativo";
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
					 echo utf8_encode($usuarios->current()->nm_usuario);
				 ?>
            </h4>
            <div class="uk-coment-action">
           				<div class="uk-button-group">
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class=" uk-button uk-icon-ellipsis-v " style="margin:0; border:0; background:none;"></button>
						<div class="uk-dropdown uk-dropdown-small" >
							<ul class="uk-nav uk-nav-dropdown">
								<li><a  onclick="D_Actions_User('edit','<?php echo tool::CompletaZeros("3",$usuarios->current()->id);?>',null);"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
							</ul>
						</div>
					</div>
				</div>
            </div>

            <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Login: <?php echo utf8_encode($usuarios->current()->nm_usuario); ?> |
                Email: <?php echo $usuarios->current()->email; ?> |
                Status: <div class="uk-badge <?php echo  $class; ?>"><?php echo  $st; ?></div> |
                Nivel: <?php echo $usuarios->current()->nivel; ?>
            </div>
        </header>
    </article>


<?php
$usuarios->next();
endwhile;
?>

</div><!-- fim gridusuario -->

<script type="text/javascript" >

function D_Actions_User(action,val,conv){

/* abre o dependente em modo de edição */
if(action=='edit'){

	LoadContent('assets/usuario/Frm_usuario.php?user_id='+val+'','content');
	jQuery(".uk-dropdown").hide();


	}
}

</script>