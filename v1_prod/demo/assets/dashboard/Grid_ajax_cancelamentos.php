<?php
require_once("../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">

<?php
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_referencia       = isset( $_GET['ref'])        ? $_GET['ref'] : tool::msg_erros("O Campo ref é Obrigatorio.");

/* definimos o ano para o botão voltar*/
$year= explode("-",$FRM_referencia);


// retorna todo os registros para montar a paginação
$Query_all=associados::find_by_sql("SELECT associados.*,convenios.nm_fantasia FROM associados
                                    RIGHT JOIN convenios ON associados.convenios_id = convenios.id
                                    WHERE associados.dt_cancelamento between '".$FRM_referencia."-01' AND '".$FRM_referencia."-31' AND associados.status='0' AND associados.empresas_id='".$COB_Empresa_Id."'");


$List_detalhes= new ArrayIterator($Query_all);

?>
</div>
    <div class="uk-modal-header">
      <a href="JavaScript:void(0);" id="Btn_Det_New_cad_voltar" class="uk-button uk-button-danger" uk-data-year="<?php echo $year[0]; ?>"><i class="uk-icon-angle-double-left" ></i> Voltar</a>
    </div>

<nav class="uk-navbar ">
<table  class="uk-table" >
    <thead >
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:30px;" ></th>
        <th class="uk-width uk-text-center" style="width:100px;" >Matricula</th>
        <th class="uk-width uk-text-left"   style="width:220px;">Nome</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Dt Canc</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Fone Cel</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Fone Fixo</th>

        <th class="uk-text-left" >Convênio</th>
      </tr>
    </thead>
 </table>
</nav>

<div style="width: 100%; overflow-x: auto; height: 440px;">

<table  class="uk-table uk-table-striped uk-table-hover" >
<tbody>
<?php
$linha=0;
$total=0;

while($List_detalhes->valid()):

$linha++;
$dt_cad  = new ActiveRecord\DateTime($List_detalhes->current()->dt_cancelamento);

/* validação de nono digito*/
if(strlen(tool::LimpaString($List_detalhes->current()->fone_cel)) == "10"){

$fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($List_detalhes->current()->fone_cel),0,2)."0".substr(tool::LimpaString($List_detalhes->current()->fone_cel),2,8));

}else{

$fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($List_detalhes->current()->fone_cel),0,2)." ".substr(tool::LimpaString($List_detalhes->current()->fone_cel),2,9));
}

?>
      <tr style="line-height:23px; <?php echo $color_error; ?>" >
        <th class="uk-width uk-text-center" style="width:30px;" ><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo $List_detalhes->current()->matricula; ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:220px;max-width: 220px;" ><?php echo $List_detalhes->current()->nm_associado; ?></td>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo $dt_cad->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo tool::MascaraCampos("??-????-????",$List_detalhes->current()->fone_fixo); ?></td>
        <td class="uk-width uk-text-center" style="width:100px;" ><?php echo $fone_cel; ?></td>
        <td class="uk-text-left uk-text-uppercase"  ><?php echo $List_detalhes->current()->nm_fantasia; ?></td>

      </tr>

<?php
$total++;
$List_detalhes->next();
endwhile;
?>
</tbody>
<tfoot>
  <tr style="line-height:25px;">
    <th class="uk-width uk-text-center" style="width:30px;" ></th>
    <th class="uk-width uk-text-center" style="width:100px;" ></th>
    <th class="uk-width uk-text-left"   style="width:220px;">Total</th>
    <th class="uk-width uk-text-center" style="width:100px;" ></th>
    <th class="uk-width uk-text-center" style="width:90px;" ><?php echo $total; ?></th>
    <th class="uk-width uk-text-center" style="width:100px;" ></th>
    <th class="uk-text-left"  ></th>
    <th class="uk-width uk-text-center" style="width:20px;" ></th>
  </tr>
</tfoot>
</table>
</div>
<script type="text/javascript">

/* botão retroceder year*/
jQuery("#Btn_Det_New_cad_voltar").click(function(event) {
        event.preventDefault();
        var year = jQuery(this).attr("uk-data-year");
    jQuery(".uk-modal-det").html('<i class="uk-icon-spinner uk-icon-spin"></i><span > Carregando </span>').load("assets/dashboard/Grid_cancelamentos.php?year="+year+"");
    });

</script>
