<?php
require_once"../../../sessao.php";

// corrigi bug do active record
echo'<div class="tabs-spacer" style="display:none;">';
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
$Query_contas=contas_bancarias::all();
echo'</div>';
?>

<div id="Gridcontas" style="height:466px; width: 98%; overflow-y:auto; padding:5px;">
<?php

$conta= new ArrayIterator($Query_contas);
while($conta->valid()):

?>

    <article class="uk-comment">
        <header class="uk-comment-header ">
            <img class="uk-comment-avatar" src="imagens/bancos/<?php if($conta->current()->cod_banco == 0){echo 'no-image';}else{echo $conta->current()->cod_banco; }?>.png" width="50" height="50" alt="">
            <h4 class="uk-comment-title uk-text-bold">
				 <?php
					 echo tool::CompletaZeros(10,$conta->current()->id)." - ".utf8_encode($conta->current()->nm_conta);
				?>
            </h4>

            <div class="uk-coment-action">
           				<div class="uk-button-group">
					<div data-uk-dropdown="{pos:'left-center',mode:'click'}">
						<button class=" uk-button uk-icon-search " style="margin:-8px 0; border:0; background:none;" onclick="D_Actions_Assoc('edit','<?php echo $conta->current()->id;?>');" data-uk-tooltip="{pos:'left'}" title="Visualizar" data-cached-title="Visualizar"></button>
					</div>
				</div>
            </div>

            <div class="uk-comment-meta uk-text-bold "  style="height:30px;">
                Cod.Banco: <?php echo $conta->current()->cod_banco; ?> |
                Agencia: <?php
									echo $conta->current()->agencia;
									if($conta->current()->dv_agencia != ""){echo"-";}
									echo $conta->current()->dv_agencia;
							?>
                            |
                Conta: 		<?php
									echo $conta->current()->conta;
									if($conta->current()->dv_conta != ""){echo"-";}
									echo$conta->current()->dv_conta;
							?>
				 |
             Tipo da Conta: <?php
								if($conta->current()->tp_conta == "0"){echo "Dinheiro";}
								if($conta->current()->tp_conta == "1"){echo "Corrente";}
								if($conta->current()->tp_conta == "2"){echo "Cobrança";}
							?>
            </div>

        </header>
    </article>

<?php
$conta->next();
endwhile;
?>


<script type="text/javascript" >

function D_Actions_Assoc(action,val){

/* abre o dependente em modo de edição */
if(action=='edit'){

	jQuery(".Window").remove();
	New_window('search','780','520','Contas Bancarias','assets/empresa/contas/Frm_contas_bancarias.php?conta_id='+val+'',true,false,'Carregando...');

	}
}
</script>


