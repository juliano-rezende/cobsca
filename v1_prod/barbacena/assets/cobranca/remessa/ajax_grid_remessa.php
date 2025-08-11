<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_cod_banco= isset( $_POST['cod_banco_grid'])     ? $_POST['cod_banco_grid']
                                                     : tool::msg_erros("O Campo cod_banco é Obrigatorio.");

/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
if(isset($_POST['pagina'])){

  $pagina = $_POST['pagina'];

  $limite =15; // Define o limite de registros a serem exibidos com o valor cinco

  $inicio = ($pagina * $limite) - $limite;

  $limite_ini_fim="LIMIT ".$inicio.",".$limite."";

}else{

  $pagina = 1;

  $limite =15; // Define o limite de registros a serem exibidos com o valor cinco

  $inicio = ($pagina * $limite) - $limite;

  $limite_ini_fim="LIMIT ".$inicio.",".$limite."";

}

// retorna todo os registros para montar a paginação
$Query_all=remessas::find('all', array('conditions' => array('cod_banco = ?', $FRM_cod_banco)));
$items=count($Query_all);

// monta a query especifica

$Query_remessas=remessas::find_by_sql("SELECT * FROM remessas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND  cod_banco='".$FRM_cod_banco."' ORDER BY id DESC ".$limite_ini_fim."");
$List_rem= new ArrayIterator($Query_remessas);

?>
</div>
<ul class="uk-pagination " style="position:absolute; top:52px; left:20px;" data-uk-pagination="{edges:5,items:<?php echo $items; ?>, itemsOnPage:16, currentPage:<?php echo $pagina;?>}"></ul>
<table  class="uk-table uk-table-striped uk-table-hover" >
<tbody>
<?php
$linha=0;

while($List_rem->valid()):

$linha++;

$dt_criacao  = new ActiveRecord\DateTime($List_rem->current()->dt_criacao);
$dt_download = new ActiveRecord\DateTime($List_rem->current()->dt_download);

?>

    <tr style="line-height:23px;" >
       <td class="uk-width uk-text-center" style="width:90px;" ><?php echo $linha; ?></td>
       <td class="uk-width uk-text-center" style="width:100px;" >
        <?php if($List_rem->current()->status == 1){echo'<i class="uk-icon-check uk-text-success uk-text-large" >';}else{echo'Aguardando';}?>
       </td>
       <td class="uk-width uk-text-center" style="width:100px;" ><?php echo $dt_criacao->format('d/m/Y'); ?></td>
       <td class="uk-width uk-text-center" style="width:150px;" >
        <?php  if($List_rem->current()->dt_download != ""){ echo $dt_download->format('d/m/Y'); }else{  echo"00/00/00";  } ?>
       </td>
       <td class="uuk-text-left" >
       <?php
       $arq=explode(".",$List_rem->current()->nm_arquivo);
       echo $arq[0];
       ?></td>
       <td class="uk-width uk-text-center" style="width:120px;" ><?php echo $List_rem->current()->linhas ; ?></td>
       <td class="uk-width uk-text-center" style="width:120px;" ><?php echo tool::Completazeros("12",$List_rem->current()->lote_remessa) ; ?></td>
       <td class="uk-width uk-text-center" style="width:120px;" >

          <div class="uk-button-group" style="position: absolute; margin: -10px">
              <div data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
                <button class="uk-button uk-button-primary uk-button-mini"><i class="uk-icon-caret-down"></i></button>
                <div class="uk-dropdown " aria-hidden="true" style="width: 150px;">
                  <ul class="uk-nav uk-nav-dropdown" style="text-align: left;">
                      <li>
                        <a href="assets/cobranca/remessa/controllers/Controller_dow_remessa.php?id=<?php echo $List_rem->current()->id; ?>&arq=<?php echo $List_rem->current()->path; ?>&nm=<?php echo $List_rem->current()->nm_arquivo; ?>" target="blank" >
                        <i class="uk-icon-download"></i> Download
                        </a>
                      </li>
                      <li>
                        <a href="javascript:void(0)" onclick="Detalhes('<?php echo $List_rem->current()->lote_remessa ; ?>');">
                        <i class="uk-icon-list-alt"></i> Detalhes
                        </a>
                      </li>
                  </ul>
                </div>
              </div>
          </div>
      </td>
    </tr>
<?php
$List_rem->next();
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
        url: "assets/cobranca/remessa/ajax_grid_remessa.php",
        type: "post",
        data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroRemessa").serialize(),
        success: function(resultado) {
                            //abre a tela de preload
                           jQuery("#Grid_remessa").html(resultado);
                            //abre a tela de preload
              modal.hide();
        },
        error:function (){
          UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
          modal.hide();
          }

      });
});


function Detalhes(cod_remessa){
  New_window('search','980','520','Detalhes Retorno','assets/cobranca/remessa/detalhes_rem.php?cod_remessa='+cod_remessa+'',true,false,'Carregando...');
  }
</script>