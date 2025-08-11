<?php


class paymentTransationCard
{


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




