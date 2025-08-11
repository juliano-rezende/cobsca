<?php
error_reporting(0);
@ini_set('display_errors', '0');
@ini_set('register_globals', '0');
set_time_limit(0);
require_once("../conexao.php");
$cfg->set_model_directory('../models/');

// cogidos e decrições
/* codigos possiveis

"OK"://aceita
"OP"://enviada
"CL"://confirmada
"E4"://recusada
"E1"://blacklist
"E3"://duplicada
"E0"://invalida
"E7"://sem credito
"E6"://Expirado tentativa de entregue de 24 horas
"MO"://resposta

*/

function StatusSMS($idsms){		
	 $posting = "NumUsu=clifam&Senha=cf2514&SeuNum=".$idsms."";
	 $postlength = strlen($posting);
	 
	 $ktstring = "POST /reluzcap/wsreluzcap.asmx/StatusSMS HTTP/1.1\r\n";
	 $ktstring .= "Host: webservices.twwwireless.com.br\r\n";
	 $ktstring .= "Content-Length: $postlength\r\n";
	 $ktstring .= "Content-Type: application/x-www-form-urlencoded\r\n";
	 $ktstring .= "Connection: Close\r\n\r\n";
															  
	 $fp = fsockopen ("ssl://webservices.twwwireless.com.br", 443, $errno, $errstr, 30);
	 if (!$fp)
	 {
	 echo "$errstr ($errno)<br>\n";
	 }
	 else
	 {
	 fputs ($fp, $ktstring);
	 fputs ($fp, $posting );
	 $buffer = "";
	 while (!feof($fp))
	 {
	 $buffer .= fgets($fp,1024);
	 }
 
 fclose ($fp);

if(preg_match('@<OutDataSet xmlns="">(.*?) </OutDataSet>@si',$buffer, $matches)){
	
	$linhas=explode("</StatusSMS>",$matches[1]);
	 
	$totaldelinhas=count($linhas);
	 
	foreach( $linhas as $line){
									
			if(preg_match('@<seunum>(.*?)</seunum>@si', $line, $matches)){
				$nossonumero=$matches[1];
			}
			elseif(preg_match('@<celular>(.*?)</celular>@si', $line, $matches)){
				$Celular=substr($matches[1],2,10);
			}
			elseif(preg_match('@<status>(.*?)</status>@si', $line, $matches)){
				$Status=$matches[1];
			}
			elseif(preg_match('@<datarec>(.*?)</datarec>@si', $line, $matches)){
				$datarec=explode("T",$matches[1]);
				$data=$datarec[0];
				$hora=substr($datarec[1],0,8);
				$datahorarec=$data." ".$hora; 
			}
			elseif(preg_match('@<datastatus>(.*?)</datastatus>@si', $line, $matches)){
				$datastatus=explode("T",$matches[1]);
				$data=$datastatus[0];
				$hora=substr($datastatus[1],0,8);
				$datahorastatus=$data." ".$hora;
			}
			
				// query na tabela SMS
				$QuerySms=sms::find_by_nossonumero_AND_telefone($nossonumero,$Celular);
				if($QuerySms){
							/// faz o update
							$update=sms::find_by_idsms($QuerySms->idsms);
							$QuerySms->update_attributes(array(
							 'status'=>$Status,
							 'datareceb'=>$datahorarec,
							 'datastatus'=>$datahorastatus
							 ));
							 
							if($update){echo "Id: ".$QuerySms->idsms." Telefone: ".$Celular." - Atualizado<br />";}}else{
								echo "SMS não encontrato<br />";
							 }
							 }
	  }
 }		
								
}
echo  StatusSMS("1423230123");

?>
