<?php

$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$ref = date("Y-m") . "-01";
$dia = date('d');

set_time_limit(0);

// recupera as taxas configuradas na empresa
$dados_config = configs::find_by_empresas_id(1);
$Query_conta_deposito = contas_bancarias::find_by_sql("SELECT id FROM contas_bancarias WHERE empresas_id='1' AND tp_conta='0' AND status='1'");

$query_transations = "SELECT dd.id as iddadocobranca, dd.matricula, dd.associados_card_id, dd.valor, ac.number_card,
                    dd.dt_venc_p, dd.dt_venc_cob,dd.api_cob_cliente_id, ac.api_cob_card_id, fat.id as fatid, fat.dt_vencimento,fat.referencia,
                    IFNULL((select count(status) from transacoes_cards tc
                    where dd.matricula = tc.matricula AND  DATE_FORMAT(tc.dt_envio, '%Y%m') = '" . date("Ym") . "' and tc.status = 0 ),0)  as negadas
                    FROM dados_cobranca  as dd
                    INNER JOIN associados_cards ac ON ac.id = dd.associados_card_id
                    INNER JOIN faturamentos fat ON fat.matricula = dd.matricula
                    LEFT JOIN transacoes_cards tran ON tran.faturamentos_id = fat.id
                    WHERE
                    NOT EXISTS (SELECT id FROM transacoes_cards
                    WHERE faturamentos_id = fat.id AND dt_envio = '" . date("Y-m-d") . "' AND tran.card_number = ac.number_card )
                    
                    AND dd.forma_cobranca_id = 4 
                    
                    AND fat.referencia = '" . $ref . "' AND fat.status = 0  AND dd.matricula != 533
                    GROUP BY dd.id, dd.matricula, dd.associados_card_id, dd.valor, ac.number_card, dd.api_cob_cliente_id,
                    ac.api_cob_card_id, fat.id , fat.dt_vencimento LIMIT 1";


$Query_cob_cards = dados_cobranca::find_by_sql($query_transations);

$listfat = new ArrayIterator($Query_cob_cards);


while ($listfat->valid()):


    $dados_cobranca_id = $listfat->current()->iddadocobranca;

    $referencia_fat = $listfat->current()->referencia;

    $dt_venc = $listfat->current()->dt_vencimento;

    $dt_venc_p = $listfat->current()->dt_venc_p;


    $endPoint = "http://54.39.26.99";
    $tokenAcessoFuturaApi = "api*futura#cob2021&";
    $apiCompanyId = "2";


    $amount = number_format($listfat->current()->valor, 2, "", "");
    $amount = str_replace(".", "", $amount);
    $description = removeAccentsUppercase("Mensalidade Cartao Mais Saúde");

    $reference_id = $referencia_fat;
    $ApiCobClienteId = $listfat->current()->api_cob_cliente_id;
    $ApiCobCardId = $listfat->current()->api_cob_card_id;

    $dtvencto = $dt_venc;
    $idfaturamento = $listfat->current()->fatid;
    $vencimentobase = $dt_venc_p;

    $jsonTransation = '{
		    "amount": "' . $amount . '",					
		    "currency": "BRL",
		    "capture": "true",
		    "description": "' . $description .' | '. $listfat->current()->matricula.' | '. $reference_id.'",
		    "reference_id": "' . $reference_id . '",
		    "on_behalf_of": "0",
		    "payment_type": "credit",
		    "source": {
		        "usage": "reusable",
		        "amount": "' . $amount . '",
		        "currency": "BRL",
		        "description": "' . $description . '",
		        "type": "card",
		        "card": {
		            "holder_name": "",
		            "expiration_month": "",
		            "expiration_year": "",
		            "card_number": "",
		            "security_code": ""
		        }
		    },
		    "installment_plan":{
		    	"mode":"interest_free",          
		    	"number_installments":"1"        
		    },
		    "apicob":{
		    	"apicobclienteid": "' . $ApiCobClienteId . '",
		    	"apicobcardid": "' . $ApiCobCardId . '"
		    },
             "movimento":{
		    	"dtvencto": "' . $dtvencto . '",
		    	"idfaturamento": "' . $idfaturamento . '",		    	
		    	"vencimentobase":"' . $vencimentobase . '",		    	
                "apicobfaturamentoid": 0
		    },
		    "cliente":
			    {
			    "matricula": "",
			    "nome": "",
			    "datanasc":"",
	             "email":"",
	             "documento":"",
	             "telefone":"",                       
			     "cep": "",
			     "endereco": "",
			     "complemento": "",
	             "bairro": "",
	             "cidade": "",
	             "estado": ""                        
			    }
	    }';




    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "{$endPoint}/persontransacaocard",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{$jsonTransation}",
        CURLOPT_HTTPHEADER => array(
            "token:{$tokenAcessoFuturaApi}",
            "empresaid:{$apiCompanyId}",
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $returnApi = json_decode($response);

    var_dump($returnApi);

    sleep(1);
    $listfat->next();
endwhile;


/**
 * função reponsavel por deixar apenas numeros nas variaveis cpf, cnpj, cep, telefone
 * @param $number
 * @return string|string[]|null
 */
function cleanNumbers($number)
{
    $number = preg_replace("/[^0-9]/", "", $number);
    return $number;
}

/**
 * função para remover acentos e converter para caixa alta
 * @param $string
 * @return string|string[]|null
 */
function removeAccentsUppercase($string)
{
    $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    $string = strtoupper($string);
    return $string;
}


