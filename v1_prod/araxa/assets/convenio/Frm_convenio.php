<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

if(isset($_GET['conv_id'])){

    $FRM_convenio_id = isset( $_GET['conv_id'])     ? $_GET['conv_id'] : tool::msg_erros("Codigo do convênio Invalido.");


// recupera os dados da empresa
    $dadosconvenio=convenios::find_by_sql("SELECT
        convenios.*,
        logradouros.descricao as nm_logradouro,
        logradouros.cep,estados.id AS estado_id,
        estados.sigla AS nm_estado,
        cidades.id AS cidade_id,
        cidades.descricao AS nm_cidade,
        bairros.id AS bairro_id,
        bairros.descricao AS nm_bairro
        FROM convenios
        LEFT JOIN logradouros ON logradouros.id = convenios.logradouros_id
        LEFT JOIN estados ON estados.id = logradouros.estados_id
        LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
        LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
        WHERE convenios.id = '".$FRM_convenio_id."'");

    if($dadosconvenio[0]->status == 0){
        $st     ="Cancelado";
        $class  =" uk-badge-danger";
    }else{
        $st     ="Ativo";
        $class  ="";
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


<form method="post" id="FrmConvenio" class="uk-form uk-form-shadow " style="width: 900px;">
    <div id="menu-float" style="text-align:center;margin:0 900px;">

        <a  id="Btn_conv_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
        <hr class="uk-article-divider">

        <?php if(isset($dadosconvenio)):?>

            <a  id="Btn_conv_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Convenio" data-cached-title="Novo Convenio" ></a>
            <a  id="Btn_conv_3" class="uk-icon-button uk-icon-list" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Formas de Cobrança" data-cached-title="Formas de Cobrança" ></a>
            <a  id="Btn_conv_4" class="uk-icon-button uk-icon-list-alt" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Sub Convênios" data-cached-title="Sub Convênios" ></a>
            <a  id="Btn_conv_2" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" ></a>

            <?php
        else:
            ?>
            <a  id="Btn_conv_2" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" ></a>

        <?php endif;?>
    </div>

    <fieldset style="width:870px;">

        <legend>
            <i class="uk-icon-building " ></i> Dados do Convênio
        </legend>
        <label>
            <span>Codigo</span>
            <input value="<?php  if(isset($dadosconvenio)):
            echo tool::CompletaZeros("11",$dadosconvenio[0]->id); endif; ?>"  name="conv_id" type="text" class="input_text w_100 " readonly id="conv_id"  />
        </label>
        <label>
            <span>Representante</span>
            <select class="select w_400" name="vendedor"  id="vendedor" >
                <?php
                if(isset($dadosconvenio)):

                    $descricaovendedor=vendedores::find('all',array('conditions'=>array('status= ?','1')));
                    $descricao= new ArrayIterator($descricaovendedor);
                    while($descricao->valid()):

                        if($descricao->current()->id == $dadosconvenio[0]->vendedores_id){$select='selected="selected"';}else{$select="";}

                        echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($descricao->current()->nm_vendedor)).'</option>';

                        $descricao->next();
                    endwhile;
                else:
                    $descricaovendedor=vendedores::find('all',array('conditions'=>array('status= ?','1')));
                    $descricao= new ArrayIterator($descricaovendedor);
                    echo'<option value="" selected> Selecione um Representante</option>';
                    while($descricao->valid()):
                        echo'<option value="'.$descricao->current()->id.'" >'.utf8_encode(strtoupper($descricao->current()->nm_vendedor)).'</option>';
                        $descricao->next();
                    endwhile;
                endif;
                ?>
            </select>
        </label>
        <label >
            <span>Status</span>
            <div class="uk-form-controls">
                <label >
                    <input type="radio"  name="st" value="1" <?php  if(isset($dadosconvenio)){if($dadosconvenio[0]->status == "1"){echo"checked";} }?> >
                    <?php  if(isset($dadosconvenio) && $dadosconvenio[0]->status == 1): ?><div class="uk-badge uk-badge-primary>">Ativo</div> <?php else: echo "Ativo"; endif; ?>
                    <input type="radio"  name="st" value="0" <?php  if(isset($dadosconvenio)){if($dadosconvenio[0]->status == "0"){echo"checked";} }?> >
                    <?php  if(isset($dadosconvenio) && $dadosconvenio[0]->status == 0): ?><div class="uk-badge uk-badge-danger">Inativo</div> <?php else: echo "Inativo"; endif; ?>
                
                </label>
            </div>
        </label>
        <label >
            <span>Aplicar limite</span>
            <div class="uk-form-controls">
                <label >
                    <input type="radio"  name="lt_uso" value="1" <?php  if(isset($dadosconvenio)){if($dadosconvenio[0]->limite_uso == "1"){echo"checked";} }?> >
                    <?php  if(isset($dadosconvenio) && $dadosconvenio[0]->limite_uso == 1): ?><div class="uk-badge uk-badge-primary>">Sim</div> <?php else: echo "Ativo"; endif; ?>
                    <input type="radio"  name="lt_uso" value="0" <?php  if(isset($dadosconvenio)){if($dadosconvenio[0]->limite_uso == "0"){echo"checked";} }?> >
                    <?php  if(isset($dadosconvenio) && $dadosconvenio[0]->limite_uso == 0): ?><div class="uk-badge uk-badge-danger">Não</div> <?php else: echo "Inativo"; endif; ?>
                
                </label>
            </div>
        </label>
        <label>
            <span>Razão Social</span>
            <input value="<?php  if(isset($dadosconvenio)):
            echo utf8_encode($dadosconvenio[0]->razao_social); endif; ?>"  name="rzsc" type="text" class="uk-text-left w_400 " id="rzsc"  />
        </label>
        <label>
            <span>Nome Fantasia</span>
            <input value="<?php  if(isset($dadosconvenio)):
            echo utf8_encode($dadosconvenio[0]->nm_fantasia); endif; ?>"  name="nmfant" type="text" class="uk-text-left w_400 " id="nmfant"  />
        </label>
        <label>
            <span>Cnpj</span>
            <input value="<?php  if(isset($dadosconvenio)):echo $dadosconvenio[0]->cnpj; endif; ?>"  name="cnpj" type="text" class="uk-text-center w_150 " id="cnpj"  />
        </label>
        <label>
            <span>Insc.Mun</span>
            <input value="<?php if(isset($dadosconvenio)):echo $dadosconvenio[0]->im; endif; ?>" name="im" type="text" class=" w_100 uk-text-center" id="im"  />
        </label>
        <label>
            <span>Insc.Est</span>
            <input value="<?php if(isset($dadosconvenio)):echo $dadosconvenio[0]->ie; endif; ?>" name="ie" type="text" class=" w_100 uk-text-center" id="ie"  />
        </label>
        <label>
            <span>Fone fixo</span>
            <div class="uk-form-icon">
                <i class="uk-icon-fax"></i>
                <input name="fn_fx" type="text" class="uk-text-center w_150  " id="fn_fx"  value="<?php if(isset($dadosconvenio)):
                echo tool::MascaraCampos("(??) ????-????",$dadosconvenio[0]->fone_fixo); endif; ?>" >
            </div>
        </label>
        <label>
            <span>Fone celular</span>
            <div class="uk-form-icon">
                <i class="uk-icon-phone"></i>
                <input name="fn_cel" type="text" class="uk-text-center w_150  " id="fn_cel"  value="<?php if(isset($dadosconvenio)):echo tool::MascaraCampos("(??) ?????-????",$dadosconvenio[0]->fone_cel); endif; ?>" >
            </div>
        </label>
        <label>
            <span>E-mail</span>
            <div class="uk-form-icon">
                <i class="uk-icon-envelope"></i>
                <input name="email" type="email" class="uk-text-left w_400" id="email" value="<?php if(isset($dadosconvenio)):echo $dadosconvenio[0]->email; endif; ?>">
            </div>
        </label>
        <label>
            <span>Contato</span>
            <div class="uk-form-icon">
                <i class="uk-icon-user"></i>
                <input name="ct" type="text" class="uk-text-left w_400" id="ct" value="<?php if(isset($dadosconvenio)):echo $dadosconvenio[0]->contato; endif; ?>">
            </div>
        </label>
        <legend>
            <i class="uk-icon-barcode " ></i> Detalhes Cobrança
        </legend>
        <label >
            <span>Tipo Cobrança</span>
            <div class="uk-form-controls">
                <label >
                    <input type="radio"  name="tp_convenio" value="J" <?php  if(isset($dadosconvenio)){if($dadosconvenio[0]->tipo_convenio == "J"){echo"checked";} }?> > Juridica
                    <input type="radio"  name="tp_convenio" value="F" <?php  if(isset($dadosconvenio)){if($dadosconvenio[0]->tipo_convenio == "F"){echo"checked";} }?> >  Fisica
                </label>
            </div>
        </label>
        <label>
            <span>Tx Adesão</span>
            <input type="text"  class="input_text w_120 uk-text-center" name="tx_adesao" id="tx_adesao" value="<?php  if(isset($dadosconvenio)): 
            echo number_format($dadosconvenio[0]->tx_adesao,2,',','.'); else: echo "0,00"; endif; ?>"   />
        </label>
        <label>
            <span>Tx Parcelamento</span>
            <input type="text"  class="input_text w_120 uk-text-center" name="tac_faturar" id="tac_faturar" value="<?php  if(isset($dadosconvenio)): 
            echo $dadosconvenio[0]->tac_faturar; else: echo "0,00"; endif; ?>"   /> %
        </label>
        <legend style="margin-bottom: 5px;">
            <i class="uk-icon-street-view " ></i> Endereço
        </legend>

        <label>
            <span>Estado</span>
            <select class="select w_400" name="uf"  id="uf" >
                <?php
                if(isset($dadosconvenio)):

                 $descricaoestados=estados::find("all");
                 $descricao= new ArrayIterator($descricaoestados);
                 while($descricao->valid()):
                    if($descricao->current()->id == $dadosconvenio[0]->estado_id){$select='selected="selected"';}else{$select="";}
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
            if(isset($dadosconvenio)):
                echo'<option value="'.$dadosconvenio[0]->cidade_id.'" selected="selected">'.utf8_encode(strtoupper($dadosconvenio[0]->nm_cidade)).'</option>';
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
            if(isset($dadosconvenio)):
                echo'<option value="'.$dadosconvenio[0]->bairro_id.'" selected="selected">'.utf8_encode(strtoupper($dadosconvenio[0]->nm_bairro)).'</option>';
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
            if(isset($dadosconvenio)):
                echo'<option value="'.$dadosconvenio[0]->logradouros_id.'" selected="selected">'.utf8_encode(strtoupper($dadosconvenio[0]->nm_logradouro)).'</option>';
            else:
                echo'<option value="" >Aguardando...</option>';
            endif;
            ?>
        </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvrua'}"><i class="uk-icon-plus"></i> Adcionar</button>
    </label>
    <label>
        <span>Complemento</span>
        <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadosconvenio)):echo $dadosconvenio[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
    </label>
    <label>
        <span>Numero</span>
        <input name="num" class=" w_100 uk-text-center" id="num"  value="<?php if(isset($dadosconvenio)):echo $dadosconvenio[0]->num; endif; ?>" maxlength="9" >
    </label>
    <label>
        <span>Cep</span>
        <div class="uk-form-icon">
            <i class="uk-icon-street-view"></i>
            <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadosvendedor)):echo tool::MascaraCampos("?????-???",$dadosvendedor[0]->cep); endif; ?>" maxlength="9"  readonly>
        </div>
        <!--       <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>-->
    </label>
</fieldset>
</form>
<br />

<script type="text/javascript" src="assets/js/convenio.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>