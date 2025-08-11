<?php 
require_once"../../sessao.php";
include("Function_SMS.php");
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
include("../../functions/funcoes.php");
 
$cdpaciente=$_POST['cdpaciente'];
$msg=$_POST['msg'];
$fone=limpamascara($_POST['fone']);
$idsms=time();

$envia=EnviarSMS($msg,$fone,$idsms);// funcao para envio do sms mensagem/telefone/matricula // envia o sms
		
$sms=str_replace(" ","",substr($envia, -3));// retorno do envia

	
/*cria o registro no banco de dados
$create= sms::create(
						array(
							'cdpaciente'=>$cdpaciente,
							'telefone'=>$fone,
							'msg'=>$msg,
							'status' => $sms,
							'dataenvio'=>date("Y-m-d h:m:s"),
							'datareceb'=>date("Y-m-d h:m:s"),
							'datastatus'=>date("Y-m-d h:m:s"),
							'nossonumero'=>$idsms,
							'cdempresa'=>$SCA_Id_empresa
							));			

$create= historico::create(
							array(
								'cdempresa'=>$SCM_Id_empresa,
								'cdusuario'=>$SCM_Id_usuario,
								'cdpaciente'=>$cdpaciente,
								'tipo' => "1",
								'historico'=>"SMS enviado com a seguinte mensagem: ".$msg.""
							));
*/
echo'Mensagem enviada para: '.$fone."\n";
echo'Status da Mensagem: '.$sms."\n";
echo'Codigo da Mensagem :'.$idsms."\n";
?>