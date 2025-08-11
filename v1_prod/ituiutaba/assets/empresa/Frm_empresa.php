<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if(isset($_GET['emp_id'])){

$FRM_id_empresa = isset( $_GET['emp_id'])     ? $_GET['emp_id'] : tool::msg_erros("Codigo da empresa Invalido.");


// recupera os dados da empresa
$dadosempresa=empresas::find_by_sql("SELECT
                                        empresas.*,
                                        logradouros.descricao as nm_logradouro,
                                        logradouros.cep,estados.id AS estado_id,
                                        estados.sigla AS nm_estado,
                                        cidades.id AS cidade_id,
                                        cidades.descricao AS nm_cidade,
                                        bairros.id AS bairro_id,
                                        bairros.descricao AS nm_bairro
                                    FROM empresas
                                    INNER JOIN logradouros ON logradouros.id = empresas.logradouros_id
                                    INNER JOIN estados ON estados.id = logradouros.estados_id
                                    INNER JOIN cidades ON cidades.id = logradouros.cidades_id
                                    INNER JOIN bairros ON bairros.id = logradouros.bairros_id
                                    WHERE empresas.id = '".$FRM_id_empresa."'");
if($dadosempresa[0]->status == 0){
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

<form method="post" id="FrmAssociado" class="uk-form uk-form-shadow "  style="width: 900px;">
<div id="menu-float" style="text-align:center;margin:0 900px;">

	<a  id="Btn_emp_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
<hr class="uk-article-divider">

<?php if(isset($dadosempresa)):?>

    <a  id="Btn_emp_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Nova Empresa" data-cached-title="Nova Empresa" ></a>
    <a  id="Btn_emp_2" class="uk-icon-button  uk-icon-bank" style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Contas Bancarias" data-cached-title="Contas Bancarias" ></a>
    <a  id="Btn_emp_3" class="uk-icon-button  uk-icon-gear " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Configurações" data-cached-title="Configurações" ></a>
    <!--<a  id="Btn_emp_5" class="uk-icon-button  uk-icon-barcode " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Formas de Pagamento" data-cached-title="Formas de Pagamento" ></a>
    <a  id="Btn_emp_6" class="uk-icon-button  uk-icon-credit-card " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Formas Recebimento" data-cached-title="Formas Recebimento" ></a>-->
    <a  id="Btn_emp_4" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" ></a>

<?php
else:
?>
    <a  id="Btn_emp_4" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" ></a>

<?php endif;?>
</div>

<fieldset style="width:870px;">

        <legend>
            <i class="uk-icon-building " ></i> Dados da Empresa
        </legend>
        <label>
            <span>Codigo</span>
            <input value="<?php  if(isset($dadosempresa)):
            echo tool::CompletaZeros("11",$dadosempresa[0]->id); endif; ?>"  name="emp_id" type="text" class="input_text w_100 " readonly id="emp_id"  />
		<?php  if(isset($dadosempresa)): ?>
        	<div class="uk-badge <?php echo  $class; ?>"><?php echo  $st; ?></div>
		<?php endif; ?>


        </label>
        <label>
            <span>Razão Social</span>
            <input value="<?php  if(isset($dadosempresa)):
            echo utf8_encode($dadosempresa[0]->razao_social); endif; ?>"  name="rzsc" type="text" class="uk-text-left w_400 " id="rzsc"  />
        </label>
        <label>
            <span>Nome Fantasia</span>
            <input value="<?php  if(isset($dadosempresa)):
            echo utf8_encode($dadosempresa[0]->nm_fantasia); endif; ?>"  name="nmfant" type="text" class="uk-text-left w_400 " id="nmfant"  />
        </label>
        <label>
            <span>Cnpj</span>
            <input value="<?php  if(isset($dadosempresa)):echo $dadosempresa[0]->cnpj; endif; ?>"  name="cnpj" type="text" class="uk-text-center w_150 " id="cnpj"  />
        </label>
        <label>
            <span>Insc.Mun</span>
            <input value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->im; endif; ?>" name="im" type="text" class=" w_100 uk-text-center" id="im"  />
        </label>
        <label>
            <span>Insc.Est</span>
            <input value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->ie; endif; ?>" name="ie" type="text" class=" w_100 uk-text-center" id="ie"  />
        </label>

        <label>
            <span>Fone fixo</span>
            <div class="uk-form-icon">
                <i class="uk-icon-fax"></i>
                <input name="fn_fx" type="text" class="uk-text-center w_150  " id="fn_fx"  value="<?php if(isset($dadosempresa)):
                echo tool::MascaraCampos("(??) ????-????",$dadosempresa[0]->fone_fixo); endif; ?>" >
            </div>
        </label>
        <label>
            <span>Fone celular</span>
            <div class="uk-form-icon">
                <i class="uk-icon-phone"></i>
                <input name="fn_cel" type="text" class="uk-text-center w_150  " id="fn_cel"  value="<?php if(isset($dadosempresa)):echo tool::MascaraCampos("(??) ?????-????",$dadosempresa[0]->fone_cel); endif; ?>" >
            </div>
        </label>
        <label>
            <span>E-mail</span>
            <div class="uk-form-icon">
                <i class="uk-icon-envelope"></i>
                <input name="email" type="email" class="uk-text-left w_400" id="email" value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->email; endif; ?>">
            </div>
        </label>
         <label>
            <span>Website</span>
            <div class="uk-form-icon">
                <i class="uk-icon-sitemap"></i>
                <input name="wbst" type="url" class="uk-text-left w_400" id="wbst" value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->website; endif; ?>">
            </div>
        </label>
        <label>
            <span>Contato</span>
            <div class="uk-form-icon">
                <i class="uk-icon-user"></i>
                <input name="ct" type="text" class="uk-text-left w_400" id="ct" value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->contato; endif; ?>">
            </div>
        </label>
    <legend style="margin-bottom: 5px;">
        <i class="uk-icon-street-view " ></i> Endereço
    </legend>

    <label>
        <span>Estado</span>
        <select class="select w_400" name="uf"  id="uf" >
            <?php
            if(isset($dadosempresa)):

                   $descricaoestados=estados::find("all");
                    $descricao= new ArrayIterator($descricaoestados);
                    while($descricao->valid()):
                        if($descricao->current()->id == $dadosempresa[0]->estado_id){$select='selected="selected"';}else{$select="";}
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
            if(isset($dadosempresa)):
            echo'<option value="'.$dadosempresa[0]->cidade_id.'" selected="selected">'.utf8_encode(strtoupper($dadosempresa[0]->nm_cidade)).'</option>';
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
            if(isset($dadosempresa)):
            echo'<option value="'.$dadosempresa[0]->bairro_id.'" selected="selected">'.utf8_encode(strtoupper($dadosempresa[0]->nm_bairro)).'</option>';
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
            if(isset($dadosempresa)):
            echo'<option value="'.$dadosempresa[0]->logradouros_id.'" selected="selected">'.utf8_encode(strtoupper($dadosempresa[0]->nm_logradouro)).'</option>';
            else:
            echo'<option value="" >Aguardando...</option>';
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvrua'}"><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
     <label>
        <span>Complemento</span>
        <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
    </label>
    <label>
        <span>Numero</span>
        <input name="num" class=" w_100 uk-text-center" id="num"  value="<?php if(isset($dadosempresa)):echo $dadosempresa[0]->num; endif; ?>" maxlength="9" >
    </label>
   <label>
        <span>Cep</span>
        <div class="uk-form-icon">
                <i class="uk-icon-street-view"></i>
        <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadosvendedor)):echo tool::MascaraCampos("?????-???",$dadosvendedor[0]->cep); endif; ?>" maxlength="9" readonly >
        </div>
 <!--       <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>-->
    </label>
</fieldset>
</form>
<br />

<script type="text/javascript" src="assets/js/empresa.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>