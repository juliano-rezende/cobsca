<?php require_once"../../sessao.php";?>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_fcob_id    = isset( $_GET['fcob_id'])    ? $_GET['fcob_id']            : tool::msg_erros("O Campo fcob_id é Obrigatorio.");

$Query_planos=planos::find_by_sql("SELECT * FROM planos WHERE forma_cobranca_id='".$FRM_fcob_id."' ORDER BY id");
$List_planos= new ArrayIterator($Query_planos);

/* contador de linhas */
$linha=1;
?>
</div>
<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody >
<?php

// laço que loopa os lançamentos dos convenios  agrupando por data
$List_planos= new ArrayIterator($List_planos);
while($List_planos->valid()):

?>
    <tr style="line-height: 30px;">
        <th class="uk-width uk-text-center " style="width:20px;" ><?php echo $linha; ?></th>
        <td class="uk-width  uk-text-center" style="width:100px;"  >
        <?php

        if($List_planos->current()->status == 1){
            echo' <div  class="uk-badge uk-badge-notification uk-text-small">ATIVO</div>';
        }else{
            echo' <div  class="uk-badge uk-badge-danger uk-badge-notification uk-text-small">INATIVO</div>';
        }

        ?>
        </td>
        <td class="uk-width  uk-text-center" style="width:100px;" ><?php echo tool::CompletaZeros(7,$List_planos->current()->forma_cobranca_id.".".$List_planos->current()->id); ?></td>
        <td class="uk-width  uk-text-center" style="width:100px;" ><?php echo number_format($List_planos->current()->valor_dependente,2,",",","); ?></td>
        <td class="uk-width uk-text-left" style="width:200px;"><?php echo $List_planos->current()->descricao; ?></td>
        <td class="uk-width uk-text-left" style="width:200px;"><?php echo $List_planos->current()->obs_plano; ?></td>
        <td class="uk-text-center" style="width:150px;">
         <?php

        if($List_planos->current()->seguro == 1){
            echo' <div  class="uk-badge uk-badge-warning uk-badge-notification uk-text-small">SIM</div>';
        }else{
            echo' <div  class="uk-badge uk-badge-warning uk-badge-notification uk-text-small">NÃO</div>';
        }

        ?>
        <td class="uk-text-right"  >
        <a class="uk-icon-times-circle uk-icon-medium" style="margin-left:10px;" data-uk-tooltip="{pos:'left'}" title="Desativar" data-cached-title="Desativar"></a>
        </td>
        </tr>
<?php
$linha++;
$List_planos->next();
endwhile;
?>
  </tbody>

 </table>