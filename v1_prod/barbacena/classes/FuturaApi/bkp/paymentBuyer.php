<?php


class paymentBuyer
{
    /*@var*/
    private $endPoint;
    /*@var*/
    private $apiCompanyId;
    /*@var*/
    private $tokenAcessoFuturaApi;

    /**
     * paymentBuyer constructor.
     */
    public function __construct()
    {
        $this->endPoint = "http://142.44.232.20";
    }

    public function setToken($token): paymentBuyer
    {
        $this->tokenAcessoFuturaApi = $token;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setCompany($id): paymentBuyer
    {
        $this->apiCompanyId = $id;
        return $this;
    }

    /*
     {
    "address": {
        "line1": "Rua niteroi, 586",
        "line2": "4 casa abaixo da igreja",
        "neighborhood": "veneza II",
        "city": "IPATINGA",
        "state": "MG",
        "postal_code": "35164290",
        "country_code": "BR"
        },
    "first_name": "ADAIR ANTONIO",
    "last_name": "SILVA",
    "email": "adairasilva@hotmail.com",
    "phone_number": "31999670084",
    "taxpayer_id": "00972528679",
    "birthdate": "1974-12-03",
    "description": "ADAIR TESTE"
}

     /**
         * result
         * message
         * apicobid
         * apifuturaid
     */

    /**
     * @param $matricula
     * @param $logradouro_num
     * @param $complemento
     * @param $neighborhood
     * @param $city
     * @param $state
     * @param $postal_code
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $phone_number
     * @param $documentocpfcnpj
     * @param $dtnascimento
     * @param $description
     * @param string $country_code
     * @return bool|string
     */
    public function createClient($matricula, $logradouro_num, $complemento, $neighborhood, $city, $state, $postal_code, $first_name, $last_name, $email, $phone_number, $documentocpfcnpj, $dtnascimento, $description, $country_code = "BR")
    {

        $this->matricula = $matricula;
        $this->logradouro_num = self::removeAccentsUppercase("$logradouro_num");
        $this->complemento = self::removeAccentsUppercase("$complemento");
        $this->neighborhood = self::removeAccentsUppercase("$neighborhood");
        $this->city = self::removeAccentsUppercase("$city");
        $this->state = self::removeAccentsUppercase("$state");
        $this->postal_code = self::cleanNumbers("$postal_code");
        $this->first_name = self::removeAccentsUppercase("$first_name");
        $this->last_name = self::removeAccentsUppercase("$last_name");
        $this->phone_number = self::cleanNumbers("$phone_number");
        $this->documentocpfcnpj = self::cleanNumbers("$documentocpfcnpj");
        $this->description = self::removeAccentsUppercase("$description");
        $this->email = $email;
        $this->dtnascimento = $dtnascimento;

        $jsonClient = '{
                        "address": {
                            "line1": "' . $this->logradouro_num . '",
                            "line2": "' . $this->complemento . '",
                            "neighborhood": "' . $this->neighborhood . '",
                            "city": "' . $this->city . '",
                            "state": "' . $this->state . '",
                            "postal_code": "' . $this->postal_code . '",
                            "country_code": "BR"
                            },
                        "first_name": "' . $this->first_name . '",
                        "last_name": "' . $this->last_name . '",
                        "email": "' . $this->email . '",
                        "phone_number": "' . $this->phone_number . '",
                        "taxpayer_id": "' . $this->documentocpfcnpj . '", 
                        "birthdate": "' . $this->dtnascimento . '",
                        "description": "' . $this->description . '",
                        "apicocclienteid": "0",
                        "action":"i"
                    }';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->endPoint}/personcliente",
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
        return $response;
        $returnApi = json_decode($response);

        if ($returnApi->result != "success") {
            $callBack["error"] = "true";
            $callBack["message"] = $returnApi->result;
        } else {
            $callBack["error"] = "false";
            $callBack["matricula"] = $this->matricula;
            $callBack["apicobclienteid"] = $returnApi->apicobclienteid; //id do buyer
            $callBack["apifuturabuyerid"] = $returnApi->apifuturabuyerid;
        }
        return json_encode($callBack);

    }


    /**
     * @param $matricula
     * @param $apicocclienteid
     * @param $logradouro_num
     * @param $complemento
     * @param $neighborhood
     * @param $city
     * @param $state
     * @param $postal_code
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $phone_number
     * @param $documentocpfcnpj
     * @param $dtnascimento
     * @param $description
     * @param string $country_code
     * @return false|string
     */
    public function updateClient($matricula, $apicocclienteid, $logradouro_num, $complemento, $neighborhood, $city, $state, $postal_code, $first_name, $last_name, $email, $phone_number, $documentocpfcnpj, $dtnascimento, $description, $country_code = "BR")
    {

        $this->logradouro_num = self::removeAccentsUppercase("$logradouro_num");
        $this->complemento = self::removeAccentsUppercase("$complemento");
        $this->neighborhood = self::removeAccentsUppercase("$neighborhood");
        $this->city = self::removeAccentsUppercase("$city");
        $this->state = self::removeAccentsUppercase("$state");
        $this->postal_code = self::cleanNumbers("$postal_code");
        $this->first_name = self::removeAccentsUppercase("$first_name");
        $this->last_name = self::removeAccentsUppercase("$last_name");
        $this->phone_number = self::cleanNumbers("$phone_number");
        $this->documentocpfcnpj = self::cleanNumbers("$documentocpfcnpj");
        $this->description = self::removeAccentsUppercase("$description");
        $this->email = $email;
        $this->dtnascimento = $dtnascimento;
        $this->apicocclienteid = $apicocclienteid;

        $jsonClient = '{
                        "address": {
                            "line1": "' . $this->logradouro_num . '",
                            "line2": "' . $this->complemento . '",
                            "neighborhood": "' . $this->neighborhood . '",
                            "city": "' . $this->city . '",
                            "state": "' . $this->state . '",
                            "postal_code": "' . $this->postal_code . '",
                            "country_code": "' . $this->country_code . '"
                            },
                        "first_name": "' . $this->first_name . '",
                        "last_name": "' . $this->last_name . '",
                        "email": "' . $this->email . '",
                        "phone_number": "' . $this->phone_number . '",
                        "taxpayer_id": "' . $this->documentocpfcnpj . '", 
                        "birthdate": "' . $this->dtnascimento . '",
                        "description": "' . $this->description . '",
                        "apicocclienteid": "' . $this->apicocclienteid . '",
                        "action":"0"
                    }';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->endPoint}/personcliente",
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

        if ($returnApi->result != "success") {
            $callBack["error"] = "true";
            $callBack["message"] = $returnApi->message;
        } else {
            $callBack["error"] = "false";
            $callBack["matricula"] = "{$matricula}";
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
