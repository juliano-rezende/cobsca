<?php

include("../../../../../conexao.php");
$cfg->set_model_directory('../../../../../models/');


/* variavel com os ids das parcelas*/

$FRM_parcelas_id 		= isset( $_GET['ids']) 	? $_GET['ids']
												: tool::msg_erros("O Campo ids Obrigatorio faltando.");

// string contendo o id dos titulos a imprimir
$FRM_pa = explode(",",$FRM_parcelas_id);


$qte					= 0;
$bol					= 0; // inica a quantidade de boletos por folha como zero
?>

<nav class="uk-navbar" style="padding:8px 18px 8px 0px; text-align: right;">
	<!--<a  onclick="Pdf_veiw('<?php echo $FRM_parcelas_id; ?>');"><i class="uk-icon-file-pdf-o uk-icon-medium "></i></a>-->
	<a  onclick="Print();">  <i class="uk-icon-print uk-icon-medium "></i>  </a>
	<!--<a  onclick="Pdf_dow('<?php echo $FRM_parcelas_id; ?>');"><i class="uk-icon-floppy-o uk-icon-medium "></i></a>-->
	<!--<a  onclick="Pdf_email('<?php echo $FRM_parcelas_id; ?>');"><i class="uk-icon-envelope-o uk-icon-medium "></i></a>-->
</nav>

<?php

// ABRE A DIV DE IMPRESSÃO
echo'<div id="print_c" style=" height:505px;width:100%; overflow:auto; padding:0; margin:0 auto;background:#fff; ">';
	// loopa os titulos
	foreach ($FRM_pa as $id_faturamento){

	$validar_parcela=faturamentos::find($id_faturamento);

	if($validar_parcela->tipo_parcela == "A"){continue;}

	$qte++;
	$bol++;// conta os boletos

		require_once("include/funcoes_santander.php");// carrega as funções do boleto
		require("include/dados_boleto.php");

		echo'<div style="width:870px; height:380px; padding:0; padding-bottom:8px; margin:-2px auto; border:0px solid #ccc;background:#fff;" >';
		require("include/layout_santander.php");
		echo'</div><br>';


	if(count($FRM_parcelas_id) > 3 and $bol == 3){
		echo '<p style="page-break-before:always"></p>';
		$bol=0;
	}

	}

// FINALIZA A DIV DE IMPRESSA
echo'</div>';
?>

<div id="Col_pdf_email" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
        <h2><i class="uk-icon-envelope-o" ></i> Boleto por e-mail</h2>
        </div>
		<form method="post" id="FrmRecebimento" class="uk-form" style="padding-top:0;margin:0;">

		<fieldset style="width:500px; background-color:transparent;">

		            <label>
		                <span>E-mail</span>
		                  <input  type="text" class="input_text w_300 center" name="email" id="email" autocomplete="off"/>
		            </label>
		</fieldset>
		</form>
        <div class="uk-modal-footer uk-text-right">
            <a id="Btn_email_confirm" class="uk-button uk-button-primary uk-button-small" ><i class="uk-icon-search" ></i> Confirmar</a>
            <a id="btn_email_cancel" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
    </div>
</div>


<script type="text/javascript">

function Pdf_veiw(ids){

 var LeftPosition = (screen.width) ? (screen.width-980)/2 : 0;

window.open("<?php echo dirname($_SERVER['PHP_SELF']); ?>/veiw_pdf.php?action=&ids="+ids+"",'Impressão', 'width=980, height=550, top=100, left='+LeftPosition+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');

}
function Print(action){

  $( "#print_c" ).print();

}
</script>

