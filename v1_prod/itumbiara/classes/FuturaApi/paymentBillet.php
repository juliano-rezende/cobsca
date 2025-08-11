<?php


namespace classes\FuturaApi;


class paymentBillet extends apiFutura
{


   public function setBillet($Json){

      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pay.futurati.com.br/v1/transactions",
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
      return $response;
   }


}