<?php

require_once  "../classes/FuturaApi/PaymentApiFutura.php";

use classes\FuturaApi\PaymentApiFutura;


 /* consulta seller pelo id 

 $objId = new PaymentApiFutura();
 $objId->setConfig("af4ce64d347d45ee9230d28303abdd30","HOMOLOGACAO");
 $resultId = $objId->getSellerId("{$idSeller}");
 $resultId = json_decode($resultId, true);
 echo "Este documento já pertence a um Seller -> nome do seller ".$resultId["first_name"];
 echo"<br /><br />";
 
 */




 /* consulta seller pelo doc 

$objDoc = new PaymentApiFutura();
$objDoc->setConfig("af4ce64d347d45ee9230d28303abdd30","HOMOLOGACAO");
$doc = "94770867034";
$resultDoc = $objDoc->getSellerDoc("{$doc}");
$resultDoc = json_decode($resultDoc, true);
echo "Este documento já pertence a um Seller -> nome do seller ".$resultDoc["id"];
echo"<br /><br />";
*/




/*json create seller*/
$json = '{
"first_name":"Juliano",
"last_name":"Rezende",
"email":"julianoreze@gmail.com",
"phone_number":"+5534997630303",
"taxpayer_id":"18593137520",
"birthdate":"1981-02-20",
"statement_descriptor":"JREZENDE-ME",
"description":"SISTEMAS DE COBRANCA",
"address":{
"line1":"RUA FRANCISCO DO SANTOS",
"line2":"104",
"line3":"",
"city":"araxa",
"state":"MG",
"neighborhood":"CENTRO",
"postal_code":"38183238",
"country_code":"BR"
},
"mcc": "1"
}';


/* create seller */
$objSeller = new PaymentApiFutura();
$objSeller->setConfig("af4ce64d347d45ee9230d28303abdd30","HOMOLOGACAO");
$objResult = $objSeller->addSeller("{$json}");

var_dump($objResult);

if(isset($objResult ["fault"])){
  
    echo $objResult ["fault"]["detail"];
  
}else{
  
 echo $objResult ["id"];
  
}

<div class="ic2"><i class="fa fa-image" title="Enviar Imagens"></i></div>






