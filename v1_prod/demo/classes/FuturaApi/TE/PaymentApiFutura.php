<?php


namespace classes\FuturaApi;

use stdClass;

class PaymentApiFutura
{

   /**
    * apiFutura constructor.
    */
   public function __construct()
   {
      $this->data = new stdClass();
   }

   /**
    * @param string $MarketPlaceKey
    * @param string $Ambient
    */
   public function setConfig( $MarketPlaceKey, $Ambient)
   {
      $this->data->MarketPlaceKey = $MarketPlaceKey;
      $this->data->Ambient = $Ambient;
   }

   /**
    * @param $Json
    * @return bool|string
    */
   public function addSeller($Json)
   {
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/sellers/individuals",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 1,
         CURLOPT_FOLLOWLOCATION => false,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS =>"{$Json}",
         CURLOPT_HTTPHEADER => array(
            "MarketPlaceKey: {$this->data->MarketPlaceKey}",
            "Ambient: {$this->data->Ambient}",
            "Content-Type: application/json"
         ),
      ));
      
      $response = curl_exec($curl);

      curl_close($curl);

      $return = json_decode($response ,true);

      return $return;

   }

   /**
    * @param $sellerId
    * @return bool|string
    */
   public function getSellerId($sellerId){

      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/sellers/{$sellerId}",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 1,
         CURLOPT_FOLLOWLOCATION => false,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
            "MarketPlaceKey: {$this->data->MarketPlaceKey}",
            "Ambient: {$this->data->Ambient}"
         ),
      ));
     
      $response = curl_exec($curl);

      curl_close($curl);

      $return = json_decode($response ,true);

      return $return;

   }

   /**
    * @param $docSeller
    * @return bool|string
    */
   public function getSellerDoc($docSeller){


      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/sellers/cpfcnpj/{$docSeller}",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 1,
         CURLOPT_FOLLOWLOCATION => false,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
            "MarketPlaceKey: {$this->data->MarketPlaceKey}",
            "Ambient: {$this->data->Ambient}"
         ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $return = json_decode($response ,true);

      return $return;

   }


   /**
    * @param $Json
    * @return bool|string
    */
   public function addBuyer($Json){
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/Buyers",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 1,
         CURLOPT_FOLLOWLOCATION => false,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS =>"{$Json}",
         CURLOPT_HTTPHEADER => array(
            "MarketPlaceKey: {$this->data->MarketPlaceKey}",
            "Ambient: {$this->data->Ambient}",
            "Content-Type: application/json"
         ),
      ));
      
      $response = curl_exec($curl);

      curl_close($curl);

      $return = json_decode($response ,true);

      return $return;
   }

   /**
    * @param $docBuyer
    * @return bool|string
    */
   public function getBuyerDoc($docBuyer){

      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/Buyers/cpfcnpj/{$docBuyer}",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 1,
         CURLOPT_FOLLOWLOCATION => false,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
            "MarketPlaceKey: {$this->data->MarketPlaceKey}",
            "Ambient: {$this->data->Ambient}"
         ),
      ));
      
      $response = curl_exec($curl);

      curl_close($curl);

      $return = json_decode($response ,true);

      return $return;

   }

   /**
    * @param $buyerId
    * @return bool|string
    */
   public function getBuyerId($buyerId){

      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/Buyers/{$buyerId}",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 1,
         CURLOPT_FOLLOWLOCATION => false,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
         CURLOPT_HTTPHEADER => array(
            "MarketPlaceKey: {$this->data->MarketPlaceKey}",
            "Ambient: {$this->data->Ambient}"
         ),
      ));
     
      $response = curl_exec($curl);

      curl_close($curl);

      $return = json_decode($response ,true);

      return $return;

   }


 
}