<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');


if(isset($_GET['matricula'])){

// recupera os dados do associado
    $dadosassociado= associados::find_by_sql("SELECT
        SQL_CACHE associados.*,
        logradouros.descricao as nm_logradouro,
        logradouros.cep,
        estados.id AS estado_id,
        estados.sigla AS nm_estado,
        cidades.id AS cidade_id,
        cidades.descricao AS nm_cidade,
        bairros.id AS bairro_id,
        bairros.descricao AS nm_bairro,
        convenios.nm_fantasia as nm_convenio,
        usuarios.login as nm_usuario,
        vendedores.nm_vendedor as nm_vendedor,
        empresas.cnpj
        FROM
        associados
        LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
        LEFT JOIN estados ON estados.id = logradouros.estados_id
        LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
        LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
        LEFT JOIN usuarios ON usuarios.id = associados.usuarios_id
        LEFT JOIN convenios ON convenios.id = associados.convenios_id
        LEFT JOIN vendedores ON vendedores.id = associados.vendedores_id
        LEFT JOIN empresas ON empresas.id = associados.empresas_id
        WHERE
        associados.matricula = ".$_GET['matricula']."");

// verificamos se esta assegurado e exibimos na tela
    $Query_Segurado=seguros::find_by_matricula_and_referencia($_GET['matricula'],(date("Y-m")."-01"));



// verifica se existe subconvenio para o convenio


// valida 9 digito do celular
    if(strlen(tool::LimpaString($dadosassociado[0]->fone_cel)) == "10"){

        $fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($dadosassociado[0]->fone_cel),0,2)."0".substr(tool::LimpaString($dadosassociado[0]->fone_cel),2,8));

    }else{

        $fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($dadosassociado[0]->fone_cel),0,2)." ".substr(tool::LimpaString($dadosassociado[0]->fone_cel),2,9));
    }

// VALIDA A REFERENCIA DO MES ATUAL DO SEGURO
    if($Query_Segurado){

        $back_st=$Query_Segurado->status;

        if($back_st == 0 ){
            $msg_seguro    = "Associado não assegurado em: ".date("m/Y")."";
            $syle_msg      = "warning";
        }elseif($back_st == 1){
            $msg_seguro    = "Associado reincluido no seguro/auxilio en: ".date("m/Y")."";
            $syle_msg      = "success";
        }elseif($back_st == 2){
            $msg_seguro    = "Associado sem movimentação para seguro/auxilio.";
            $syle_msg      = "primary";
        }elseif($back_st == 3){
            $msg_seguro    = "Associado removido do seguro/auxilio em ".date("m/Y")."";
            $syle_msg      = "danger";
        }
    }else{
        $msg_seguro='Associado não possui bonificações.';
        $syle_msg="";
    }

    if($dadosassociado[0]->status == 0){
        $dt_cancel = new ActiveRecord\DateTime($dadosassociado[0]->dt_cancelamento);
        $st='<div class="uk-badge uk-badge-danger">Contrato cancelado em : '.$dt_cancel->format('d/m/Y').'</div>';
    }else{
     $st='<div class="uk-badge uk-badge-success">Ativo </div> <div class="uk-badge uk-badge-'.$syle_msg.'">'.$msg_seguro.'</div>';
 }
 if($dadosassociado[0]->spc == 1){
    $spc='<div class="uk-badge uk-badge-warning">Registrado no SPC</div>';
}else{$spc='';}

/* recuperamos a matricula atual para acionar o botoes avançar retroceder*/
if($_GET['matricula'] !="1"){
    $mat["prev"] = ($_GET['matricula']-1);
    $mat["next"] =($_GET['matricula']+1);
}else{
    $mat["prev"] = 1;
    $mat["next"] =($_GET['matricula']+1);
}


}else{$st="";$spc='';}


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



<form method="post" id="FrmAssociado" class="uk-form uk-form-shadow " style="width: 900px;">
    <div id="menu-float" style="text-align:center;margin:0 900px;">

       <a  id="Btn_assoc_0" class="uk-icon-button uk-icon-search" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
       <hr class="uk-article-divider">

       <?php if(isset($dadosassociado)){ ?>

        <a  id="Btn_assoc_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Novo Associado" data-cached-title="Novo Associado" ></a>
        <a  id="Btn_assoc_13" class="uk-icon-button uk-icon-list-alt" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="AUtorização de consulta" data-cached-title="Autorização de consulta" ></a>
        <a  id="Btn_assoc_2" class="uk-icon-button uk-icon-users" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Dependentes" data-cached-title="Dependentes" > </a>
        <a  id="Btn_assoc_3" class="uk-icon-button uk-icon-table" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Faturamento Associado" data-cached-title="Faturamento Associado" > </a>
        <a  id="Btn_assoc_4" class="uk-icon-button uk-icon-list-ul" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Historico Associado" data-cached-title="Historico Associado" > </a>
        <!--<a  id="Btn_assoc_5" class="uk-icon-button uk-icon-mobile uk-icon-medium" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Historico SMS " data-cached-title="Historico SMS " >    </a>-->
        <?php if($COB_Acesso_Id == 3 || $COB_Acesso_Id == 6 ){    ?>
            <?php if($dadosassociado[0]->spc == 0){    ?>
                <a  id="Btn_assoc_11" class="uk-icon-button uk-icon-lock" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Registrar spc" data-cached-title="Registrar spc" > </a>
            <?php }else{ ?>
                <a  id="Btn_assoc_12" class="uk-icon-button uk-icon-unlock" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Remover spc" data-cached-title="Remover spc" > </a>
            <?php } ?>
        <?php } ?>

        <?php if($dadosassociado[0]->status == 1){    ?>

            <a  id="Btn_assoc_6" class="uk-icon-button uk-icon-file-pdf-o" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Contrato de Adesão" data-cached-title="Contrato de Adesão" ></a>
            <a  id="Btn_assoc_8" class="uk-icon-button uk-icon-remove" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Cancelar Contrato" data-cached-title="Cancelar Contrato" > </a>

        <?php }else{ ?>

            <a  id="Btn_assoc_7" class="uk-icon-button uk-icon-file-text-o" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Termo de cancelamento" data-cached-title="Termo de Cancelamento" ></a>
            <a  id="Btn_assoc_10" class="uk-icon-button uk-icon-check" style="margin-top:2px;" data-uk-tooltip="{pos:'left'}" title="Reativar Contrato" data-cached-title="Reativar Contrato" > </a>

        <?php } ?>

        <a  id="Btn_assoc_9" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações"> </a>
    <?php }else{ ?>
        <a  id="Btn_assoc_9" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Novo" data-cached-title="Gravar Novo" ></a>
    <?php } ?>

</div>

<fieldset style="width:870px;">

    <?php if( isset($dadosassociado) ){ 

        if( $COB_Acesso_Id != 5 && $COB_Acesso_Id != 4 ){
            ?>

            <div style="width: 70px; height: 31px; float: right; background-color:transparent; margin-top: -44px;">

                <a href="javascript:void(0)" style="float: left; color: #fff; font-weight: bold;" onclick="PrevNext('<?php echo $mat["prev"]; ?>');"><i class="uk-icon-angle-double-left uk-icon-large" data-uk-tooltip="{pos:'left'}" title="Anterior" data-cached-title="Anterior"></i> </a>
                <a href="javascript:void(0)" style="float: right; color: #fff; font-weight: bold;" onclick="PrevNext('<?php echo $mat["next"]; ?>');"><i class="uk-icon-angle-double-right uk-icon-large" data-uk-tooltip="{pos:'left'}" title="Proximo" data-cached-title="Proximo"></i> </a>

            </div>

        <?php }} ?>

        <legend>
            <i class="uk-icon-user " ></i> Dados do Associado
        </legend>

        <label>
          <span>Identificador</span>
          <?php if(isset($dadosassociado)){
             $compl= tool::CompletaZeros(3,$dadosassociado[0]->empresas_id)." / ".
             tool::CompletaZeros(3,$dadosassociado[0]->convenios_id)." / ";
         }
         ?>
         <input  type="text" class="uk-text-center w_100"  value="<?php  if(isset($dadosassociado)): echo $compl; endif; ?>" readonly="readonly" >
         <input name="matricula" type="text" class=" uk-text-center w_80 "  id="matricula"  value="<?php  if(isset($dadosassociado)): echo tool::CompletaZeros(10,$dadosassociado[0]->matricula); endif; ?>" readonly="readonly" >
         <?php echo $st;  ?>
         <?php echo $spc;  ?>
     </label>
     <!-- -->
     <label>
        <span>Data Contrato</span>
        <input  value="<?php if(isset($dadosassociado)):$now = new ActiveRecord\DateTime($dadosassociado[0]->dt_cadastro);echo $now->format('d/m/Y'); else: echo date("d/m/Y"); endif; ?>"  type="text" class=" w_100 uk-text-center" name="dt_cadastro" id="dt_cadastro" readonly="readonly">
        <?php
        if(isset($dadosassociado)):
            $idde=tool::CalcularIdade($now->format("Y-m-d"),date("Y-m-d"));
            echo'<div class="uk-badge uk-badge-warning">( '.$idde.' ) Anos</div>';
        endif;
        ?>
    </label>
    <!-- -->
    <label>
       <span>Nome completo</span>
       <input value="<?php if(isset($dadosassociado)){echo $dadosassociado[0]->nm_associado;}else{} ?>" type="text" class="w_400" name="nome" id="nome"  />
   </label>
   <!-- -->
   <label>
    <span>Telefones</span>
    <input type="text" class="dadosconta w_100 uk-text-center" value="<?php if(isset($dadosassociado)):echo tool::MascaraCampos("??-????-????",$dadosassociado[0]->fone_fixo); endif; ?>" name="fone_fixo" id="fone_fixo" placeholder="Residencia"/>
    <input type="text" class=" w_100 uk-text-center" value="<?php if(isset($dadosassociado)):echo $dadosassociado[0]->fone_trabalho; endif; ?>" name="fone_trab" id="fone_trab" placeholder="trabalho"/>

    <input type="text" class=" w_120 uk-text-center" value="<?php if(isset($dadosassociado)):echo $fone_cel; endif; ?>" name="fone_cel" id="fone_cel" placeholder="Celular"/>
    <a id="Btn_Sms" title="Enviar SMS" onClick="New_window('500','250','Envio de Mensagem','sms/Fr_SMS_Avulso.php?fone=<?php echo $dadosassociado[0]->fone_cel; ?>&msg=<?php echo base64_encode(""); ?>');"><i class="icon-chat" ></i></a>
</label>
<!-- -->
<label>
    <span>Data Nasc</span>
    <input  value="<?php if(isset($dadosassociado)):$now = new ActiveRecord\DateTime($dadosassociado[0]->dt_nascimento);echo $now->format('d/m/Y'); endif; ?>"  type="text" class=" w_100 uk-text-center" name="data_nasc" id="data_nasc" placeholder="00/00/0000"/>
    <?php
    if(isset($dadosassociado)):
        $idde=tool::CalcularIdade($now->format("Y-m-d"),date("Y-m-d"));
        echo'<div class="uk-badge uk-badge-warning">( '.$idde.' ) Anos</div>';
    endif;
    ?>
</label>
<!-- -->
<label>
    <span>Documento(CPF)</span>
    <input value="<?php if(isset($dadosassociado)):echo tool::MascaraCampos("???.???.???-??",$dadosassociado[0]->cpf); endif; ?>" type="text" class=" w_120 uk-text-center" name="cpf" id="cpf" placeholder="000.000.000-00"/>
</label>
<!-- -->
<label>
  <span>Identidade(RG)</span>
  <input value="<?php if(isset($dadosassociado)):echo $dadosassociado[0]->rg; endif; ?>" type="text" class="uk-text-center w_100" name="rg" id="rg" />
</label>
<!-- -->
<label>
  <span>Orgão Emissor</span>
  <input value="<?php if(isset($dadosassociado)):echo $dadosassociado[0]->orgao_emissor_rg; endif; ?>"   type="text" class=" w_100 uk-text-center" name="orgao_emissor_rg" id="orgao_emissor_rg" />
</label>
<!-- -->
<label>
  <span>Data de emissão</span>
  <input value="<?php if(isset($dadosassociado)):$now = new ActiveRecord\DateTime($dadosassociado[0]->data_emissao_rg); echo $now->format('d/m/Y');  endif; ?>"   type="text" class=" w_100 uk-text-center" name="data_emissao_rg" id="data_emissao_rg"  placeholder="00/00/0000"/>
</label>
<!-- -->
<label>
    <span>Tipo Associado</span>
    <div class="uk-form-controls">
        <label >
            <input type="radio"  name="agregado" value="1" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->agregado == 1){echo"checked";} }?> > Agregado
            <input type="radio"  name="agregado" value="0" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->agregado == 0){echo"checked";} }?> > Não Agregado
        </label>
    </div>
</label>
<!-- -->
<label>
    <span>Casa Propria</span>
    <div class="uk-form-controls">
        <label >
            <input type="radio" name="casa_propria" value="S" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->casa_propria == "S"){echo"checked";} }?> > Sim
            <input type="radio" name="casa_propria" value="N" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->casa_propria == "N"){echo"checked";} }?> > Não
        </label>
    </div>
</label>
<!-- -->
<label>
    <span>Sexo</span>
    <div class="uk-form-controls">
        <label >
            <input type="radio"  name="sexo" value="M" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->sexo == "M"){echo"checked";} }?> > Masculino
            <input type="radio"  name="sexo" value="F" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->sexo == "F"){echo"checked";} }?> > Feminino
        </label>
    </div>
</label>
<!-- -->
<label>
    <span>Estado civil</span>
    <div class="uk-form-controls">
        <label >
            <input type="radio"  name="estado_civil" value="C" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->estado_civil == "C"){echo"checked";} }?> > Casado (a)
            <input type="radio"  name="estado_civil" value="S" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->estado_civil == "S"){echo"checked";} }?> > Solteiro(a)
            <input type="radio"  name="estado_civil" value="V" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->estado_civil == "V"){echo"checked";} }?> > Viuvo(a)
            <input type="radio"  name="estado_civil" value="A" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->estado_civil == "A"){echo"checked";} }?> > Amasiado(a)
            <input type="radio"  name="estado_civil" value="D" <?php  if(isset($dadosassociado)){if($dadosassociado[0]->estado_civil == "D"){echo"checked";} }?> > Divorciado(a)
        </label>
    </div>
</label>
<!-- -->
<?php if($COB_Acesso_Id == 5): /*separa o dados a serem mostrados deixando para conveniada apenas o campo sub convenio caso haja*/ ?>


    <label>
        <span>Representante</span>
        <select class="select w_400" name="vendedor"  id="vendedor" >
            <?php
            if(isset($dadosassociado)):

                $descricaoconvenio=vendedores::find_by_sql("SELECT convenios.vendedores_id, vendedores.nm_vendedor FROM convenios LEFT JOIN vendedores ON convenios.vendedores_id = vendedores.id WHERE convenios.id='".$dadosassociado[0]->convenios_id."'");
                echo'<option value="'.$descricaoconvenio[0]->vendedores_id.'" >'.utf8_encode(strtoupper($descricaoconvenio[0]->nm_vendedor)).'</option>';


            else:
                $descricaoconvenio=vendedores::find_by_sql("SELECT convenios.vendedores_id, vendedores.nm_vendedor FROM convenios LEFT JOIN vendedores ON convenios.vendedores_id = vendedores.id WHERE convenios.id='".$COB_Convenio_Id."'");
                echo'<option value="'.$descricaoconvenio[0]->vendedores_id.'" >'.utf8_encode(strtoupper($descricaoconvenio[0]->nm_vendedor)).'</option>';

            endif;
            ?>
        </select>
    </label>

<?php else:?>

<label>
        <span>Vendedor</span>
        <select class="select w_400" name="vendedor"  id="vendedor" >
         <?php
         if(isset($dadosassociado)):

            $descricaovendedor=vendedores::find('all');
            $descricao= new ArrayIterator($descricaovendedor);
            while($descricao->valid()):

                if($descricao->current()->id == $dadosassociado[0]->vendedores_id){$select='selected="selected"';}else{$select="";}

                echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($descricao->current()->nm_vendedor)).'</option>';

                $descricao->next();
            endwhile;
        else:
            $descricaovendedor=vendedores::find('all',array('conditions'=>array('status= ?','1')));
            $descricao= new ArrayIterator($descricaovendedor);
            echo'<option value="" selected> Selecione um vendedor</option>';
            while($descricao->valid()):
                echo'<option value="'.$descricao->current()->id.'" >'.utf8_encode(strtoupper($descricao->current()->nm_vendedor)).'</option>';
                $descricao->next();
            endwhile;
        endif;
        ?>
    </select>
</label>

<?php endif;?> 

<!-- -->
<?php if($COB_Acesso_Id == 5): /*separa o dados a serem mostrados deixando para conveniada apenas o campo sub convenio caso haja*/ ?>

    <input value="<?php echo $COB_Convenio_Id ?>" type="hidden"  name="convenio" id="convenio"  />
    <label id="labelsubconvenio" style="display:block;">
        <span>Sub Convênio</span>
        <select class="select w_400" name="subconvenio" id="subconvenio" > 

            <?php
            if(isset($dadosassociado) && $dadosassociado[0]->sub_convenios_id != 0):

                $descricaosubconvenios=sub_convenios::find('all');
                $descricao= new ArrayIterator($descricaosubconvenios);
                while($descricao->valid()):

                    if($descricao->current()->id == $dadosassociado[0]->sub_convenios_id){$select='selected="selected"';}else{$select="";}
                    
                    echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.(strtoupper($descricao->current()->nm_fantasia)).'</option>';

                    $descricao->next();
                endwhile;

            else:

                $descricaosubconvenios=sub_convenios::find('all',array('conditions'=>array('convenios_id= ?',$COB_Convenio_Id)));
                $descricao= new ArrayIterator($descricaosubconvenios);
                echo'<option value=""selected>Selecione um sub convênio</option>';
                while($descricao->valid()):
                    echo'<option value="'.$descricao->current()->id.'" >'.(strtoupper($descricao->current()->nm_fantasia)).'</option>';
                    $descricao->next();
                endwhile;

            endif;
            ?>
        </select>
    </label>
    <!-- caso o usuario não seja de empresa conveniada e sim usuario da empresa gestora abre se os dads abaixo -->
    <?php else: ?>
        <!-- -->
        <label>
            <span>Convenio</span>
            <select class="select w_400" name="convenio" id="convenio" >
                <?php
                if(isset($dadosassociado)):

                    $descricaoconvenio=convenios::find('all');
                    $descricao= new ArrayIterator($descricaoconvenio);
                    while($descricao->valid()):

                        if($descricao->current()->id == $dadosassociado[0]->convenios_id){$select='selected="selected"';}else{$select="";}

                        echo'<option  value="'.$descricao->current()->id.'" '.$select.'>('.$descricao->current()->id.") - ".utf8_encode(strtoupper($descricao->current()->nm_fantasia)).'</option>';
                        $descricao->next();
                    endwhile;

                else:

                    $descricaoconvenio=convenios::find('all',array('conditions'=>array('status= ? and empresas_id= ?','1',$COB_Empresa_Id)));
                    $descricao= new ArrayIterator($descricaoconvenio);
                    echo'<option value="" selected> selecione um convênio</option>';
                    while($descricao->valid()):
                        echo'<option value="'.$descricao->current()->id.'" >'.utf8_encode(strtoupper($descricao->current()->nm_fantasia)).'</option>';
                        $descricao->next();

                    endwhile;
                endif;
                ?>
            </select>
            <input value="<?php if(isset($dadosassociado)){echo $dadosassociado[0]->convenios_id;}else{} ?>" type="hidden"  name="cv_confirm" id="cv_confirm"  />
        </label>
        <!-- -->
        <label id="labelsubconvenio">
            <span>Sub Convênios</span>
            <select class="select w_400" name="subconvenio" id="subconvenio" >

                <?php  if(isset($dadosassociado) && $dadosassociado[0]->sub_convenios_id != 0): 

                    $descricaosubconvenios=sub_convenios::find('all');
                    $descricao= new ArrayIterator($descricaosubconvenios);
                    while($descricao->valid()):

                       if($descricao->current()->id == $dadosassociado[0]->sub_convenios_id){$select='selected="selected"';}else{$select="";}

                       echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($descricao->current()->nm_fantasia)).'</option>';

                       $descricao->next();
                   endwhile;

               else:   

                echo'<option>Convênio não possui subconvênios</option>';

            endif; 
            ?>
        </select>
    </label> 

<?php endif;?> 

<label>
    <span>E-mail</span>
    <div class="uk-form-icon">
        <i class="uk-icon-envelope"></i>
        <input type="text" class="uk-text-left w_400" value="<?php if(isset($dadosassociado)):echo $dadosassociado[0]->email; endif; ?>" name="email" id="email"/>
    </div>
</label>
<!-- -->
<legend style="margin-bottom: 5px;">
    <i class="uk-icon-street-view " ></i> Endereço
</legend>
<!-- -->
<label>
    <span>Estado</span>
    <select class="select w_400" name="uf"  id="uf" >
     <?php
     if(isset($dadosassociado)):

         $descricaoestados=estados::find_by_sql("SELECT * FROM estados GROUP BY descricao");
         $descricao= new ArrayIterator($descricaoestados);
         while($descricao->valid()):
            if($descricao->current()->id == $dadosassociado[0]->estado_id){$select='selected="selected"';}else{$select="";}
            echo'<option value="'.$descricao->current()->id.'" '.$select.'>'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';
            $descricao->next();
        endwhile;
    else:
        echo'<option value="" >Selecione o estado</option>';
        $descricaoestados=estados::find_by_sql("SELECT * FROM estados GROUP BY descricao");
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
     if(isset($dadosassociado)):
        echo'<option value="'.$dadosassociado[0]->cidade_id.'" selected="selected">'.utf8_encode(strtoupper($dadosassociado[0]->nm_cidade)).'</option>';
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
        if(isset($dadosassociado)):
            echo'<option value="'.$dadosassociado[0]->bairro_id.'" selected="selected">'.utf8_encode(strtoupper($dadosassociado[0]->nm_bairro)).'</option>';
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
        if(isset($dadosassociado)):
            echo'<option value="'.$dadosassociado[0]->logradouros_id.'" selected="selected">'.utf8_encode(strtoupper($dadosassociado[0]->nm_logradouro)).'</option>';
        else:
            echo'<option value="" >Aguardando...</option>';
        endif;
        ?>
    </select> <button class="uk-button uk-button-small uk-button-primary" type="button" data-uk-modal="{target:'#dvrua'}"><i class="uk-icon-plus"></i> Adcionar</button>
</label>
<label>
    <span>Complemento</span>
    <input name="compl_end" class=" w_300 uk-text-left" id="compl_end"  value="<?php if(isset($dadosassociado)):echo $dadosassociado[0]->compl_end; endif; ?>" maxlength="20" placeholder="Ex: Quadra,lote" >
</label>
<label>
    <span>Numero</span>
    <input name="num" class=" w_100 uk-text-center" id="num"  value="<?php if(isset($dadosassociado)):echo $dadosassociado[0]->num; endif; ?>" maxlength="9" >
</label>
<label>
    <span>Cep</span>
    <div class="uk-form-icon">
        <i class="uk-icon-street-view"></i>
        <input name="cep" class="w_100 uk-text-center" id="cep"  value="<?php if(isset($dadosassociado)):echo tool::MascaraCampos("?????-???",$dadosassociado[0]->cep); endif; ?>" maxlength="9" readonly  >
    </div>
    <!--       <a class="uk-icon-search"   href="javascript:void(0)" onClick="Busca_Cep('')"></a>-->
</label>
<legend style="margin-bottom: 5px;">
    <i class="uk-icon-street-view " ></i> Observações
</legend>


<textarea name="obs" class="message" style="height:200px; min-width: 870px; margin-left:auto;overflow-y: scroll;" id="obs"><?php if(isset($dadosassociado)):   echo $dadosassociado[0]->obs; endif ; ?> </textarea>

</fieldset>

</form>
<br>
<script type="text/javascript" src="assets/js/associado.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/ajax_logradouros.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="endereco/cep/ajax_logradouros_cep.js?<?php echo microtime(); ?>"></script>