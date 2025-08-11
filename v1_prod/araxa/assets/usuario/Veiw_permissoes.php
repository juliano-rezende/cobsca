<div class="tabs-spacer" style="display:none;">
<?php

include("../../functions/funcoes.php");
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$usuario_id	=	isset( $_GET['usuario_id']) ? $_GET['usuario_id']	: tool::msg_erros("O Campo Codigo de Usuario é Obrigatorio.");
$query		=	menu::all();
?>
</div>

<link href="js/jquery/plugins/jquery_switch_Button/css/jquery.switchButton.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery/plugins/jquery_switch_Button/jquery.switchButton.js?<?php echo microtime(); ?>"></script>
<style>
.uk-nav-header{ background-color:#009dd8;}
.uk-nav-header a{ color:#fff;}

</style>

<div class="Grid_usuarios" style="margin-top:0px; overflow-y:auto; width: 350px; height:460px;" id="grid_search_usuarios">
<form method="post" id="FrmPermissões" class="uk-form" style=" width:auto;  padding-top:0; margin:0;">


  <ul class="uk-nav ">

<?php
$list= new ArrayIterator($query);
while($list->valid()):
?>
	<li class="uk-parent uk-nav-header" >
    	<a href="#" >
            <i class="uk-icon-cog"></i>
            <?php echo utf8_encode($list->current()->descricao); ?>
        </a>
        </li>
        <div class="tabs-spacer" style="display:none;">
			<?php
            $query1=submenu::find("all",array('conditions'=>array("menu_id = ?",$list->current()->id)));
            ?>
        </div>
        <li class="uk-parent">
            <ul class="uk-nav-sub">
             <?php
                $list1= new ArrayIterator($query1);
                while($list1->valid()):
                echo'<div class="tabs-spacer" style="display:none;">';
                $permissaosubmenu=permissaosubmenu::find_by_submenu_id_and_usuario_id($list1->current()->id,$usuario_id);
                echo'</div>';
            ?>
                <li style="text-transform:capitalize;padding-left:10px; ">
                      <a href="#" class="uk-text-small uk-text-bold" style="color:#666;">
                <?php if(isset($permissaosubmenu->status)){ ?>
                      <input type="checkbox" name="check-<?php echo $list1->current()->id; ?>" value="<?php echo $list1->current()->menu_id.";".$list1->current()->id; ?>" class="lcs_check"  autocomplete="off" <?php if($permissaosubmenu->status == 1){echo 'checked="checked"';}?> />
                    <?php }else{?>
                       <input type="checkbox" name="check-<?php echo $list1->current()->id; ?>" value="<?php  echo $list1->current()->menu_id.";".$list1->current()->id; ?>" class="lcs_check"  autocomplete="off"  />
                     <?php } ?>
                     <?php echo utf8_encode($list1->current()->descricao); ?></a>
                </li>
          <?php
            $list1->next();
            endwhile;
            ?>
            </ul>
		</li>

<?php
$list->next();
endwhile;
?>
</ul>
</form>
</div>
<div id="footer_print" style=" padding-top:5px;width:100%; text-align:right; border-top:1px solid #ccc;background:-webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#f9f9f9)) repeat-X;">
<a  id="BtnConfirmarPermissoes" class="uk-button uk-button-primary" style="margin-right:5px;" data-uk-tooltip="Confirmar" title="Confirmar" data-cached-title="Confirmar" >
        <i class="uk-icon-check" ></i> Confirmar
    </a>
</div>

<script language="Javascript">



// mensagen de carregamento
jQuery("#msg_loading").html(" Carregando ");
//abre a tela de preload
modal.hide();

/////////////////////////////////////////// BOTÃO CONFIRMAR EDIÇÃO DOS DADOS ///////////////////////////////////////////
jQuery("#BtnConfirmarPermissoes").click(function() {
		// mensagen de carregamento
		jQuery("#msg_loading").html(" Aguarde ");
		//abre a tela de preload
		modal.show();

	if(jQuery(".lcs_check").is(':checked') ){
		var iditems = new Array();// array com os valores dos checks
		jQuery('.lcs_check:checked').each(function(){// verifica qual está marcado
			iditems.push(""+$(this).val()+"");// retorna o valor do campo marcado
		});
		jQuery.ajax({
					url: "assets/usuario/Controller_permissoes.php",
					type: "post",
					data:'user='+jQuery("#usuario_id").val()+'&acessos='+iditems+'',
					success: function(resultado) {
						if(jQuery.isNumeric(resultado)){
						//mensagen de carregamento
						jQuery("#msg_loading").html(" Atualizando Grid  ");
						//variavel com id
						var usuario_id=resultado;
						//fecha janela antes de atualiza
						jQuery(".Window").remove();
						//recarrega a pagina
						New_window('list','350','500','Permissões de Acesso','assets/usuario/Veiw_permissoes.php?usuario_id='+usuario_id.replace(/[^\d]+/g,'')+'',true,false,'Carregando...');
						}else{
							//abre a tela de preload
							modal.hide();
							UIkit.modal.alert(""+resultado+"");
						}
					},
					error:function (){
						UIkit.modal.alert("Caminho Invalido!");
						modal.hide();
						}
				});

	}else{
		UIkit.modal.alert("Não há parcelas Selecionadas !");
		}
});

</script>

