<?php
require_once"../../sessao.php";

?>
<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_convenio_id    = isset( $_GET['convenio_id'])    ? $_GET['convenio_id']            : tool::msg_erros("O Campo convenio_id é Obrigatorio.");


// contas bancarias habilitadas a emitir boleto
$Query_formas_cobranca=formas_cobranca::find_by_sql("SELECT formas_cobranca.*,formas_cobranca_sys.descricao as forma_cob
                                                     FROM formas_cobranca
                                                     LEFT JOIN formas_cobranca_sys ON formas_cobranca.forma_cobranca_sys_id = formas_cobranca_sys.id
                                                     WHERE empresas_id='".$COB_Empresa_Id."' AND convenios_id='".$FRM_convenio_id."' AND status='1' ORDER BY id");
$List_formas= new ArrayIterator($Query_formas_cobranca);


$lf="1";
?>
</div>

<style>
#menu-float a{ background-color:transparent;}
.uk-text-warning {
    color: #F90 !important
}
#FrmNovaForma  span{  width:150px;  }
#DadosNovoPlano span {  width:150px;  }
</style>

<div id="menu-float" style="text-align:center;margin:0 900px;top:35px;border:0;background-color:#546e7a;">

    <a id="Btn_forma_01" class="uk-icon-button uk-icon-file-o" style="margin-top:2px;text-align:center;" data-uk-modal="{target:'#DadosNovaForma'}" data-uk-tooltip="{pos:'left'}" title="Adcionar nova forma de cobrança" data-cached-title="Adcionar Nova forma de cobrança" ></a>
    <a id="Btn_forma_01" class="uk-icon-button uk-icon-copy" style="margin-top:2px;text-align:center;" data-uk-modal="{target:'#DadosNovoPlano'}" data-uk-tooltip="{pos:'left'}" title="Adcionar novo plano" data-cached-title="Adcionar Novo Plano" ></a>
    <a id="Btn_forma_01" class="uk-icon-button uk-icon-refresh" style="margin-top:2px;text-align:center;" data-uk-modal="{target:'#'}" data-uk-tooltip="{pos:'left'}" title="Atualizar Grid" data-cached-title="Atualizar Grid" ></a>

</div>

<nav class="uk-navbar">
<table  class="uk-table" >
  <thead >
    <tr style="line-height: 20px;">
        <th class="uk-width uk-text-left" style="width:20px;"></th>
        <th class="uk-width  uk-text-center" style="width:100px;"  >Status</th>
        <th class="uk-width  uk-text-center" style="width:100px;" >Codigo</th>
        <th class="uk-width uk-text-left" style="width:200px;">Descrição</th>
        <th class="uk-width uk-text-left" style="width:200px;">Forma de Cobrança</th>
        <th class=" uk-text-center">Tipo da Forma</th>
        <th class="uk-width uk-text-center" style="width:30px;" >Ações</th>
    </tr>
    </thead>
 </table>
</nav>

<div id="GridFormasCobranca" style="height:180px; overflow-y:scroll;">
<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody >
<?php
// laço que loopa os lançamentos dos convenios  agrupando por data
$List_forma= new ArrayIterator($List_formas);
while($List_forma->valid()):

?>
    <tr style="line-height: 20px;" onclick="DescPla('<?php echo $List_forma->current()->id; ?>');">
        <th class="uk-width uk-text-center " style="width:20px;" ><?php echo $lf; ?></th>
        <td class="uk-width  uk-text-center" style="width:100px;"  >
        <?php

        if($List_forma->current()->status == 1){
            echo' <div  class="uk-badge uk-badge-notification uk-text-small">ATIVA</div>';
        }else{
            echo' <div  class="uk-badge uk-badge-danger uk-badge-notification uk-text-small">INATIVA</div>';
        }

        ?>
        </td>
        <td class="uk-width  uk-text-center" style="width:100px;" ><?php echo tool::CompletaZeros(7,$List_forma->current()->forma_cobranca_sys_id.".".$List_forma->current()->id); ?></td>
        <td class="uk-width uk-text-left" style="width:200px;"><?php echo $List_forma->current()->descricao; ?></td>
        <td class="uk-width uk-text-left" style="width:200px;"><?php echo $List_forma->current()->forma_cob; ?></td>
        <td class="uk-text-center" >
        <?php

        if($List_forma->current()->tipo == "F"){
            echo' <div  class="uk-badge uk-badge-warning uk-badge-notification uk-text-small">PESSOA FISÍCA</div>';
        }else{
            echo' <div  class="uk-badge uk-badge-warning uk-badge-notification uk-text-small">PESSOA JURIDICA</div>';
        }

        ?>
        <td class="uk-width uk-text-center" style="width:30px;" >
        <a class="uk-icon-times-circle uk-icon-medium" style="margin-left:10px;" data-uk-tooltip="{pos:'left'}" title="Desativar" data-cached-title="Desativar"></a>
        </td>
    </tr>
<?php
$lf++;
$List_forma->next();
endwhile;
?>
</tbody>
</table>
</div>

<nav class="uk-navbar  uk-text-center" style="line-height: 30px; ">
Planos Disponíveis

<table  class="uk-table" >
  <thead >
    <tr style="line-height: 20px;">
        <th class="uk-width uk-text-left" style="width:20px;"></th>
        <th class="uk-width  uk-text-center" style="width:100px;"  >Status</th>
        <th class="uk-width  uk-text-center" style="width:100px;" >Codigo</th>
        <th class="uk-width uk-text-left" style="width:200px;">Descrição</th>
        <th class="uk-width uk-text-left" style="width:200px;">Obs Plano</th>
        <th class="uk-width uk-text-center" style="width:150px;">Seguros (SIM/NÂO)</th>
        <th class="uk-text-right">Ações</th>
    </tr>
    </thead>
 </table>
</nav>
<div id="GridPlanos" style="height:215px; overflow-y:scroll;">


</div>

<form class="uk-form uk-form-tab" id="FrmNovaForma" style="padding: 0; margin: 0;">
<div id="DadosNovaForma" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
        <h2><i class="uk-icon-plus" ></i> Adcionar Nova Forma de CObrança</h2>
        </div>
            <label>
                <span>Forma de CObrança</span>
                <select name="f_cob_sys" id="f_cob_sys" class="select">
                    <option value="" selected></option>
                <?php

                 $formasdecobranca=formas_cobranca_sys::find('all');

                 $descricao= new ArrayIterator($formasdecobranca);

                 while($descricao->valid()):

                    echo'<option value="'.$descricao->current()->id.'" >'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';

                 $descricao->next();

                 endwhile;

                ?>
                </select>
            </label>

            <label>
                <span>Tipo de Cobrança</span>
                <select name="tp_cob" id="tp_cob" class="select">
                    <option value="" selected></option>
                    <option value="F">PESSOA FÍSICA</option>
                    <option value="J">PESSOA JURIDICA</option>
                </select>
            </label>

        <div class="uk-modal-footer uk-text-right">
            <a id="Btn_fat_9" class="uk-button uk-button-primary" ><i class="uk-icon-check" ></i> Confirmar</a>
            <a id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
    </div>
</div>
</form>



<form class="uk-form uk-form-tab" id="FrmNovoPlano" style="padding: 0; margin: 0;">
<div id="DadosNovoPlano" class="uk-modal">
    <div class="uk-modal-dialog" style="width:600px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
        <h2><i class="uk-icon-plus" ></i> Adcionar Novo Plano</h2>
        </div>
            <label>
                <span>Forma de Cobrança</span>
                <select name="f_cob_conv" id="f_cob_conv" class="select">
                    <option value="" selected></option>
                <?php

                 $List_forma= new ArrayIterator($List_formas);
                 while($List_forma->valid()):

                    echo'<option value="'.$List_forma->current()->id.'" >'.strtoupper(utf8_encode($List_forma->current()->descricao)).'</option>';

                 $List_forma->next();

                 endwhile;

                ?>
                </select>
            </label>

            <label>
                <span>Modelo de Plano</span>
                <select name="mod_plano" id="mod_plano" class="select">
                    <option value="" selected></option>
                    <option value="A1">PLANO BASICO</option>
                    <option value="A2">PLANO INTERMEDIARIO</option>
                    <option value="A3">PlANO PLUS</option>
                    <option value="A4">PLANO ESPECIAL</option>
                </select>
            </label>
            <label>
                <span>Possui Seguros?</span> <div class="uk-badge uk-badge-success" style="position: absolute; margin: 5px 85px;">Seguro de vida / Auxilio Funerario</div>
                <select name="seguro" id="seguro" class="select ">
                    <option value="" selected></option>
                    <option value="1">SIM</option>
                    <option value="0">NÃO</option>
                </select>
            </label>
            <label>
                <span>Valor plano</span>
                <input  name="vlr_plano" type="text" class="uk-text-center w_100 " id="vlr_plano" />
            </label>
            <label>
                <span>Descrição</span>
                <input name="desc_plano" type="text" class="uk-text-center w_250 " id="desc_plano" />
            </label>


        <div class="uk-modal-footer uk-text-right">
            <a id="Btn_fat_9" class="uk-button uk-button-primary" ><i class="uk-icon-check" ></i> Confirmar</a>
            <a id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
    </div>
</div>
</form>



<script type="text/javascript" >

jQuery(document).ready(function(){

jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");// define a cor do menu lateral
  jQuery("#vlr_plano").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});

});

function DescPla(fcob_id){

     modal.show();
    jQuery("#GridPlanos").load("assets/convenio/Grid_planos_fcob.php?fcob_id="+fcob_id+"",function(){modal.hide();});

}

</script>