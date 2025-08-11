<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">

<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_cod_remessa= isset( $_GET['cod_remessa'])     ? $_GET['cod_remessa'] : tool::msg_erros("O Campo cod_remessa é Obrigatorio.");

// retorna todo os registros para montar a paginação
$Query_all=titulos::find_by_sql("SELECT titulos_bancarios.*,contas_bancarias.cod_banco FROM titulos_bancarios LEFT JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
                                WHERE cod_remessa ='".$FRM_cod_remessa."'");


$List_detalhes= new ArrayIterator($Query_all);

?>
</div>

<nav class="uk-navbar ">
<table  class="uk-table" >
    <thead >
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:30px;" ></th>
        <th class="uk-width uk-text-left"   style="width:300px;">Sacado</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Dt Venc</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Dt Pgto</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Valor</th>
        <th class="uk-text-left" >Tipo do Movimento</th>
      </tr>
    </thead>
 </table>
</nav>

<div style="width: 100%; overflow-x: auto; height: 480px;">

<table  class="uk-table uk-table-striped uk-table-hover" >
<tbody>
<?php
$linha=0;

while($List_detalhes->valid()):

$linha++;
$dt_emissao  = new ActiveRecord\DateTime($List_detalhes->current()->dt_vencimento);
$dt_venc  = new ActiveRecord\DateTime($List_detalhes->current()->dt_vencimento);
$dt_pagto = new ActiveRecord\DateTime($List_detalhes->current()->dt_pagamento);

?>

      <tr style="line-height:23px;">
        <td class="uk-width uk-text-center" style="width:30px;" ><?php echo $linha; ?></td>
        <td class="uk-width uk-text-left"   style="width:300px; text-transform: uppercase;" ><?php echo $List_detalhes->current()->sacado; ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo $dt_venc->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo $dt_pagto->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:100px;"><?php echo number_format($List_detalhes->current()->vlr_nominal,2,',','.'); ?></td>
        <td class="uk-text-left"  >
        <?php echo remessas::Det_cod_Remessa(''.$List_detalhes->current()->cod_banco.'','MOV'.$List_detalhes->current()->cod_mov_rem.''); ?></td>

      </tr>

<?php
$List_detalhes->next();
endwhile;
?>
</tbody>
</table>
</div>