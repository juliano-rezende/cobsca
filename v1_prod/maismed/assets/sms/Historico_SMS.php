<?php
header("Content-type: text/html;charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Sempre modificado
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once"../sessao.php";


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
  <title>Historico</title>
</head>
<body>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../conexao.php");
$cfg->set_model_directory('../models/');
require_once("../functions/funcoes.php");

$historicosms=sms::find_by_sql("SELECT * FROM tbsms WHERE  matricula='".$_GET['matricula']."' AND cdempresa='".$SCA_Id_empresa."' ORDER BY dataenvio DESC");
// coloca em um array
$list= new ArrayIterator($historicosms);
$linhaarray=0;
?>
</div>
<div id="veiw_config" style=" overflow-y:scroll; height:500px;">
<?php
while($list->valid()): 
// formata a data e hora
$dataenvio = new ActiveRecord\DateTime($list->current()->dataenvio);
$datareceb = new ActiveRecord\DateTime($list->current()->datareceb);
$datastatus = new ActiveRecord\DateTime($list->current()->datastatus);


$st=$list->current()->status;

switch ($st) {
    case "OP":
		$status= "Enviada ao Usuario";
		$corfundo="00BFFF";
        break;
    case "CL":
		$status= "Confirmada pela Operadora";
		$corfundo="008B8B";
       break;
    case "E4":
		$status= "Recusada pela Operadora";
		$corfundo="FF4500";
       break;
    case "E1":
		$status= "Balcklist";
		$corfundo="000000";
       break;
    case "E3":
		$status= "Duplicada";
		$corfundo="FFD700";
       break;
    case "E0":
		$status= "Invalida";
		$corfundo="800000";
       break;
    case "E7":
		$status= "Sem Credito";
		$corfundo="D2691E";
       break;
    case "E6":
		$status= "Expirado";
		$corfundo="BDB76B";
       break;
    case "MO":
		$status= "Resposta";
		$corfundo="556B2F";
       break;
	   default:
	   $status= "Aceita pela Operadora";
	   $corfundo="00008B";

}
?>
<!-- ###################################################################### conta cobrança ###################################################### -->
<form name="fr_contacobranca" method="post" id="fr_contacobranca" > 

<fieldset style="width:600px;margin-bottom:5px;background-color:#f5f5f5;">

<legend style="background-color:#<?php echo $corfundo; ?>; cursor:pointer; line-height:10px;" onClick="Toggleall('<?php echo $list->current()->idsms;?>');">

<?php
echo "Nº ( ".$list->current()->nossonumero." ) Data: ". $dataenvio->format('d/m/Y'); 
?>

<a class="toggle" style="float:right; top:-2px; right:-3px; position: relative; color:#fff;" href="JavaScript:void(0);" ><i class="icon-down-open" id="iconsms_<?php echo $list->current()->idsms;?>"></i></a>

</legend>  
<div class="bloco" id="historicosms_<?php echo $list->current()->idsms;?>" style="padding:5px; background-color: #ffffff;">

<span style=" line-height:25px;">Data Envio: <strong>
<?php  echo $dataenvio->format('d/m/Y h:m:s');  ?>
</strong></span><br>
<span style=" line-height:25px;">Data Recebimento: <strong>
<?php  echo $datareceb->format('d/m/Y h:m:s');  ?>
</strong></span><br>
<span style=" line-height:25px;">Data Atualização de Status: <strong><?php echo  $datastatus->format('d/m/Y h:m:s');  ?></strong></span><br>
<span style=" line-height:25px;">Status: <strong><?php echo  $status;  ?></strong></span><br>
<span style=" line-height:25px;">Mensagem: <strong><?php  echo $list->current()->msg;  ?></strong></span>

</div>
</fieldset >
</form>
<?php

$linhaarray++;
$list->next();
endwhile; 
?>
</div>  
  
</body>
<script type="text/javascript" >

$('.bloco').toggle();

function Toggleall(id) {
		  var idhist=id;
		  $('#historicosms_'+idhist+'').slideToggle();
		  $('#iconsms_'+idhist+'').toggleClass( "icon-up-open" );
}

</script>

</html>