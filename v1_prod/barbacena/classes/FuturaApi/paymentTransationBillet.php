<?php


class paymentTransationBillet
{

    /**
     * @var string
     */
    private $endPoint;
    /**
     * @var
     */
    private $apiCompanyId;
    /**
     * @var
     */
    private $tokenAcessoFuturaApi;
    /**
     * @var
     */
    private $amount, $description, $reference_id, $ApiCobClienteId, $ApiCobCardId;
    /**
     * @var
     */
    private $dtvencto, $idfaturamento, $vencimentobase, $apicobfaturamentoid,$dtlimitpgto;
    /**
     * @var
     */
    private $nome, $datanasc, $email, $documento, $telefone, $cep, $endereco, $complemento, $bairro, $cidade, $estado;

   var  $instrucoes;


    /**
     * paymentBuyer constructor.
     */
    public function __construct()
    {
        $this->endPoint = "http://54.39.26.99";

    }

    /**
     * @param $token
     * @return $this
     */
    public function setToken($token): paymentTransationCard
    {
        $this->tokenAcessoFuturaApi = $token;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setCompany($id): paymentTransationCard
    {
        $this->apiCompanyId = $id;
        return $this;
    }

    /**
     *      * Seta o valor da transação
     * @param $amount
     * @return $this
     */
    public function setAmoutTransation($amount): paymentTransationCard
    {
        $this->amount = number_format($amount,2,"","");
        $this->amount =  str_replace(".","",$this->amount);
        return $this;
    }

    /**
     * define uma descrição para a transação
     * @param $description
     * @return $this
     */
    public function setDescriptionTransation($description): paymentTransationCard
    {
        $this->description = self::removeAccentsUppercase($description);
        return $this;
    }

    /**
     * Seta a referencia da parcela
     * @param $reference_id
     * @return $this
     */
    public function setReferenceIdTransation($reference_id): paymentTransationCard
    {
        $this->reference_id = $reference_id;
        return $this;
    }

    /**
     * @param $ApiCobClienteId
     * @return $this
     */
    public function setApiCobClienteId($ApiCobClienteId): paymentTransationCard
    {
        $this->ApiCobClienteId = self::cleanNumbers($ApiCobClienteId);
        $this->ApiCobClienteId = intval($this->ApiCobClienteId);
        return $this;
    }


    /**
     * @param $dtvencto
     * @return paymentTransationCard
     * "dtvencto": 5,
     * "idfaturamento": 10,
     * "vencimentobase": "data de vencimento base presenta na tabela dados de cobrança",
     * "apicobfaturamentoid": 0
     */
    public function setMovimentoTransation($dtvencto, $idfaturamento, $diavencbase,$instrucoes, $apicobfaturamentoid = 0): paymentTransationCard
    {
        $this->dtvencto = $dtvencto ; // YYYY/mm/dd
        $this->dtlimitpgto = date('Y/m/d', strtotime('+1 days', strtotime(''.$dtvencto.'')));  // YYYY/mm/dd
        $this->instrucoes = self::removeAccentsUppercase($instrucoes);
        $this->idfaturamento = intval($idfaturamento); // id da tabela faturamento no sistema de cobrança
        $this->vencimentobase = $diavencbase; // dia de vencimento base das parcelas presente na tabela de dados de cobnança
        $this->apicobfaturamentoid = $apicobfaturamentoid; // id de ideintificação da parcela na tabela de faturamentos na apicob

        return $this;

    }

    /**
     * @param $nome
     * @param $datanasc
     * @param $email
     * @param $documento
     * @param $telefone
     * @param $cep
     * @param $endereco
     * @param $complemento
     * @param $bairro
     * @param $cidade
     * @param $estado
     * @return $this
     */

    public function setDetailsClientTransation($nome, $datanasc, $email, $documento, $telefone, $cep, $endereco, $complemento, $bairro, $cidade, $estado): paymentTransationCard
    {
        $this->nome = self::removeAccentsUppercase($nome);
        $this->datanasc = $datanasc;
        $this->email = $email;
        $this->documento = self::cleanNumbers($documento);
        $this->telefone = self::cleanNumbers($telefone);
        $this->cep = self::removeAccentsUppercase($cep);
        $this->endereco = self::removeAccentsUppercase($endereco);
        $this->complemento = self::removeAccentsUppercase($complemento);
        $this->bairro = self::removeAccentsUppercase($bairro);
        $this->cidade = self::removeAccentsUppercase($cidade);
        $this->estado = self::removeAccentsUppercase($estado);

        return $this;

    }


    /**
     * @return false|string
     */
    public function addTransationCorrence()
    {

        $jsonTransation ='{
                            "amount": ' . $this->amount . ',
                            "currency": "BRL",
                            "description": "' . $this->description . '",
                            "reference_id": "' . $this->reference_id . '",
                            "on_behalf_of": "0",
                            "customer": "0",
                            "payment_type": "boleto",
                            "logo": "http://futurati.com.br/wp-content/uploads/2019/12/cropped-FUTURATI_LOGOH-2.png",
                            "payment_method": {
                            "expiration_date": "'.$this->dtvencto.'",
                            "payment_limit_date": "'.$this->dtlimitpgto.'",
                            "body_instructions": "'.$this->instrucoes.'",
                            "billing_instructions": {
                            "late_fee": {
                            "mode": "FIXED",
                            "amount": ' . $this->amount . '
                            },
                            "interest": {
                            "mode": "DAILY_AMOUNT",
                            "amount": 30
                            },
                            "discount": [
                            {
                            "mode": "FIXED",
                            "limit_date": "2020-06-20",
                            "amount": 150
                            },
                            {
                            "mode": "FIXED",
                            "limit_date": "2020-06-25",
                            "amount": 100
                            }],
                            }
                            },
                            "apicob":{ 
                              "apicobclienteid":"72"
                               }, 
                            "movimento":{ 
                              "dtvencto":"2021-03-10", 
                              "idfaturamento":"22118", 
                              "vencimentobase":"10", 
                              "apicobfaturamentoid":"0" 
                            }, 
                            "cliente":{ 
                              "nome":"TESTE DE EMISSAO DE BOLETOS", 
                              "datanasc":"2005-06-22", 
                              "email":"", 
                              "documento":"40357264193", 
                              "telefone":"", 
                              "cep":"38182470", 
                              "endereco":"RUA  AMADOR CARNEIRO DE MELO, 10", 
                              "complemento":"", 
                              "bairro":"PARQUE DAS MANGABEIRAS", 
                              "cidade":"ARAXA", 
                              "estado":"MG" 
                              } 
                            }';


        $jsonTransation = '{
		    "amount": "' . $this->amount . '",					
		    "currency": "BRL",
		    "capture": "true",
		    "description": "' . $this->description . '",
		    "reference_id": "' . $this->reference_id . '",
		    "on_behalf_of": "0",
		    "payment_type": "credit",
		    "source": {
		        "usage": "reusable",
		        "amount": "' . $this->amount . '",
		        "currency": "BRL",
		        "description": "' . $this->description . '",
		        "type": "card",
		        "card": {
		            "holder_name": "' . $this->holder_name . '",
		            "expiration_month": "' . $this->expiration_month . '",
		            "expiration_year": "' . $this->expiration_year . '",
		            "card_number": "' . $this->card_number . '",
		            "security_code": "' . $this->security_code . '"
		        }
		    },
		    "installment_plan":{
		    	"mode":"interest_free",          
		    	"number_installments":"' . $this->installments . '"        
		    },
		    "apicob":{
		    	"apicobclienteid": "' . $this->ApiCobClienteId . '",
		    	"apicobcardid": "' . $this->ApiCobCardId . '"
		    },
             "movimento":{
		    	"dtvencto": "' . $this->dtvencto . '",
		    	"idfaturamento": "' . $this->idfaturamento . '",		    	
		    	"vencimentobase":"' . $this->vencimentobase . '",		    	
                "apicobfaturamentoid": "' . $this->apicobfaturamentoid . '"
		    },
		    "cliente":
			    {
			    "nome": "' . $this->nome . '",
			    "datanasc":"' . $this->datanasc . '",
	             "email":"' . $this->email . '",
	             "documento":"' . $this->documento . '",
	             "telefone":"' . $this->telefone . '",                       
			     "cep": "' . $this->cep . '",
			     "endereco": "' . $this->endereco . '",
			     "complemento": "' . $this->complemento . '",
	             "bairro": "' . $this->bairro . '",
	             "cidade": "' . $this->cidade . '",
	             "estado": "' . $this->estado . '"                        
			    }
	    }';


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->endPoint}/persontransacaocard",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{$jsonTransation}",
            CURLOPT_HTTPHEADER => array(
                "token:{$this->tokenAcessoFuturaApi}",
                "empresaid:{$this->apiCompanyId}",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $returnApi = json_decode($response);


        if ($returnApi->result != "success") {
            $callBack["error"] = "true";
            $callBack["message"] = $returnApi->message;
        } else {
            $callBack["error"] = "false";
            $callBack["message"] = $returnApi->message;
            $callBack["scafaturamentoid"] = $returnApi->scafaturamentoid;
            $callBack["apicobtransacaoid"] = $returnApi->apicobtransacaoid;
            $callBack["idtransacao"] = ($returnApi->payment_authorization)->idtransacao;
            $callBack["statusTrasation"] = ($returnApi->payment_authorization)->status;
            $callBack["transactionNumber"] = ($returnApi->payment_authorization)->transaction_number;
            $callBack["authorizerId"] = ($returnApi->payment_authorization)->authorizer_id;
            $callBack["autorizationNsu"] = ($returnApi->payment_authorization)->authorization_nsu;
            $callBack["expectedOnCredit"] = ($returnApi->payment_authorization)->expected_on;
            $callBack["apicobclienteid"] = $returnApi->apicobclienteid;
            $callBack["apifuturabuyerid"] = $returnApi->apifuturabuyerid;
        }
        return $response;

    }

    /**
     * @return false|string
     */

    public function AddTransationCard()
    {

        $jsonTransation = '{
		    "amount": "' . $this->amount . '",					
		    "currency": "BRL",
		    "capture": "true",
		    "description": "' . $this->description . '",
		    "reference_id": "' . $this->reference_id . '",
		    "on_behalf_of": "0",
		    "payment_type": "credit",
		    "source": {
		        "usage": "reusable",
		        "amount": "' . $this->amount . '",
		        "currency": "BRL",
		        "description": "' . $this->description . '",
		        "type": "card",
		        "card": {
		            "holder_name": "' . $this->holder_name . '",
		            "expiration_month": "' . $this->expiration_month . '",
		            "expiration_year": "' . $this->expiration_year . '",
		            "card_number": "' . $this->card_number . '",
		            "security_code": "' . $this->security_code . '"
		        }
		    },
		    "installment_plan":{
		    	"mode":"interest_free",          
		    	"number_installments":"' . $this->installments . '"        
		    },
		    "apicob":{
		    	"apicobclienteid": "' . $this->ApiCobClienteId . '",
		    	"apicobcardid": "' . $this->ApiCobCardId . '"
		    },
             "movimento":{
		    	"dtvencto": "' . $this->dtvencto . '",
		    	"idfaturamento": "' . $this->idfaturamento . '",		    	
		    	"vencimentobase":"' . $this->vencimentobase . '",		    	
                "apicobfaturamentoid": "' . $this->apicobfaturamentoid . '"
		    },
		    "cliente":
			    {
			    "nome": "' . $this->nome . '",
			    "datanasc":"' . $this->datanasc . '",
	             "email":"' . $this->email . '",
	             "documento":"' . $this->documento . '",
	             "telefone":"' . $this->telefone . '",                       
			     "cep": "' . $this->cep . '",
			     "endereco": "' . $this->endereco . '",
			     "complemento": "' . $this->complemento . '",
	             "bairro": "' . $this->bairro . '",
	             "cidade": "' . $this->cidade . '",
	             "estado": "' . $this->estado . '"                        
			    }
	    }';


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->endPoint}/persontransacaocard",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{$jsonTransation}",
            CURLOPT_HTTPHEADER => array(
                "token:{$this->tokenAcessoFuturaApi}",
                "empresaid:{$this->apiCompanyId}",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $returnApi = json_decode($response);

        if ($returnApi->result != "success") {
            $callBack["error"] = "true";
            $callBack["message"] = $returnApi->message;
        } else {
            $callBack["error"] = "false";
            $callBack["message"] = $returnApi->message;
            $callBack["apifaturamentoid"] = $returnApi->apifaturamentoid;
            $callBack["idtransacao"] = ($returnApi->payment_authorization)->idtransacao;
            $callBack["statusTrasation"] = ($returnApi->payment_authorization)->status;
            $callBack["transactionNumberCard"] = ($returnApi->payment_authorization)->transaction_number;
            $callBack["autorizationIdCard"] = ($returnApi->payment_authorization)->authorizer_id;
            $callBack["autorizationNsu"] = ($returnApi->payment_authorization)->authorization_nsu;
            $callBack["expectedOnCredit"] = ($returnApi->payment_authorization)->expected_on;
            $callBack["apicobclienteid"] = $returnApi->apicobclienteid;
            $callBack["apifuturabuyerid"] = $returnApi->apifuturabuyerid;
        }
        return json_encode($callBack);

    }

    /**
     * função reponsavel por deixar apenas numeros nas variaveis cpf, cnpj, cep, telefone
     * @param $number
     * @return string|string[]|null
     */
    private function cleanNumbers($number)
    {
        $number = preg_replace("/[^0-9]/", "", $number);
        return $number;
    }

    /**
     * função para remover acentos e converter para caixa alta
     * @param $string
     * @return string|string[]|null
     */
    private function removeAccentsUppercase($string)
    {
        $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
        $string = strtoupper($string);
        return $string;
    }

}




