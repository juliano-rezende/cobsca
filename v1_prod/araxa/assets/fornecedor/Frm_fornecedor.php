<?php
require_once"../../sessao.php";

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if(isset($_GET['for_id'])){

$FRM_id_fornecedor = isset( $_GET['for_id'])     ? $_GET['for_id'] : tool::msg_erros("Codigo de fornecedor Invalido.");


// recupera os dados do associado
$dadosfornecedor=clientes_fornecedores::find_by_sql("SELECT
                                                      clientes_fornecedores.*,
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
                                                      clientes_fornecedores.id = '".$FRM_id_fornecedor."'");
if($dadosfornecedor[0]->status == 0){
					$st		="Cancelado";
					$class	=" uk-badge-danger";
                }else{
					$st		="Ativo";
					$class	="";
                    }
}
?>
</div>


<!-- formularios de adição de endereços -->

<div id="dvuf" class="uk-modal">
<?php include"../../endereco/add_end/Frm_uf.php"; ?>
</div>
<div id="dvcid" class="uk-modal">
<?php include"../../endereco/add_end/Frm_cidade.php"; ?>
</div>
<div id="dvbair" class="uk-modal">
<?php include"../../endereco/add_end/Frm_bairro.php"; ?>
</div>
<div id="dvrua" class="uk-modal">
<?php include"../../endereco/add_end/Frm_logradouro.php"; ?>
</div>


<form method="post" id="FrmFornecedor" class="uk-form uk-form-shadow " style="width: 900px;">
<div id="menu-float" style="text-align:center;margin:0 900px;">

	<a  id="Btn_for_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
    <hr id="Btn_for_0_divider" class="uk-article-divider">

<?php if(isset($dadosfornecedor)):?>
    <a  id="Btn_for_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Fornecedor" data-cached-title="Novo Fornecedor" ></a>
    <a  id="Btn_for_2" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" ></a>

<?php
else:
?>
    <a  id="Btn_for_2" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" ></a>

<?php endif;?>
</div>

<fieldset style="width:870px;">

        <legend>
            <i class="uk-icon-building" ></i> Dados do Fornecedor
        </legend>
        <label>
            <span>Codigo</span>
            <input value="<?php  if(isset($dadosfornecedor)):
            echo tool::CompletaZeros("11",$dadosfornecedor[0]->id); endif; ?>"  name="for_id" type="text" class="w_100 " readonly id="for_id"  />
		<?php  if(isset($dadosfornecedor)): ?>
        	<div class="uk-badge <?php echo  $class; ?>"><?php echo  $st; ?></div>
		<?php endif; ?>
        </label>
<label>
        <span>Tipo Fornecedor</span>
            <select name="tp_pessoa" class="select w_120" id="tp_pessoa">
            <?php
                if(isset($dadosfornecedor)){

                    if($dadosfornecedor[0]->tp_pessoa == 0){

                        echo'<option value=""></option>';echo'<option value="0" selected>P.Fisica</option>';echo'<option value="1">P.Juridica</option>';

                    }else{
                        echo'<option value=""></option>';echo'<option value="0">P.Fisica</option>';echo'<option value="1" selected>P.Juridica</option>';

                    }
                }else{
                    echo'<option value="" selected></option>';echo'<option value="0">P.Fisica</option>';echo'<option value="1" >P.Juridica</option>';
                    }
            ?>
            </select>
        </label>
        <label id="lab01">
            <span>Nome</span>
            <input value="<?php  if(isset($dadosfornecedor)):
            echo $dadosfornecedor[0]->nm_cliente; endif; ?>"  name="nm_cliente" type="text" class="w_400 " id="nm_cliente"  />
        </label>
        <label id="lab02">
            <span>Razão Social</span>
            <input value="<?php  if(isset($dadosfornecedor)):
            echo $dadosfornecedor[0]->razao_social; endif; ?>"  name="rzsc" type="text" class="w_400 " id="rzsc"  />
        </label>
        <label id="lab03">
            <span>Nome Fantasia</span>
            <input value="<?php  if(isset($dadosfornecedor)):
            echo $dadosfornecedor[0]->nm_fantasia; endif; ?>"  name="nmfant" type="text" class="uk-text-left w_400 " id="nmfant"  />
        </label>

        <label id="lab04">
            <span>Cpf</span>
            <input value="<?php  if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->cpf; endif; ?>"  name="cpf" type="text" class=" w_100 uk-text-center" id="cpf"  />
        </label>
        <label id="lab05">
            <span>Cnpj</span>
            <input value="<?php  if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->cnpj; endif; ?>"  name="cnpj" type="text" class=" w_150 uk-text-center" id="cnpj"  />
        </label>
        <label id="lab06">
            <span>Rg</span>
            <input value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->rg; endif; ?>" name="rg" type="text" class=" w_100 uk-text-center" id="rg"  />
        </label>
        <label id="lab07">
            <span>Isc.Est</span>
            <input value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->ie; endif; ?>" name="ie" type="text" class=" w_100 uk-text-center" id="ie"  />
        </label>
        <label id="lab08">
            <span>Isc.Mun</span>
            <input value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->im; endif; ?>" name="im" type="text" class=" w_100 uk-text-center" id="im"  />
        </label>


        <label>
            <span>Fone fixo</span>
            <div class="uk-form-icon">
                <i class="uk-icon-fax"></i>
                <input name="fn_fx" type="text" class=" w_150 uk-text-center " id="fn_fx"  value="<?php if(isset($dadosfornecedor)):
                echo tool::MascaraCampos("(??) ????-????",$dadosfornecedor[0]->fone_fixo); endif; ?>" >
            </div>
        </label>
        <label>
            <span>Fone celular</span>
            <div class="uk-form-icon">
                <i class="uk-icon-phone"></i>
                <input name="fn_cel" type="text" class=" w_150 uk-text-center " id="fn_cel"  value="<?php if(isset($dadosfornecedor)):echo tool::MascaraCampos("(??) ?????-????",$dadosfornecedor[0]->fone_cel); endif; ?>" >
            </div>
        </label>
        <label>
            <span>E-mail</span>
            <div class="uk-form-icon">
                <i class="uk-icon-envelope"></i>
                <input name="email" type="text" class="uk-text-left w_400" id="email" value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->email; endif; ?>">
            </div>
        </label>
         <label>
            <span>Website</span>
            <div class="uk-form-icon">
                <i class="uk-icon-sitemap"></i>
                <input name="wbst" type="text" class="uk-text-left w_400" id="wbst" value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->website; endif; ?>">
            </div>
        </label>
        <label>
            <span>Contato</span>
            <div class="uk-form-icon">
                <i class="uk-icon-user"></i>
                <input name="ct" type="text" class="uk-text-left w_400" id="ct" value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->contato; endif; ?>">
            </div>
        </label>
    <legend style="margin-bottom: 5px;">
        <i class="uk-icon-street-view " ></i> Endereço
    </legend>

    <label>
        <span>Estado</span>
        <select class="select w_400" name="uf"  id="uf" >
            <?php
            if(isset($dadosfornecedor)):

                   $descricaoestados=estados::find("all");
                    $descricao= new ArrayIterator($descricaoestados);
                    while($descricao->valid()):
                        if($descricao->current()->id == $dadosfornecedor[0]->estado_id){$select='selected="selected"';}else{$select="";}
                        echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';
                    $descricao->next();
                    endwhile;
            else:
                echo'<option value="" >Selecione o estado</option>';
                $descricaoestados=estados::find('all');
                $descricao= new ArrayIterator($descricaoestados);
                while($descricao->valid()):
                    echo'<option value="'.$descricao->current()->id.'" >'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';
                    $descricao->next();
                endwhile;
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvuf'}"><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
<!-- -->
    <label>
        <span>cidade</span>
        <select class="select w_400" name="cidade"  id="cidade" >
            <?php
            if(isset($dadosfornecedor)):
            echo'<option value="'.$dadosfornecedor[0]->cidade_id.'" selected="selected">'.utf8_encode(strtoupper($dadosfornecedor[0]->nm_cidade)).'</option>';
            else:
            echo'<option value="" >Aguardando...</option>';
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvcid'}"><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
<!-- -->
    <label>
        <span>bairro</span>
            <select class="select w_400" name="bairro"  id="bairro" >
            <?php
            if(isset($dadosfornecedor)):
            echo'<option value="'.$dadosfornecedor[0]->bairro_id.'" selected="selected">'.utf8_encode(strtoupper($dadosfornecedor[0]->nm_bairro)).'</option>';
            else:
            echo'<option value="" >Aguardando...</option>';
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvbair'}" ><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
<!-- -->
    <label>
        <span>logradouro</span>
            <select class="select w_400" name="logradouro"  id="logradouro" >
            <?php
            if(isset($dadosfornecedor)):
            echo'<option value="'.$dadosfornecedor[0]->logradouros_id.'" selected="selected">'.utf8_encode(strtoupper($dadosfornecedor[0]->nm_logradouro)).'</option>';
            else:
            echo'<option value="" >Aguardando...</option>';
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvrua'}"><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
     <label>
        <span>Complemento</span>
        <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
    </label>
    <label>
        <span>Numero</span>
        <input name="num" class=" w_100 uk-text-center" id="num"  value="<?php if(isset($dadosfornecedor)):echo $dadosfornecedor[0]->num; endif; ?>" maxlength="9" >
    </label>
   <label>
        <span>Cep</span>
        <div class="uk-form-icon">
                <i class="uk-icon-street-view"></i>
        <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadosfornecedor)):echo tool::MascaraCampos("?????-???",$dadosfornecedor[0]->cep); endif; ?>" maxlength="9"  readonly>
        </div>
 <!--       <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>-->
    </label>
</fieldset>
</form>
<br />

<script type="text/javascript" src="assets/js/fornecedor.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>