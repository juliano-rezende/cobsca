<?php 

require_once"../../../sessao.php"; 
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_matricula      = isset( $_GET['matricula'])      ? $_GET['matricula']              : tool::msg_erros("O Campo matricula é Obrigatorio.");

?>
<table  class="uk-table uk-table-striped uk-table-hover" >
    <tbody >
      <?php
      $dadosautorizacao=med_autorizacoes::find_by_sql("SELECT med_autorizacoes.*,
       CASE WHEN med_autorizacoes.dependente = '1' THEN (SELECT dependentes.nome FROM dependentes  WHERE id = med_autorizacoes.dependentes_id)
       ELSE (SELECT associados.nm_associado FROM associados  WHERE matricula = med_autorizacoes.matricula) END AS  solicitante
       FROM med_autorizacoes  WHERE med_autorizacoes.matricula='".$FRM_matricula."'");

      $listaut= new ArrayIterator($dadosautorizacao);

      while($listaut->valid()):

        $dtinc = new ActiveRecord\DateTime($listaut->current()->dt_inclusao);
        $dtreal = new ActiveRecord\DateTime($listaut->current()->dt_realizacao);

        ?>
        <tr style="line-height: 30px;" uk-data-id="<?php echo tool::CompletaZeros(11,$listaut->current()->id); ?>" uk-data-tp="<?php echo $listaut->current()->tipo; ?>">
          <th class="uk-width uk-text-center" style="width:100px;" ><?php echo tool::CompletaZeros(11,$listaut->current()->id); ?></th>
          <td class="uk-width uk-text-center" style="width:50px;" >

          <?php  if($listaut->current()->tipo == 0){echo'C';}else{echo'E';} ?>

          </td>
          <td class="uk-width uk-text-center" style="width:120px;"><?php echo $dtinc->format('d/m/Y'); ?></td>
          <td class="uk-width uk-text-center" style="width:120px;"><?php echo $dtreal->format('d/m/Y'); ?></td>
          <td class="uk-text-left uk-text-uppercase"><?php echo $listaut->current()->solicitante; ?></td>
          <td class="uk-width uk-text-center" style="width:150px;">
           <?php 

           /*DEFINE O STATUS DA AUTORIZAÇÃO*/
           /* 0 aberta, 1 confirmada, 2 processada para pagamento, 3 paga, 4 cancelada */

           if($listaut->current()->status==0){
            $res='<div  class="uk-badge uk-badge-muted">Agendada!</div>';
          }elseif($listaut->current()->status==1){
            $res='<div  class="uk-badge ">Autorização Confirmada!</div>';
          }elseif($listaut->current()->status==2){
            $res='<div  class="uk-badge uk-badge-secundary">Processada pagamento!</div>';
          }elseif($listaut->current()->status==3){
            $res='<div  class="uk-badge uk-badge-success">Pagamento confirmado!</div>';
          }elseif($listaut->current()->status==4){
            $res='<div  class="uk-badge uk-badge-danger">Cancelada!</div>';
          }elseif($listaut->current()->status==5){
            $res='<div  class="uk-badge uk-badge-tertiary">Aguardando liberação!</div>';
          }else{$res="-";}
          echo $res;
          ?>
        </td>
      </tr>
      <?php
      $listaut->next();
    endwhile;
    ?>
  </tbody>
</table>
<script type="text/javascript" >

  jQuery("#GridAutorizacoesAssoc table tr").click(function(evento){
    var data_id   = jQuery(this).attr("uk-data-id");
    var data_tipo = jQuery(this).attr("uk-data-tp");

    if(data_tipo == 0){
      New_window('list','700','360','Autorização de Consulta','assets/medautorizacao/Frm_aut_consultas.php?matricula=<?php echo $FRM_matricula; ?>&autId='+data_id+'',true,false,'Carregando...');
    }else{
      New_window('list','700','550','Autorização de Exames','assets/medautorizacao/Frm_aut_exames.php?matricula=<?php echo $FRM_matricula; ?>&autId='+data_id+'',true,false,'Carregando...');
    }
  });

</script>