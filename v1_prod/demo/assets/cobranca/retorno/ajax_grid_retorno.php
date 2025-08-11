<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">

<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_contas_bancarias_id= isset( $_POST['cod_contas_id_grid'])     ? $_POST['cod_contas_id_grid'] : tool::msg_erros("O Campo cod_contas_id_grid é Obrigatorio.");

/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
$limite =15; // Define o limite de registros a serem exibidos com o valor cinco

if(isset($_POST['pagina'])){

  $pagina = $_POST['pagina'];


  $inicio = ($pagina * $limite) - $limite;

  $limite_ini_fim="LIMIT ".$inicio.",".$limite."";

}else{

  $pagina = 1;

  $inicio = ($pagina * $limite) - $limite;

  $limite_ini_fim="LIMIT ".$inicio.",".$limite."";

}

// retorna todo os registros para montar a paginação
$Query_all=retornos::find('all', array('conditions' => array('contas_bancarias_id = ?', $FRM_contas_bancarias_id)));
$items=count($Query_all);

// monta a query especifica
$Query_retornos=retornos::find_by_sql("SELECT
                                        retornos_bancarios.id,
                                        retornos_bancarios.t_erros,
                                        retornos_bancarios.path,
                                        retornos_bancarios.contas_bancarias_id,
                                        retornos_bancarios.nm_arquivo,
                                        retornos_bancarios.dt_importacao,
                                        retornos_bancarios.dt_processamento,
                                        retornos_bancarios.t_linhas,
                                        retornos_bancarios.t_baixa,
                                        retornos_bancarios.t_compensados,
                                        retornos_bancarios.lote_retorno,
                                        retornos_bancarios.status,
                                        retornos_bancarios.cod_banco,
                                        contas_bancarias_cobs.tipo_arquivo
                                      FROM
                                        contas_bancarias_cobs
                                        INNER JOIN retornos_bancarios
                                      ON contas_bancarias_cobs.contas_bancarias_id =
                                      retornos_bancarios.contas_bancarias_id
                                      WHERE retornos_bancarios.empresas_id='".$COB_Empresa_Id."' AND retornos_bancarios.contas_bancarias_id='".$FRM_contas_bancarias_id."' ORDER BY id DESC ".$limite_ini_fim."");

$List_ret= new ArrayIterator($Query_retornos);

?>
</div>
<ul class="uk-pagination " style="position:absolute; top:52px; left:20px;" data-uk-pagination="{edges:5,items:<?php echo $items; ?>, itemsOnPage:16, currentPage:<?php echo $pagina;?>}"></ul>
<table  class="uk-table uk-table-striped uk-table-hover" >
<tbody>
<?php
$linha=0;

while($List_ret->valid()):

$linha++;

$dt_importacao  = new ActiveRecord\DateTime($List_ret->current()->dt_importacao);
$dt_processamento = new ActiveRecord\DateTime($List_ret->current()->dt_processamento);

?>

    <tr style="line-height:23px;" >
       <td class="uk-width uk-text-center" style="width:90px;" ><?php echo $linha; ?></td>
        <td class="uk-width uk-text-center" style="width:50px;" >
        <?php
        echo' <img src="imagens/icon_bancos/'.$List_ret->current()->cod_banco.'.png" alt="" width="20" height="15">';
        ?>
       </td>
       <td class="uk-width uk-text-center" style="width:100px;" >

        <?php if($List_ret->current()->t_erros > 0){echo'<i class="uk-icon-exclamation-triangle uk-text-warning" data-uk-tooltip="" title="" data-cached-title="Arquivo com inconsistencias"></i> ';} ?>
        <?php if($List_ret->current()->status == 1){echo'Processado';}else{echo'Aguardando';}
        ?>
       </td>
       <td class="uk-width uk-text-center" style="width:120px;" ><?php echo $dt_importacao->format('d/m/Y'); ?></td>
       <td class="uk-width uk-text-center" style="width:120px;" >
        <?php  if($List_ret->current()->dt_processamento != ""){ echo $dt_processamento->format('d/m/Y'); }else{  echo"00/00/00";  } ?>
       </td>
       <td class="uk-text-left" >
       <?php
       $arq=explode(".",$List_ret->current()->nm_arquivo);
       echo $arq[0];
       ?></td>
       <td class="uk-width uk-text-center uk-text-primary uk-text-bold" style="width:100px;" >
       <?php echo ($List_ret->current()->t_linhas -($List_ret->current()->t_baixa+$List_ret->current()->t_compensados+$List_ret->current()->t_erros)) ; ?>
        </td>
       <td class="uk-width uk-text-center uk-text-primary uk-text-bold" style="width:100px;" ><?php echo $List_ret->current()->t_baixa ; ?></td>
       <td class="uk-width uk-text-center uk-text-success uk-text-bold" style="width:100px;" ><?php echo $List_ret->current()->t_compensados ; ?></td>
       <td class="uk-width uk-text-center uk-text-danger uk-text-bold" style="width:100px;" ><?php echo $List_ret->current()->t_erros; ?></td>
       <td class="uk-width uk-text-center uk-text-bold" style="width:100px;" ><?php echo $List_ret->current()->t_linhas ; ?></td>
       <td class="uk-width uk-text-center" style="width:120px;" ><?php echo tool::Completazeros("12",$List_ret->current()->lote_retorno) ; ?></td>
       <td class="uk-width uk-text-center" style="width:80px;" >

          <div class="uk-button-group" style="position: absolute; margin: -10px">
              <div data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
                <button class="uk-button uk-button-primary uk-button-mini"><i class="uk-icon-caret-down"></i></button>
                <div class="uk-dropdown " aria-hidden="true" style="width: 150px;">
                  <ul class="uk-nav uk-nav-dropdown" style="text-align: left;">
                  <?php
                  if($List_ret->current()->status == 0){
                  ?>
                      <li>
                        <a href="javascript:void(0)" onclick="Process_Ret('<?php echo $List_ret->current()->id ; ?>','<?php echo tool::CompletaZeros(3,$List_ret->current()->cod_banco) ; ?>','<?php echo $List_ret->current()->tipo_arquivo; ?>','<?php echo $List_ret->current()->contas_bancarias_id; ?>');">
                        <i class="uk-icon-refresh"></i> Processar
                        </a>
                      </li>
                  <?php
                  }
                  if($List_ret->current()->status == 1){
                  ?>
                      <li>
                        <a href="javascript:void(0)" onclick="Detalhes('<?php echo $List_ret->current()->lote_retorno ; ?>','<?php echo $List_ret->current()->contas_bancarias_id ; ?>');">
                        <i class="uk-icon-list-alt"></i> Detalhes
                        </a>
                      </li>
                  <?php
                  }if($List_ret->current()->t_erros > 0){
                  ?>
                      <li>
                        <a href="assets/cobranca/retorno/controllers/Controller_dow_log.php?id=<?php echo $List_ret->current()->id; ?>" target="blank">
                        <i class="uk-icon-list"></i> Inconsistências
                        </a>
                      </li>
                  <?php } ?>
                  </ul>
                </div>
              </div>
          </div>




      </td>


    </tr>
<?php
$List_ret->next();
endwhile;
?>
</tbody>
</table>

 <script src="framework/uikit-2.24.0/js/components/pagination.min.js"></script>
<script type="text/javascript">

jQuery('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){

    // mensagen de carregamento
 jQuery("#msg_loading").html("Pesquisando ");

 //abre a tela de preload
 modal.show();

   jQuery.ajax({
        async: true,
        url: "assets/cobranca/retorno/ajax_grid_retorno.php",
        type: "post",
        data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroRetorno").serialize(),
        success: function(resultado) {
                            //abre a tela de preload
                           jQuery("#Grid_retorno").html(resultado);
                            //abre a tela de preload
              modal.hide();
        },
        error:function (){
          UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
          modal.hide();
          }

      });
});


function Process_Ret(id,banco,tipo_arquivo,conta_bancaria_id){


// mensagen de carregamento
jQuery("#msg_loading").html(" Processando... ");
//abre a tela de preload
modal.show();
//fecha o drop
jQuery(".uk-dropdown").hide();


$.post("assets/cobranca/retorno/controllers/Controller_processa_retorno_"+banco+"_"+tipo_arquivo+".php",{id:id},
                  function(resultado){

                        UIkit.modal.alert(""+resultado+"");

                         jQuery.ajax({
                                    async: true,
                                    url: "assets/cobranca/retorno/ajax_grid_retorno.php",
                                    type: "post",
                                    data:'cod_contas_id_grid='+conta_bancaria_id+'',
                                    success: function(resultado) {
                                                jQuery("#Grid_retorno").html(resultado);
                                                modal.hide();
                                    },
                                    error:function (){
                                      UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                                    }
                                });
                  });
}


function Detalhes(cod_retorno,conta_bancaria_id){
  New_window('search','980','520','Detalhes Retorno','assets/cobranca/retorno/detalhes_ret.php?cod_retorno='+cod_retorno+'&conta_bancaria_id='+conta_bancaria_id+'',true,false,'Carregando...');
  }

</script>