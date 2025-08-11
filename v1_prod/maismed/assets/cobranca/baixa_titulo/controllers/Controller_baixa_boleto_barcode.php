<?php


$Frm_cad  = true;// fala pra sessão não encerra pois é uma janela de cadastro

set_time_limit(0);
require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');


$FRM_ids = isset($_GET['ids']) ? $_GET['ids'] : tool::msg_erros("Campo informado invalido.");


//define as variaveis vazias
$parcelas = explode(',', $FRM_ids);
$t_parcelas = count($parcelas);


foreach ($parcelas as $id) {                                 //   faz um loop usando foreach e recupera os valores
  
  // recupera ps dados da parcela
  $Query_parcela = faturamentos::find($id);
  
//  // data de vencimento
//  $dtv = new ActiveRecord\DateTime($Query_parcela->dt_vencimento);
//  $vl_t_parcelas += $Query_parcela->valor;                  // valor total das parcelas
//  $t_debito = (faturamentos::Calcula_Juros($Query_parcela->valor, $dtv->format('Y-m-d'), $configs->juros, $configs->multa) - $Query_parcela->valor);      // valor total da divida
//  $jurosemulta += $t_debito; // acrescimos
  
  
  echo '<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">'.$Query_parcela->valor.'</span>';
  
}




?>