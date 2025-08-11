<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if(isset($_GET['vendor_id'])){

// recupera os dados do associado
$dadosvendedor= vendedores::find_by_sql("SELECT
								SQL_CACHE vendedores.*,
										  logradouros.descricao as nm_logradouro,
										  logradouros.cep,
										  estados.id AS estado_id,
										  estados.sigla AS nm_estado,
										  cidades.id AS cidade_id,
										  cidades.descricao AS nm_cidade,
										  bairros.id AS bairro_id,
										  bairros.descricao AS nm_bairro
										FROM
										  vendedores
										  INNER JOIN logradouros ON logradouros.id = vendedores.logradouros_id
										  INNER JOIN estados ON estados.id = logradouros.estados_id
										  INNER JOIN cidades ON cidades.id = logradouros.cidades_id
										  INNER JOIN bairros ON bairros.id = logradouros.bairros_id

										WHERE
										  vendedores.id = ".$_GET['vendor_id']."");


if($dadosvendedor[0]->status == 0){
					$st='<div class="uk-badge uk-badge-danger">Inativo</div>';
                }else{
					$st='<div class="uk-badge uk-badge-success">Ativo </div>';
                    }
}else{$st="";}


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


<form method="post" id="Frmvendedor" class="uk-form uk-form-shadow " style="width: 900px;">
<div id="menu-float" style="text-align:center;margin:0 900px;">

	<a  id="Btn_vendor_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" >
    </a>
<hr class="uk-article-divider">

<?php if(isset($dadosvendedor)):?>

    <a  id="Btn_vendor_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Vendedor" data-cached-title="Novo Vendedor" ></a>
    <a  id="Btn_vendor_2" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" >
    </a>
<?php
else:
?>
    <a  id="Btn_vendor_2" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" >
    </a>

<?php endif;
?>
</div>

<fieldset style="width:870px;">
    <legend>
        <i class="uk-icon-user " ></i> Dados do Vendedor
    </legend>
    <label>
		<span>codigo</span>

        <input name="vendor_id" type="text" class=" center w_80 "  id="vendor_id"  value="<?php  if(isset($dadosvendedor)): echo tool::CompletaZeros(3,$dadosvendedor[0]->id); endif; ?>" readonly="readonly" >
 	 	<?php echo $st;  ?>
    </label>
	<label>
    	<span>Nome completo</span>
        <input value="<?php if(isset($dadosvendedor)){echo $dadosvendedor[0]->nm_vendedor;}else{} ?>" type="text" class=" w_400" name="nome" id="nome"  />
    </label>

     <label>
        <span>Telefones</span>
       <input type="text" class="dadosconta w_100 uk-text-center" value="<?php if(isset($dadosvendedor)):echo tool::MascaraCampos("??-????-????",$dadosvendedor[0]->fone_fixo); endif; ?>" name="fone_fixo" id="fone_fixo" placeholder="Residencia"/>
        <input type="text" class=" w_120 uk-text-center" value="<?php if(isset($dadosvendedor)):echo tool::MascaraCampos("??-?????-????",$dadosvendedor[0]->fone_cel); endif; ?>" name="fone_cel" id="fone_cel" placeholder="Celular"/>
        <a id="Btn_Sms" title="Enviar SMS" onClick="New_window('500','250','Envio de Mensagem','sms/Fr_SMS_Avulso.php?fone=<?php echo $dadosvendedor[0]->fone_cel; ?>&msg=<?php echo base64_encode(""); ?>');"><i class="icon-chat" ></i></a>
    </label>
	<label>
        <span>Documento(CPF)</span>
        <input value="<?php if(isset($dadosvendedor)):echo tool::MascaraCampos("???.???.???-??",$dadosvendedor[0]->cpf); endif; ?>"type="text" class=" w_120 uk-text-center" name="cpf" id="cpf" placeholder="000.000.000-00"/>
    </label>
	<label>
		<span>Identidade(RG)</span>
        <input value="<?php if(isset($dadosvendedor)):echo $dadosvendedor[0]->rg; endif; ?>" type="text" class="uk-text-left w_100" name="rg" id="rg" />
	</label>
	<label>
		<span>Orgão Emissor</span>
		<input value="<?php if(isset($dadosvendedor)):echo $dadosvendedor[0]->orgao_emissor_rg; endif; ?>"   type="text" class=" w_100 uk-text-center" name="orgao_emissor_rg" id="orgao_emissor_rg" />
    </label>
    <label >
    <span>Sexo</span>
        <div class="uk-form-controls">
        <label >
        <input type="radio"  name="sexo" value="M" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->sexo == "M"){echo"checked";} }?> > Masculino
        <input type="radio"  name="sexo" value="F" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->sexo == "F"){echo"checked";} }?> > Feminino
        </label>
        </div>
    </label>
    <label >
    <span>Estado civil</span>
        <div class="uk-form-controls">
        <label >
            <input type="radio"  name="estado_civil" value="C" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->estado_civil == "C"){echo"checked";} }?> > Casado (a)
            <input type="radio"  name="estado_civil" value="S" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->estado_civil == "S"){echo"checked";} }?> > Solteiro(a)
            <input type="radio"  name="estado_civil" value="V" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->estado_civil == "V"){echo"checked";} }?> > Viuvo(a)
            <input type="radio"  name="estado_civil" value="A" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->estado_civil == "A"){echo"checked";} }?> > Amasiado(a)
            <input type="radio"  name="estado_civil" value="D" <?php  if(isset($dadosvendedor)){if($dadosvendedor[0]->estado_civil == "D"){echo"checked";} }?> > Divorciado(a)
        </label>
        </div>
    </label>
    <label>
        <span>e-mail</span>
        <input type="text" class="uk-text-left w_400" value="<?php if(isset($dadosvendedor)):echo $dadosvendedor[0]->email; endif; ?>" name="email" id="email"/>
    </label>

    <legend style="margin-bottom: 5px;">
        <i class="uk-icon-street-view " ></i> Endereço
    </legend>
    <label>
        <span>Cep</span>
        <div class="uk-form-icon">
            <i class="uk-icon-street-view"></i>
            <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadosvendedor)):echo tool::MascaraCampos("?????-???",$dadosvendedor[0]->cep); endif; ?>" maxlength="9" >
        </div>
       <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>
    </label>
    <label>
        <span>Estado</span>
        <select class="select w_400" name="uf"  id="uf" >
            <?php
            if(isset($dadosvendedor)):

                   $descricaoestados=estados::find("all");
                    $descricao= new ArrayIterator($descricaoestados);
                    echo'<option value="0" >Selecione um estado</option>';
                    while($descricao->valid()):
                        if($descricao->current()->id == $dadosvendedor[0]->estado_id){$select='selected="selected"';}else{$select="";}
                        echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.strtoupper(($descricao->current()->descricao)).'</option>';
                    $descricao->next();
                    endwhile;
            else:
                echo'<option value="" >Selecione o estado</option>';
                $descricaoestados=estados::find('all');
                $descricao= new ArrayIterator($descricaoestados);
                while($descricao->valid()):
                    echo'<option value="'.$descricao->current()->id.'" >'.strtoupper(($descricao->current()->descricao)).'</option>';
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
            if(isset($dadosvendedor)):
            echo'<option value="'.$dadosvendedor[0]->cidade_id.'" selected="selected">'.(strtoupper($dadosvendedor[0]->nm_cidade)).'</option>';
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
            if(isset($dadosvendedor)):
            echo'<option value="'.$dadosvendedor[0]->bairro_id.'" selected="selected">'.(strtoupper($dadosvendedor[0]->nm_bairro)).'</option>';
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
            if(isset($dadosvendedor)):
            echo'<option value="'.$dadosvendedor[0]->logradouros_id.'" selected="selected">'.(strtoupper($dadosvendedor[0]->nm_logradouro)).'</option>';
            else:
            echo'<option value="" >Aguardando...</option>';
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvrua'}"><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
     <label>
        <span>Complemento</span>
        <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadosvendedor)):echo $dadosvendedor[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
    </label>
    <label>
        <span>Numero</span>
        <input name="num" class=" w_100 uk-text-center" id="num"  value="<?php if(isset($dadosvendedor)):echo $dadosvendedor[0]->num; endif; ?>" maxlength="9" >
    </label>

</fieldset>
</form>
<br>
<script type="text/javascript" src="assets/js/vendedor.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>