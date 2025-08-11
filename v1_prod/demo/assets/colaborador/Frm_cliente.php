<?php
require_once"../../sessao.php";


include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if(isset($_GET['cli_id'])){

$FRM_id_cliente = isset( $_GET['cli_id'])     ? $_GET['cli_id'] : tool::msg_erros("Codigo de cliente Invalido.");


// recupera os dados do associado
$dadoscliente=clientes_fornecedores::find_by_sql("SELECT
                                                 SQL_CACHE  clientes_fornecedores.*,
                                                            logradouros.descricao as nm_logradouro,
                                                            logradouros.cep,
                                                            estados.id AS estado_id,
                                                            estados.sigla AS nm_estado,
                                                            cidades.id AS cidade_id,
                                                            cidades.descricao AS nm_cidade,
                                                            bairros.id AS bairro_id,
                                                            bairros.descricao AS nm_bairro
                                                 FROM
                                                    clientes_fornecedores
                                                      INNER JOIN logradouros ON logradouros.id = clientes_fornecedores.logradouros_id
                                                      INNER JOIN estados ON estados.id = logradouros.estados_id
                                                      INNER JOIN cidades ON cidades.id = logradouros.cidades_id
                                                      INNER JOIN bairros ON bairros.id = logradouros.bairros_id
                                                    WHERE
                                                      clientes_fornecedores.id = '".$FRM_id_cliente."'");
if($dadoscliente[0]->status == 0){
					$st		="Cancelado";
					$class	=" uk-badge-danger";
                }else{
					$st		="Ativo";
					$class	="";
                    }
}
?>
</div>

<form method="post" id="FrmCliente" class="uk-form uk-form-shadow " style="width: 900px;">
<div id="menu-float" style="text-align:center;margin:0 900px;">

	<a  id="Btn_cli_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
    <hr id="Btn_cli_0_divider" class="uk-article-divider">

<?php if(isset($dadoscliente)):?>
    <a  id="Btn_cli_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Cliente" data-cached-title="Novo Cliente" ></a>
    <a  id="Btn_cli_2" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" ></a>

<?php
else:
?>
    <a  id="Btn_cli_2" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" ></a>

<?php endif;?>
</div>

<fieldset style="width:870px;">
        <legend>
            <i class="uk-icon-user " ></i> Dados do Cliente
        </legend>
        <label>
            <span>Codigo</span>
            <input value="<?php  if(isset($dadoscliente)):
            echo tool::CompletaZeros("11",$dadoscliente[0]->id); endif; ?>"  name="cliente_id" type="text" class=" w_100 " readonly id="cliente_id"  />
		<?php  if(isset($dadoscliente)): ?>
        	<div class="uk-badge <?php echo  $class; ?>"><?php echo  $st; ?></div>
		<?php endif; ?>


        </label>
        <label>
            <span>Nome</span>
            <input value="<?php  if(isset($dadoscliente)):
            echo $dadoscliente[0]->nm_cliente; endif; ?>"  name="nm_cliente1" type="text" class="w_400 " id="nm_cliente1"  />
        </label>
        <label>
            <span>Cpf</span>
            <input value="<?php  if(isset($dadoscliente)):echo $dadoscliente[0]->cpf; endif; ?>"  name="cpf" type="text" class=" w_100 uk-text-center" id="cpf"  />
        </label>
        <label>
            <span>Rg</span>
            <input value="<?php if(isset($dadoscliente)):echo $dadoscliente[0]->rg; endif; ?>" name="rg" type="text" class=" w_100 uk-text-center" id="rg"  />
        </label>

        <label>
            <span>Fone fixo</span>
            <div class="uk-form-icon">
                <i class="uk-icon-fax"></i>
                <input name="fone_fixo" type="text" class=" w_150 uk-text-center " id="fone_fixo"  value="<?php if(isset($dadoscliente)):
                echo tool::MascaraCampos("(??) ????-????",$dadoscliente[0]->fone_fixo); endif; ?>" >
            </div>
        </label>
        <label>
            <span>Fone fixo</span>
            <div class="uk-form-icon">
                <i class="uk-icon-fax"></i>
                <input name="fone_cel" type="text" class=" w_150 uk-text-center " id="fone_cel"  value="<?php if(isset($dadoscliente)):echo tool::MascaraCampos("(??) ?????-????",$dadoscliente[0]->fone_cel); endif; ?>" >
            </div>
        </label>
        <label>
            <span>E-mail</span>
            <div class="uk-form-icon">
                <i class="uk-icon-envelope"></i>
                <input name="email" type="text" class="w_400 uk-text-left" id="email" value="<?php if(isset($dadoscliente)):echo $dadoscliente[0]->email; endif; ?>">
            </div>
        </label>
    <legend>
        <i class="uk-icon-street-view " ></i> Endereço
    </legend>
    <label>
        <span>Cep</span>
        <div class="uk-form-icon">
                <i class="uk-icon-street-view"></i>
        <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadoscliente)):echo tool::MascaraCampos("?????-???",$dadoscliente[0]->cep); endif; ?>" maxlength="9"  >
        </div>
        <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>
    </label>
<!-- -->
    <label>
        <span>Estado</span>
        <select class="select w_400" name="uf"  id="uf" >
            <?php
            if(isset($dadoscliente)):

                   $descricaoestados=estados::find("all");
                    $descricao= new ArrayIterator($descricaoestados);
                    while($descricao->valid()):
                        if($descricao->current()->id == $dadoscliente[0]->estado_id){$select='selected="selected"';}else{$select="";}
                        echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';
                    $descricao->next();
                    endwhile;
            else:
                echo'<option value="" ></option>';
                $descricaoestados=estados::find('all');
                $descricao= new ArrayIterator($descricaoestados);
                while($descricao->valid()):
                    echo'<option value="'.$descricao->current()->id.'" >'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';
                    $descricao->next();
                endwhile;
            endif;
            ?>
        </select> <button class="uk-button" type="button" onClick="New_estado();"><i class="uk-icon-plus"></i></button>
    </label>
<!-- -->
    <label>
        <span>cidade</span>
        <select class="select w_400" name="cidade"  id="cidade" >
            <?php
            if(isset($dadoscliente)):
            echo'<option value="'.$dadoscliente[0]->cidade_id.'" selected="selected">'.utf8_encode(strtoupper($dadoscliente[0]->nm_cidade)).'</option>';
            else:
            echo'<option value="" ></option>';
            endif;
            ?>
        </select> <button class="uk-button" type="button" onClick="New_cidade();"><i class="uk-icon-plus"></i></button>
    </label>
<!-- -->
    <label>
        <span>bairro</span>
            <select class="select w_400" name="bairro"  id="bairro" >
            <?php
            if(isset($dadoscliente)):
            echo'<option value="'.$dadoscliente[0]->bairro_id.'" selected="selected">'.utf8_encode(strtoupper($dadoscliente[0]->nm_bairro)).'</option>';
            else:
            echo'<option value="" ></option>';
            endif;
            ?>
        </select> <button class="uk-button" type="button" onClick="New_bairro();"><i class="uk-icon-plus"></i></button>
    </label>
<!-- -->
    <label>
        <span>logradouro</span>
            <select class="select w_400" name="logradouro"  id="logradouro" >
            <?php
            if(isset($dadoscliente)):
            echo'<option value="'.$dadoscliente[0]->logradouros_id.'" selected="selected">'.utf8_encode(strtoupper($dadoscliente[0]->nm_logradouro)).'</option>';
            else:
            echo'<option value="" ></option>';
            endif;
            ?>
        </select> <button class="uk-button" type="button" onClick="New_logradouro();"><i class="uk-icon-plus"></i></button>
    </label>
     <label>
        <span>Complemento</span>
        <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadoscliente)):echo $dadoscliente[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
    </label>
    <label>
        <span>Numero</span>
        <input name="num" class=" w_80 uk-text-center" id="num"  value="<?php if(isset($dadoscliente)):echo $dadoscliente[0]->num; endif; ?>" maxlength="9" >
    </label>
</fieldset>
</form>
<br />

<script type="text/javascript" src="assets/js/cliente.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>