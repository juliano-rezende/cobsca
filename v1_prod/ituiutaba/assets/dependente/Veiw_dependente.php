<?php require_once("../../sessao.php"); ?>
<div class="tabs-spacer" style="display:none;">
<?php

$FRM_matricula		=	isset( $_GET['matricula']) 	? $_GET['matricula']	: tool::msg_erros("O Campo matricula é Obrigatorio.");


require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
$query=dependentes::find_by_sql("SELECT SQL_CACHE dependentes.*, parentescos.descricao as parent_desc
								 FROM dependentes INNER JOIN parentescos ON dependentes.parentescos_id = parentescos.id
								 WHERE dependentes.matricula = '".$FRM_matricula."'");
?>
</div>
<style>
#menu-float a{ background-color:transparent;}
</style>
<div id="menu-float" style="text-align:center;margin:0 780px; top:40px;  border:0;background-color:#546e7a; ">
	<a  onclick="D_Act('new','<?php echo $FRM_matricula;?>');" class="uk-icon-button uk-icon-user-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Dependente" data-cached-title="Novo Dependente" ></a>
</div>

<div id="GridDependentes" style=" overflow-y:auto; height:488px; padding:5px; width: 98.5%;">
<?php

$dependentes= new ArrayIterator($query);
$totaldelinhas=count($dependentes);
$i=1;
while($dependentes->valid()):


$matricula=tool::CompletaZeros("10",$dependentes->current()->matricula);
$datacad= new ActiveRecord\DateTime($dependentes->current()->dt_cadastro);
$datanasc = new ActiveRecord\DateTime($dependentes->current()->dt_nascimento);
$dataalt = new ActiveRecord\DateTime($dependentes->current()->dt_ult_alteracao);

if($dependentes->current()->status == 0){
					$st="<div class=\"uk-badge uk-badge-danger\" >Inativo</div>";
                }else{
					$st="<div class=\"uk-badge uk-badge-success\" >Ativo</div>";
                    }
?>
    <article class="uk-comment">
        <header class="uk-comment-header ">
            <img class="uk-comment-avatar" src="framework/uikit-2.24.0/images/placeholder_avatar.svg" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
    		<?php
					 echo utf8_encode($dependentes->current()->nome);
			?>
            <?php echo $st; ?>
            </h4>
            <div class="uk-coment-action">
				<div class="uk-button-group">
					<div data-uk-dropdown="{pos:'left-center'}">
						<button class="uk-button uk-icon-ellipsis-v " style="margin:-10px 0; border:0; background:none;"></button>
                        <a href="" class="uk-icon-hover uk-icon-github"></a>
						<div class="uk-dropdown uk-dropdown-small">
							<ul class="uk-nav uk-nav-dropdown">
								<li><a href="#" onclick="D_Act('edit','<?php echo $dependentes->current()->id;?>');"><i class="uk-icon-edit"></i> Editar</a></li>
                                <?php if($dependentes->current()->status == 0){ ?>
                                <li><a href="#" onclick="D_Act('enabled','<?php echo $dependentes->current()->id;?>');"><i class="uk-icon-check-square-o"></i> Reativar</a></li>
                                <?php }else{ ?>
								<li><a href="#" onclick="D_Act('disabled','<?php echo $dependentes->current()->id;?>');"><i class="uk-icon-remove"></i> Desativar</a></li>
                                <?php } ?>
                                <?php if($datacad->format('d/m/Y') == date("d/m/Y")){ ?>
								<li class="uk-nav-divider"></li>
								<li><a href="#" onclick="D_Act('remove','<?php echo $dependentes->current()->id;?>');"><i class="uk-icon-trash-o"></i> Remover</a></li>
                                <?php } ?>
							</ul>
						</div>
					</div>
				</div>
           </div>

            <div class="uk-comment-meta uk-text-bold ">
            	Parentesco: <?php echo $dependentes->current()->parent_desc; ?> |
                CPF: <?php echo tool::MascaraCampos("###.###.###-##",$dependentes->current()->cpf); ?> |
                Data Nasc:	<?php  echo $datanasc->format('d/m/Y');?> |
                Data Cad:		<?php  echo $datacad->format('d/m/Y');?>
            </div>
        </header>
    </article>

<?php
    $i++;
    $dependentes->next();
    endwhile;
?>
</div>


<script type="text/javascript" >

// altera a cor de fundo do menu flutuante
jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-color')+"");


function D_Act(act,val){


/* abre o dependente em modo de edição */
if(act=='edit'){

	New_window('users','730','330','Editar','assets/dependente/Frm_dependente.php?action=edit&id='+val+'',true,false,'Carregando...');
	jQuery(".uk-dropdown").hide();

	}

/* abre o dependente em modo de visão */
if(act=='new'){

	New_window('users','730','330','Adcionar','assets/dependente/Frm_dependente.php?action=&matricula=<?php echo $FRM_matricula;?>',true,false,'Carregando...');


	}

/* desativa o dependente*/
if(act=='disabled'){

// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde ");

//abre a tela de preload
modal.show();


$.post("assets/dependente/Controller_dependente.php",{acao:"disabled",Dep_id:val},
function(resultado){

	if(jQuery.isNumeric(resultado)){
		// mensagen de carregamento
		jQuery("#msg_loading").html(" Carregando ");

		$("#"+$("#menu-float").closest('.Window').attr('id')+"").remove();

		New_window('users','780','500','Dependentes','assets/dependente/Veiw_dependente.php?matricula=<?php echo $FRM_matricula;?>',true);

		}else{
			//abre a tela de preload
			modal.hide();
			UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
		}
	});



	}

/* desativa o dependente*/
if(act=='enabled'){

	// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde ");

//abre a tela de preload
modal.show();


$.post("assets/dependente/Controller_dependente.php",{acao:"enabled",Dep_id:val},
function(resultado){

	if(jQuery.isNumeric(resultado)){
		// mensagen de carregamento
		jQuery("#msg_loading").html(" Carregando ");

		$("#"+$("#menu-float").closest('.Window').attr('id')+"").remove();

		New_window('users','780','500','Dependentes','assets/dependente/Veiw_dependente.php?matricula=<?php echo $FRM_matricula;?>',true);

		}else{
			//abre a tela de preload
			modal.hide();
			UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
		}
	});
	}



/* remove o dependente*/
if(act=='remove'){

	// mensagen de carregamento
jQuery("#msg_loading").html(" Aguarde ");

//abre a tela de preload
modal.show();

$.post("assets/dependente/Controller_dependente.php",{acao:"remove",Dep_id:val},
function(resultado){

	if(jQuery.isNumeric(resultado)){

		// mensagen de carregamento
		jQuery("#msg_loading").html(" Carregando ");

		$("#"+$("#menu-float").closest('.Window').attr('id')+"").remove();

        New_window('users','780','500','Dependentes','assets/dependente/Veiw_dependente.php?matricula=<?php echo $FRM_matricula;?>',true,false,'Carregando...');

		}else{
			//abre a tela de preload
			modal.hide();
			UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
		}
	});
}


}

</script>
