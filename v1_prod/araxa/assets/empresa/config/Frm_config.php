<?php
require_once"../../../sessao.php";

echo'<div class="tabs-spacer" style="display:none;">';

include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


// recupera os dados do associado
$dadosconfig=configs::find($COB_Empresa_Id);

echo'</div>';

?>

<style>
#menu-float a{ background-color:transparent;}
#FrmConfigs legend{ background-color:transparent; color:#666; font-weight:bold; font-size:11px;padding: 10px;}
#FrmConfigs span{ background-color: transparent; width:100px; text-align: center; }
#FrmConfigs .uk-grid{ margin:auto; }

</style>


<div id="menu-float" style="text-align:center;margin:0 700px;top:33px;border:0;background-color:#546e7a;">
  <a  id="Btn_config_0" class="uk-icon-button uk-icon-pencil " style="margin-top:2px;text-align:center; " data-uk-tooltip="{pos:'left'}" title="Salvar alteraçôes" data-cached-title="Salvar alteraçôes" >
  </a>
</div>


<form method="post" id="FrmConfigs" class="uk-form" style=" width:900px;  padding-top:0; margin:0;">
<input value="<?php  if(isset($dadosconfig)):echo tool::CompletaZeros("11",$dadosconfig->empresas_id); endif; ?>"  name="empresa_id" type="hidden" id="empresa_id" />

<fieldset style="width:678px; left:5px; top:30px; position:absolute;">
<legend class="uk-gradient-cinza" style="padding:5px 10px 5px 10px;">Configurações Gerais</legend>
  <div class="uk-grid" >
    <div class="uk-width-1-3">
      <label>
        <span>Juros mora</span> <div class="uk-badge uk-badge-success" style="position: absolute; margin: 5px 85px;">Padrão taxa Selic Dez/<?php echo (date("y")-1); ?></div>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->juros; endif; ?>"  name="juros" type="text" class="uk-text-center w_100 " id="juros" /><div class="uk-badge uk-badge-warning">%</div>
      </label>
    </div>
    <div class="uk-width-1-3">
      <label>
        <span>Multa atrazo </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->multa; endif; ?>"  name="multa" type="text" class="uk-text-center w_100 " id="multa" /><div class="uk-badge uk-badge-warning">%</div>
      </label>
    </div>

    <div class="uk-width-1-3">
      <label>
        <span>Carencia </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->carencia; endif; ?>"  name="carencia" type="text" class="uk-text-center w_100 " id="carencia" /><div class="uk-badge uk-badge-warning">dias</div>
      </label>
    </div>
  </div>

  <div class="uk-grid" >
    <div class="uk-width-1-3">
      <label>
        <span>Centro de custo</span>
        <select name="centro_custo_id" id="centro_custo_id" class="select" style="width: 100%;">
        <option value=""></option>
        <?php
        $Query_c_custos=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and categoria= ?',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
        $Arr_c_custos= new ArrayIterator($Query_c_custos);
         while($Arr_c_custos->valid()):

        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_c_custos->current()->descricao)).'">';

          $Query_c_custos1=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and subcategoria= ?',$COB_Empresa_Id,$Arr_c_custos->current()->id ),'order' => 'id ASC'));
          $Arr_c_custos1= new ArrayIterator($Query_c_custos1);

          while($Arr_c_custos1->valid()):

              if($Arr_c_custos1->current()->id == $dadosconfig->centros_custos_id){$select='selected="selected"';}else{$select="";}

              echo'<option value="'.$Arr_c_custos1->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_c_custos1->current()->descricao)).'</option>';

              $Arr_c_custos1->next();
          endwhile;
        echo'</optgroup>';

      $Arr_c_custos->next();
        $Arr_c_custos->next();
  endwhile;
            ?>

        </select>
      </label>
    </div>
    <div class="uk-width-1-3">
      <label>
        <span style="width: 100%;" class="uk-text-left">Plano de contas receitas</span>
        <select name="plano_conta_id" id="plano_conta_id" class="select" style="width: 100%;">
        <option value="" ></option>
        <?php
       $Query_planos=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and categoria= ?','R',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
              $Arr_conta= new ArrayIterator($Query_planos);
        while($Arr_conta->valid()):

                $Query_planos1=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and subcategoria= ?','R',$COB_Empresa_Id,$Arr_conta->current()->id ),'order' => 'id ASC'));
                $Arr_planos1= new ArrayIterator($Query_planos1);

                while($Arr_planos1->valid()):

                    if($Arr_planos1->current()->id == $dadosconfig->planos_contas_id ){$select='selected="selected"';}else{$select="";}

                    echo'<option value="'.$Arr_planos1->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_planos1->current()->descricao)).'</option>';

                    $Arr_planos1->next();
                endwhile;
              echo'</optgroup>';

              $Arr_conta->next();
        endwhile;

            ?>
        </select>
      </label>
    </div>
    <div class="uk-width-1-3 uk-text-left">
      <label>
        <span style="width: 100%;" class="uk-text-left">Plano de contas despesas</span>
        <select name="plano_conta_id_d" id="plano_conta_id_d" class="select" style="width: 100%;">
        <option value="" ></option>
        <?php
       $Query_planos=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and categoria= ?','D',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
              $Arr_conta= new ArrayIterator($Query_planos);
        while($Arr_conta->valid()):


                $Query_planos1=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and subcategoria= ?','D',$COB_Empresa_Id,$Arr_conta->current()->id ),'order' => 'id ASC'));
                $Arr_planos1= new ArrayIterator($Query_planos1);

                while($Arr_planos1->valid()):

                    if($Arr_planos1->current()->id == $dadosconfig->planos_contas_id_d){$select='selected="selected"';}else{$select="";}

                    echo'<option value="'.$Arr_planos1->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_planos1->current()->descricao)).'</option>';

                    $Arr_planos1->next();
                endwhile;
              echo'</optgroup>';

              $Arr_conta->next();
        endwhile;

            ?>
        </select>
      </label>
    </div>
    <div class="uk-width-1-9 uk-text-warning" style="font-size: 11px;">
     Não informando os centro de custo e os planos de contas será utilizadp os padrões . C-Custo 7 | P-Conta-R 4 | P-Conta-D 4
    </div>

  </div>

</fieldset>

<fieldset style="width:678px; left:5px; top:205px; position:absolute;">
<legend class="uk-gradient-cinza" style="padding:5px 10px 5px 10px; border-top: 1px solid #ccc;">Medias para desconto negociação</legend>
  <div class="uk-grid" >
    <div class="uk-width-1-3">
      <label>
        <span>< 200 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_um; endif; ?>"  name="desc_um" type="text" class="uk-text-center w_100 " id="desc_um" /><div class="uk-badge uk-badge-success">%</div>
      </label>
    </div>
    <div class="uk-width-1-3">
      <label>
        <span>200 <> 300 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_dois; endif; ?>"  name="desc_dois" type="text" class="uk-text-center w_100 " id="desc_dois" /><div class="uk-badge uk-badge-success">%</div>
      </label>
    </div>
    <div class="uk-width-1-3">
      <label>
        <span>300 <> 400 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_tres; endif; ?>"  name="desc_tres" type="text" class="uk-text-center w_100 " id="desc_tres" /><div class="uk-badge uk-badge-success">%</div>
     </label>
    </div>
  </div>

  <div class="uk-grid" >
    <div class="uk-width-1-3">
      <label>
        <span>400 <> 500 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_quatro; endif; ?>"  name="desc_quatro" type="text" class="uk-text-center w_100 " id="desc_quatro" /><div class="uk-badge uk-badge-success">%</div>
     </label>
    </div>
    <div class="uk-width-1-3">
      <label>
        <span>500 <> 600 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_cinco; endif; ?>"  name="desc_cinco" type="text" class="uk-text-center w_100 " id="desc_cinco" /><div class="uk-badge uk-badge-success">%</div>
      </label>
    </div>
    <div class="uk-width-1-3">
      <label>
        <span>600 <> 700 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_seis; endif; ?>"  name="desc_seis" type="text" class="uk-text-center w_100 " id="desc_seis" /><div class="uk-badge uk-badge-success">%</div>
      </label>
    </div>
  </div>


  <div class="uk-grid" >
    <div class="uk-width-1-3">
      <label>
        <span>700 <> 800 </span>
        <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_sete; endif; ?>"  name="desc_sete" type="text" class="uk-text-center w_100 " id="desc_sete" /><div class="uk-badge uk-badge-success">%</div>
      </label>

    </div>
    <div class="uk-width-1-3">
      <label>
          <span>800 <> 900 </span>
          <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_oito; endif; ?>"  name="desc_oito" type="text" class="uk-text-center w_100 " id="desc_oito" /><div class="uk-badge uk-badge-success">%</div>
      </label>
    </div>
    <div class="uk-width-1-3">
    <label>
      <span>> 900 </span>
      <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->desc_nove; endif; ?>"  name="desc_nove" type="text" class="uk-text-center w_100 " id="desc_nove" /><div class="uk-badge uk-badge-success">%</div>
    </label>
    </div>
  </div>

</fieldset>


<fieldset style="width:678px; left:5px; top:423px; position:absolute;">
<legend class="uk-gradient-cinza" style="padding:5px 10px 5px 10px;border-top: 1px solid #ccc;">Dados apolicé seguro</legend>

<div class="uk-grid" >

<div class="uk-width-1-3">
<label>
  <span>Seguradora</span>
  <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->nm_seguradora; endif; ?>"  name="nm_seguradora" type="text" class="uk-text-center w_400 " id="nm_seguradora" />
</label>
</div>
<div class="uk-width-1-3">
<label>
  <span>Cnpj</span>
  <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->cnpj_seg; endif; ?>"  name="cnpj_seg" type="text" class="uk-text-center w_150 " id="cnpj_seg" />
</label>
</div>
<div class="uk-width-1-3">
<label>
  <span>Nº Apólice</span>
  <input value="<?php  if(isset($dadosconfig)):echo $dadosconfig->num_apolice; endif; ?>"  name="num_apolice" type="text" class="uk-text-center w_150 " id="num_apolice" />
</label>
</div>
</div>

<div class="uk-grid" >

<div class="uk-width-1-3">
<label>
  <span>Valor Seguro</span>
  <input value="<?php  if(isset($dadosconfig)):echo number_format($dadosconfig->vlr_apol_seg,2,",","."); endif; ?>"  name="vlr_apol_seg" type="text" class="uk-text-center w_100 " id="vlr_apol_seg" /><div class="uk-badge uk-badge-warning">R$</div>
</label>
</div>
<div class="uk-width-1-3" >
<label>
  <span>Valor Funeral</span>
  <input value="<?php  if(isset($dadosconfig)):echo number_format($dadosconfig->vlr_aux_fun,2,",","."); endif; ?>"  name="vlr_aux_fun" type="text" class="uk-text-center w_100 " id="vlr_aux_fun" /><div class="uk-badge uk-badge-warning">R$</div>
</label>
</div>
<div class="uk-width-1-3">
<label>
  <span>validade_apolice</span>
  <input value="<?php  if(isset($dadosconfig)):$now = new ActiveRecord\DateTime($dadosconfig->validade_apolice);echo $now->format('d/m/Y'); endif; ?>"  name="validade_apolice" type="text" class="uk-text-center w_100 " id="validade_apolice" />
</label>
</div>

</div>
</fieldset>

</form>

<script src="framework/uikit-2.24.0/js/core/tab.min.js"></script>

<script type="text/javascript" >

/* define as mascaras */
jQuery(document).ready(function(){
	jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");
  jQuery("#validade_apolice").mask("99/99/9999");jQuery("#cnpj_seg").mask("99.999.999/9999-99");
  jQuery("#juros,#multa").mask("99.99");
  jQuery("#vlr_aux_fun,#vlr_apol_seg").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});
});


/*faz a requisição do formulario tanto para adição quanto para edição*/
jQuery(function() {

jQuery("#Btn_config_0").click(function(event) {

  /*mensagen de carregamento*/
  jQuery("#msg_loading").html(" Aguarde... ");
  /*abre a tela de preload*/
  modal.show();
  /*desabilita o envento padrao do formulario*/
  event.preventDefault();

  jQuery.ajax({
    async: true,
    url: "assets/empresa/config/Controller_config.php",
    type: "post",
    data:jQuery("#FrmConfigs").serialize(),
    success: function(resultado) {

        //abre a tela de preload
        modal.hide();
        UIkit.notify(""+resultado+"", {timeout: 2500})

  },
  error:function (){
    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
    modal.hide();
  }

  });
 });
});










</script>