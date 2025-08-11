<?php

function EnviarSMS($msg,$fone,$idmsg){
		
 $posting = "NumUsu=clifam&Senha=cf2514&SeuNum=".$idmsg."&Celular=55".$fone."&Mensagem=".urlencode($msg);
 $postlength = strlen($posting);
 
 $ktstring = "POST /reluzcap/wsreluzcap.asmx/EnviaSMS HTTP/1.1\r\n";
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
 $buffer .= fgets ($fp,1024);
 }
 fclose ($fp);
 
  return strip_tags($buffer);// retorno do envia
 }		
								
}

                            

?>
