<?php


class paymentAddCard
{
    /*@var*/
    private $endPoint;
    /*@var*/
    private $apiCobId;
    /*@var*/
    private $tokenAcessoFuturaApi;

    /**
     * paymentBuyer constructor.
     */
    public function __construct()
    {
        $this->endPoint = "http://142.44.232.20";
    }

    public function setToken($token): paymentAddCard
    {
        $this->tokenAcessoFuturaApi = $token;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setCompany($id): paymentAddCard
    {
        $this->apiCompanyId = $id;
        return $this;
    }

    /**
     * @param $matricula
     * @param $holder_name
     * @param $expiration_month
     * @param $expiration_year
     * @param $card_number
     * @param $security_code
     * @return false|string
     */

    public function AddCard($matricula, $holder_name, $expiration_month, $expiration_year, $card_number, $security_code, $apicobclienteid)
    {

        $this->matricula = self::removeAccentsUppercase("$matricula");
        $this->holder_name = self::removeAccentsUppercase("$holder_name");
        $this->expiration_month = self::cleanNumbers("$expiration_month");
        $this->expiration_year = self::cleanNumbers("$expiration_year");
        $this->card_number = self::cleanNumbers("$card_number");
        $this->security_code = self::cleanNumbers("$security_code");
        $this->apicobclienteid = self::cleanNumbers("$apicobclienteid");
        $this->apicobcardid = 0;

        $jsonClient = '{
                        "holder_name": "' . $this->holder_name . '",
                        "expiration_month": "' . $this->expiration_month . '",
                        "expiration_year": "' . $this->expiration_year . '",
                        "card_number": "' . $this->card_number . '",
                        "security_code": "' . $this->security_code . '",
                        "apicobclienteid": "' . $this->apicobclienteid . '",
                        "apicobcardid": "' . $this->apicobcardid . '",
                        "action":"i"	
                        }';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->endPoint}/personcard",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{$jsonClient}",
            CURLOPT_HTTPHEADER => array(
                "token:{$this->tokenAcessoFuturaApi}",
                "empresaid:{$this->apiCompanyId}",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $returnApi = json_decode($response);

        $arquivo = fopen('debbug.txt','a+');
        if ($arquivo == false) die('Não foi possível criar o arquivo.');
        $texto = $response ."\n\n";
        fwrite($arquivo, $texto);
        fclose($arquivo);


        if ($returnApi->result != "success") {
            $callBack["error"] = "true";
            $callBack["message"] = $returnApi->message;
        } else {
            $callBack["error"] = "false";
            $callBack["matricula"] = "{$this->matricula}";
            $callBack["apifuturabuyerid"] = "{$returnApi->apifuturabuyerid}";
            $callBack["apicobcardid"] = "{$returnApi->apicobcardid}";
            $callBack["apifuturacardtoken"] = "{$returnApi->apifuturacardtoken}";// token do cartão
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
