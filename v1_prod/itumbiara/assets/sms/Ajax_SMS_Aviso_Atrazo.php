<?php
error_reporting(0);
@ini_set('display_errors', '0');
@ini_set('register_globals', '0');
set_time_limit(0);
require_once"../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php
include("Function_SMS.php");
require_once("../conexao.php");
$cfg->set_model_directory('../models/');
?>
</div>
<div id="list_sms_vencimento" style="display:block;background-color: transparent;overflow:auto;height:600px;width:99%;">
<?php

// seleciona todos os convenios da empresa pessoa fisica
$QueryConvenios=convenios::find_by_sql("SELECT * FROM tbconvenios WHERE tipodoconvenio='F' AND ativo='S' AND cdempresa='".$SCA_Id_empresa."' ");
$listaconvenios= new ArrayIterator($QueryConvenios);
// Loop para exibir o resultado
while($listaconvenios->valid()):
echo'<hr>';
// imprimi o nome do convênio na tela 
echo $listaconvenios->current()->fantasia."<br />";

$linha=0;
// query na tabela faturamentos
$QueryFaturamentos=faturamento::find_by_sql("SELECT * FROM tbfaturamentos WHERE cdconvenio='".$listaconvenios->current()->cdconvenio."' AND referencia='".$_GET['referencia']."' AND status='0' AND nossonumero>'0' AND cdempresa='".$SCA_Id_empresa."'  group by matricula");
// Loop para exibir o resultado
$listaFaturamentos= new ArrayIterator($QueryFaturamentos);
while($listaFaturamentos->valid()):
		
		$dadosassociado=associados::find_by_matricula($listaFaturamentos->current()->matricula);
		
			if($dadosassociado->fonecel=="" || $dadosassociado->fonecel=="NULL"){
				
					if($dadosassociado->fonefixo!="" & $dadosassociado->fonefixo!="NULL"){
					
						$valida=substr($dadosassociado->fonefixo,2,1);
						
							if($valida>3){
											$fone= $dadosassociado->fonefixo;
										 }
						}
			 
			  }else{
					$fone= $dadosassociado->fonecel;
					}
				

$matricula=$listaFaturamentos->current()->matricula;

$dataVENC= new ActiveRecord\DateTime($listaFaturamentos->current()->datavencimento);

$msg=utf8_encode("UNIFAMILIA/CLIFAM,sua mensalidade no valor de R$ ".number_format($listaFaturamentos->current()->valorparcela,2,",",".")." com o vencimento em ".$dataVENC->format('d/m/Y ')." Consta em aberto,favor entrar em contato: 34-36623088 ( matricula ".$matricula." )");

$idsms=microtime();

$envia=EnviarSMS($msg,$fone,$idsms);// funcao para envio do sms mensagem/telefone/matricula // envia o sms
		
$sms=str_replace(" ","",substr($envia, -3));// retorno do envia

	
// cria o registro no banco de dados
$create= sms::create(
						array(
							'matricula'=>$matricula,
							'telefone'=>$fone,
							'msg'=>$msg,
							'status' => $sms,
							'dataenvio'=>date("Y-m-d h:m:s"),
							'datareceb'=>'0000-00-00 00:00:00',
							'datastatus'=>'0000-00-00 00:00:00',
							'nossonumero'=>$idsms,
							'cdempresa'=>$SCA_Id_empresa
							));			

$create= historico::create(
							array(
								'cdempresa'=>$SCA_Id_empresa,
								'cdusuario'=>$SCA_Id_usuario,
								'matricula'=>$matricula,
								'tipo' => "1",
								'historico'=>"SMS enviado com a seguinte mensagem: ".$msg.""
							));


echo'<div   style=" margin:0 auto; height:40px; width:100%;padding:2px; ">';
echo $linha.' Mensagem enviada para: '.$fone.' Status da Mensagem:  Codigo da Mensagem :'.$idsms;
echo'</div>';

$linha++;
		
	$listaFaturamentos->next();
	endwhile;
echo'<hr>';	
$listaconvenios->next();
endwhile;
?>
</div>