<?php
require_once"../../sessao.php";

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if(isset($_GET['par_id'])){

    $FRM_parceiro_id = isset( $_GET['par_id'])     ? $_GET['par_id'] : tool::msg_erros("Codigo de parceiro Invalido.");




// recupera os dados do associado
    $dadosparceiro=med_parceiros::find_by_sql("SELECT
      med_parceiros.*,
      logradouros.descricao as nm_logradouro,
      logradouros.cep,
      estados.id AS estado_id,
      estados.sigla AS nm_estado,
      cidades.id AS cidade_id,
      cidades.descricao AS nm_cidade,
      bairros.id AS bairro_id,
      bairros.descricao AS nm_bairro
      FROM
      med_parceiros
      INNER JOIN logradouros ON logradouros.id = med_parceiros.logradouros_id
      INNER JOIN estados ON estados.id = logradouros.estados_id
      INNER JOIN cidades ON cidades.id = logradouros.cidades_id
      INNER JOIN bairros ON bairros.id = logradouros.bairros_id
      WHERE
      med_parceiros.id = '".$FRM_parceiro_id."'");



    if($dadosparceiro[0]->status == 0){
       $st		="Cancelado";
       $class	=" uk-badge-danger";
   }else{
       $st		="Ativo";
       $class	="";
   }
}
?>

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


<form method="post" id="FrmParceiro" class="uk-form uk-form-shadow " style="width: 900px;">
    <div id="menu-float" style="text-align:center;margin:0 900px;">

    <a  id="Btn_par_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
    <hr id="Btn_par_0_divider" class="uk-article-divider">

    <?php if(isset($dadosparceiro)):?>
    <a  id="Btn_par_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Parceiro" data-cached-title="Novo Parceiro" ></a>
    <a  id="Btn_par_2" class="uk-icon-button uk-icon-list" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Especialidades" data-cached-title="Especialidades" ></a>
    <a  id="Btn_par_3" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" ></a>
    <?php else: ?>
    <a  id="Btn_par_3" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" ></a>
    <?php endif; ?>
</div>

<fieldset style="width:870px;">

    <legend>
        <i class="uk-icon-building" ></i> Dados do Parceiro
    </legend>
    <label>
        <span>Codigo</span>
        <input value="<?php  if(isset($dadosparceiro)):
        echo tool::CompletaZeros("11",$dadosparceiro[0]->id); endif; ?>"  name="par_id" type="text" class="w_100 " readonly id="par_id"  />
    </label>

    <label >
      <span>Status</span>
      <div class="uk-form-controls">
      <label >
        <input type="radio"  name="st" value="1" <?php  if(isset($dadosparceiro)){if($dadosparceiro[0]->status == "1"){echo"checked";} }?> > Ativo
        <input type="radio"  name="st" value="0" <?php  if(isset($dadosparceiro)){if($dadosparceiro[0]->status == "0"){echo"checked";} }?> > Inativo
      </label>
      </div>
    </label>
    
    <label>
        <span>Pessoa (PJ/PF)</span>
        <select name="tp_parceiro" class="select w_120" id="tp_parceiro">
            <?php
            if(isset($dadosparceiro)){

                if($dadosparceiro[0]->tp_parceiro == "F"){

                    echo'<option value="F" selected>P.Fisica</option>';
                    echo'<option value="J">P.Juridica</option>';

                }else{
                    echo'<option value="F">P.Fisica</option>';
                    echo'<option value="J" selected>P.Juridica</option>';

                }
            }else{
                echo'<option value="" selected></option>';echo'<option value="F">P.Fisica</option>';echo'<option value="J" >P.Juridica</option>';
            }
            ?>
        </select>
    </label>

<!-- pessoa fisica -->

    <label class="labpf">
        <span>Nome</span>
        <input value="<?php  if(isset($dadosparceiro)):
        echo $dadosparceiro[0]->nm_parceiro; endif; ?>"  name="nm_parceiro" type="text" class="w_400 " id="nm_parceiro"  />
    </label>
        <label class="labpf">
        <span>Doumentos</span>
        <input value="<?php  if(isset($dadosparceiro)):echo $dadosparceiro[0]->cpf; endif; ?>"  name="cpf" type="text" class=" w_150 uk-text-center" id="cpf" placeholder="CPF" />
        <input value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->rg; endif; ?>" name="rg" type="text" class=" w_150 uk-text-center" id="rg"  placeholder="IDENTIDADE"/>
        <input  value="<?php if(isset($dadosparceiro)):$now = new ActiveRecord\DateTime($dadosparceiro[0]->dt_nascimento);echo $now->format('d/m/Y'); endif; ?>"  type="text" class=" w_150 uk-text-center" name="data_nasc" id="data_nasc" placeholder="DATA NASCIMENTO"/>
        <?php
        if(isset($dadosassociado)):
            $idde=tool::CalcularIdade($now->format("Y-m-d"),date("Y-m-d"));
            echo'<div class="uk-badge uk-badge-warning">( '.$idde.' ) Anos</div>';
        endif;
        ?>
    </label>
    <label class="labpf"> 
        <span>Classe Med</span> 
        <select  class="select "  name="classe" id="classe" >
        <?php
        if(isset($dadosparceiro)):

        med_parceiros::ClasseMedica($dadosparceiro[0]->classe);

        else:
        med_parceiros::ClasseMedica("");
        endif;
              
        ?>          
        </select>
        Nº <input value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->numclasse; endif; ?>"   type="text" class="input_text w_100 center" name="numclasse" id="numclasse"/>
    </label>

<!-- pessoa juridica -->

    <label class="labpj">
        <span>Razão Social</span>
        <input value="<?php  if(isset($dadosparceiro)):
        echo $dadosparceiro[0]->razao_social; endif; ?>"  name="rzsc" type="text" class="w_400 " id="rzsc"  />
    </label>
    <label class="labpj">
        <span>Nome Fantasia</span>
        <input value="<?php  if(isset($dadosparceiro)):
        echo $dadosparceiro[0]->nm_fantasia; endif; ?>"  name="nmfant" type="text" class="uk-text-left w_400 " id="nmfant"  />
    </label>
    <label class="labpj">
        <span>Documentos</span>
        <input value="<?php  if(isset($dadosparceiro)):echo $dadosparceiro[0]->cnpj; endif; ?>"  name="cnpj" type="text" class=" w_150 uk-text-center" id="cnpj"  placeholder="CNPJ"/>
        <input value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->ie; endif; ?>" name="ie" type="text" class=" w_150 uk-text-center" id="ie" placeholder="INSC ESTADUAL" />
        <input value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->im; endif; ?>" name="im" type="text" class=" w_150 uk-text-center" id="im" placeholder="INSC MUNICIPAL" />
    </label>
    <!-- define pj pf hide e show label -->
    <script type="text/javascript">
        <?php
        if(isset($dadosparceiro)){
            if($dadosparceiro[0]->tp_parceiro == "F"){
                echo 'jQuery(".labpf").show();jQuery(".labpj").hide();';
            }else{
                echo 'jQuery(".labpf").hide();jQuery(".labpj").show();';
            }
        }else{echo 'jQuery(".labpf").show();jQuery(".labpj").hide();';}
        ?>
    </script>
    <label>
        <span>Telefones</span>
        <div class="uk-form-icon">
            <i class="uk-icon-fax"></i>
            <input name="fn_fx" type="text" class=" w_150 uk-text-center " id="fn_fx"  value="<?php if(isset($dadosparceiro)):
            echo tool::MascaraCampos("(??) ????-????",$dadosparceiro[0]->fone1); endif; ?>" placeholder="FIXO">
        </div>
        <div class="uk-form-icon">
            <i class="uk-icon-phone"></i>
            <input name="fn_cel" type="text" class=" w_150 uk-text-center " id="fn_cel"  value="<?php if(isset($dadosparceiro)):echo tool::MascaraCampos("(??) ?????-????",$dadosparceiro[0]->fone2); endif; ?>"" placeholder="CELULAR">
        </div>
    </label>
    <label>
        <span>E-mail / Website</span>
        <div class="uk-form-icon">
            <i class="uk-icon-envelope"></i>
            <input name="email" type="text" class="uk-text-left w_350" id="email" value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->email; endif; ?>">
        </div>
        <div class="uk-form-icon">
            <i class="uk-icon-sitemap"></i>
            <input name="wbst" type="text" class="uk-text-left w_350" id="wbst" value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->website; endif; ?>">
        </div>
    </label>
    <label>
        <span>Contato</span>
        <div class="uk-form-icon">
            <i class="uk-icon-user"></i>
            <input name="ct" type="text" class="uk-text-left w_400" id="ct" value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->contato; endif; ?>">
        </div>
    </label>
    <legend style="margin-bottom: 5px;">
        <i class="uk-icon-street-view " ></i> Endereço
    </legend>

    <label>
        <span>Estado</span>
        <select class="select w_400" name="uf"  id="uf" >
            <?php
            if(isset($dadosparceiro)):

             $descricaoestados=estados::find("all");
             $descricao= new ArrayIterator($descricaoestados);
             while($descricao->valid()):
                if($descricao->current()->id == $dadosparceiro[0]->estado_id){$select='selected="selected"';}else{$select="";}
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
        if(isset($dadosparceiro)):
            echo'<option value="'.$dadosparceiro[0]->cidade_id.'" selected="selected">'.utf8_encode(strtoupper($dadosparceiro[0]->nm_cidade)).'</option>';
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
        if(isset($dadosparceiro)):
            echo'<option value="'.$dadosparceiro[0]->bairro_id.'" selected="selected">'.utf8_encode(strtoupper($dadosparceiro[0]->nm_bairro)).'</option>';
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
        if(isset($dadosparceiro)):
            echo'<option value="'.$dadosparceiro[0]->logradouros_id.'" selected="selected">'.utf8_encode(strtoupper($dadosparceiro[0]->nm_logradouro)).'</option>';
        else:
            echo'<option value="" >Aguardando...</option>';
        endif;
        ?>
    </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvrua'}"><i class="uk-icon-plus"></i> Adcionar</button>
</label>
<label>
    <span>Complemento</span>
    <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
</label>
<label>
    <span>Numero / CEP</span>
    <input name="num" class=" w_100 uk-text-center" id="num"  value="<?php if(isset($dadosparceiro)):echo $dadosparceiro[0]->num; endif; ?>" maxlength="9" >
    <div class="uk-form-icon">
        <i class="uk-icon-street-view"></i>
        <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadosparceiro)):echo tool::MascaraCampos("?????-???",$dadosparceiro[0]->cep); endif; ?>" maxlength="9"  readonly >
    </div>
    <!--       <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>-->
</label>
</fieldset>
<fieldset style="width:870px;">
<legend><i class="uk-icon-barcode " ></i> Dados do Faturamento</legend>
<label> 
    <span>Prazo / dia </span> 
    <select  class="select "  name="prz_pgto" id="prz_pgto" >
        <?php
            if(isset($dadosparceiro)){
                if($dadosparceiro[0]->prz_pgto=="0"){
                    echo'
                     <option value="0" selected>Mesmo dia</option>
                     <option value="7">7 dias</option>
                     <option value="15">15 dias</option>
                     <option value="30">30 dias</option>
                        ';}elseif($dadosparceiro[0]->prz_pgto=="7"){
                    echo'
                     <option value="0" >Mesmo dia</option>
                     <option value="7" selected>7 dias</option>
                     <option value="15">15 dias</option>
                     <option value="30">30 dias</option>
                        ';}elseif($dadosparceiro[0]->prz_pgto=="15"){
                    echo'
                     <option value="0" >Mesmo dia</option>
                     <option value="7">7 dias</option>
                     <option value="15" selected>15 dias</option>
                     <option value="30">30 dias</option>
                        ';}elseif($dadosparceiro[0]->prz_pgto=="30"){
                    echo'
                     <option value="0" >Mesmo dia</option>
                     <option value="7">7 dias</option>
                     <option value="15">15 dias</option>
                     <option value="30" selected>30 dias</option>
                        ';}
            }else{
                echo'
                <option value="1" selected="selected">Selecionar</option>
                <option value="0">Mesmo dia</option>
                <option value="7">7 dias</option>
                <option value="15">15 dias</option>
                <option value="30">30 dias</option>
                ';}
        ?>
     </select>
    <input name="dia_venc" class="input_text w_100"id="dia_venc"  value="<?php if(isset($dadosparceiro)):
    echo $dadosparceiro[0]->dia_venc; endif; ?>" maxlength="9" placeholder="DIA">
</label>
<label> 
    <span>Local de pgto </span> 
    <select  class="select w_200"  name="local_pgto" id="local_pgto" >
        <?php
            if(isset($dadosparceiro)){
                if($dadosparceiro[0]->local_pgto=="0"){
                    echo'
                        <option value="0" selected="selected">Pagamento direto</option>
                        <option value="1">Pagamento faturado</option>
                        ';}elseif($dadosparceiro[0]->local_pgto=="1"){
                    echo'
                        <option value="0" selected="selected">Pagamento direto</option>
                        <option value="1" selected="selected">Pagamento faturado</option>
                    ';}
            }else{
                echo'
                <option value="0" selected="selected">Pagamento direto</option>
                <option value="1">Pagamento faturado</option>

                ';}
        ?>
     </select>
</label>
</fieldset>
</form>
<br />

<script type="text/javascript" src="assets/js/parceiro.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>