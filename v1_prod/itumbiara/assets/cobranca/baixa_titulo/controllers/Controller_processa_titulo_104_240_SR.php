<?php


$Frm_cad  = true;// fala pra sessão não encerra pois é uma janela de cadastro

set_time_limit(0);
require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

// variavel com o id do retorno
$FRM_action      = isset( $_POST['action'])              ?  $_POST['action']             : tool::msg_erros("O Campo action é Obrigatorio.");

if($FRM_action == "pay"){

// variavel com o id do retorno
$FRM_id_titulo      = isset( $_POST['id'])              ?  $_POST['id']             : tool::msg_erros("O Campo id é Obrigatorio.");
$FRM_vlr_nominal    = isset( $_POST['vlr_nominal'])     ?  tool::limpaMoney($_POST['vlr_nominal'])      : tool::msg_erros("O Campo FRM_vlr_nominal é Obrigatorio.");
$FRM_vlr_total      = isset( $_POST['vlr_total'])       ?  $_POST['vlr_total']      : tool::msg_erros("O Campo vlr_total é Obrigatorio.");
$FRM_vlr_descontos  = isset( $_POST['vlr_descontos'])   ?  $_POST['vlr_descontos']  : tool::msg_erros("O Campo vlr_descontos é Obrigatorio.");
$FRM_dtpgto         = isset( $_POST['dtpgto'])          ?  tool::InvertDateTime(tool::LimpaString($_POST['dtpgto']),"-"):  tool::msg_erros("O Campo vlr_descontos é Obrigatorio.");
$FRM_tpbaixa        = isset( $_POST['tpbaixa'])         ?  $_POST['tpbaixa']        : tool::msg_erros("O Campo vlr_descontos é Obrigatorio.");

$FRM_vlr_tarifa     = isset( $_POST['vlr_tarifa'])      ?  $_POST['vlr_tarifa']     :  tool::msg_erros("O Campo vlr_descontos é Obrigatorio.");
$FRM_vlr_tarifa     = tool::limpaMoney($FRM_vlr_tarifa);

/* recuperamos todas as parcelas referentes aquele titulo*/
$Q_faturamento = faturamentos::find_by_sql("SELECT
                                              faturamentos.id,
                                              faturamentos.titulos_bancarios_id,
                                              titulos_bancarios.contas_bancarias_id,
                                              faturamentos.matricula,
                                              faturamentos.referencia,
                                              convenios.tipo_convenio,
                                              planos.seguro,
                                              associados.matricula,
                                              associados.nm_associado
                                            FROM faturamentos
                                            LEFT JOIN titulos_bancarios ON titulos_bancarios.id = faturamentos.titulos_bancarios_id
                                            LEFT JOIN convenios ON faturamentos.convenios_id = convenios.id
                                            LEFT JOIN dados_cobranca ON dados_cobranca.id =  faturamentos.dados_cobranca_id
                                            LEFT JOIN associados ON faturamentos.matricula =  associados.matricula
                                            LEFT JOIN planos ON planos.id = dados_cobranca.planos_id
                                            WHERE titulos_bancarios_id='".$FRM_id_titulo."'");
  $t_parcelas_fat=count($Q_faturamento);

  // no caso da tabela faturamentos devemos dividir o valor pago e as tarifas pelo total de parcelas encontradas
  if($t_parcelas_fat > 1 ){ $new_vl_pago = ($vlr_valor_pago / $t_parcelas_fat); }else{ $new_vl_pago = $vlr_valor_pago; }


// criamos uma variavel mensagem para controle da linha na exibição
$msg="";

$list_fat= new ArrayIterator($Q_faturamento);
while($list_fat->valid()){


   /******************************************************************** atualiza o faturamento  ************************************************************/
    $up_faturamento = faturamentos::find($list_fat->current()->id);
    $up_faturamento->update_attributes(
                              array(
                                  'status'            => 1,
                                  'tipo_baixa'        =>'M',
                                  'dt_pagamento'      =>date("Y-m-d"),
                                  'valor_pago'        =>$new_vl_pago,
                                  'ultima_alteracao'  =>date("Y-m-d h:m:s"),
                                  'flag_pago'         =>'PAGO',
                                  'usuarios_id'       =>$COB_Usuario_Id,
                                  ));


$msg=strtoupper($list_fat->current()->nm_associado)." Parcela Nº ".$list_fat->current()->id." titulo Nº ".$list_fat->current()->titulos_bancarios_id." Baixada.";

  /*********************************************************** verifica se existe procedimento atrelhado a este titulo consultas ou exames *************************/
    $up_procedimentos = procedimentos::find_by_faturamentos_id($list_fat->current()->id);

    if($up_procedimentos){

      $up_procedimentos->update_attributes(array('status'=> 3,'obs'=>'Procedimento pago no cedente.'));
      $msg.=" | Este titulo possui R$ ".number_format($up_procedimentos->valor,2,",",".")." em procedimentos.";
    }


  /*********************************************************** SE POSSUI SEGURO FAZ A INSERÇÃO DO DADOS NA TABELA DE SEGURADOS *************************/
      if($list_fat->current()->seguro == 1){

      // passa a matricula a referencia e o tipo de convenio pj ou pf
      $Query_assegurar = seguros::segurar($list_fat->current()->matricula,$list_fat->current()->referencia,$list_fat->current()->tipo_convenio,$COB_Empresa_Id);

        // VERIFICA SE CORREU TUDO BEM NA INSERÇÃO DO DADOS NA TABELA SEGUROS
        if($Query_assegurar == true){
          $msg.=" | Associado Assegurado.";
        }
      }



  echo "<div class='uk-text-muted uk-text-success'> ".$msg."</br></div>";

    $list_fat->next();

  } /* fim do while list faturamentos*/

/*********************************************************** BAIXAMOS O TITULO NA TABELA *************************/

/* titulo pago no banco mais não baixado via retorno*/
if($FRM_tpbaixa == 1){

  $Query_update_titulo=titulos::find($Q_faturamento[0]->titulos_bancarios_id);
  $Query_update_titulo->update_attributes(
                                          array(
                                              'status'         =>1,
                                              'dt_pagamento'   =>$FRM_dtpgto,
                                              'dt_atualizacao' =>date("Y-m-d"),
                                              'vlr_pago'       =>$vlr_valor_pago,
                                              'vlr_tarifa'      =>$FRM_vlr_tarifa,
                                              'dt_processamento'=>date("Y-m-d h:m:s"),
                                              'dt_deb_tarifa'   =>$FRM_dtpgto,
                                              'st_flag_ret'     =>'1',
                                              'obs'             =>'<i class="uk-icon-small uk-icon-check uk-text-primary "></i> Titulo compensado.',
                                              'usuarios_id'     =>$COB_Usuario_Id
                                               ));


  /********************************************** lançamentos no caixa  **********************************************************************************/

  // recupera o centro de custo e plano de conta padrao da conta
  $dados_config=configs::find_by_empresas_id($COB_Empresa_Id);

  // query para recupera a forma de recebimento padrão dessa conta
  $Query_conta_bancaria=contas_bancarias::find($Query_update_titulo->contas_bancarias_id);

  // cria o historico do lançamento no credito
  $historico_C='RECEBIMENTO DE TITULO ';
  $detalhes_C='PAGAMENTO REALIZADO NO CEDENTE VALOR NOMINAL DO TITULO '.number_format($FRM_vlr_nominal,2,",",".").' VALOR TOTAL PAGO '.number_format($vlr_valor_pago,2,",",".").'.';


  // lança os creditos na caixa
  $caixa = caixa::create(
                        array('historico'                 => $historico_C,
                              'detalhes'                  => $detalhes_C,
                              'valor'                     => $vlr_valor_pago,
                              'data'                      => date("Y-m-d"),
                              'tipolancamento'            => 1,
                              'tipo'                      => 'c',
                              'formas_recebimentos_id'     => $Query_conta_bancaria->formas_recebimentos_id,
                              'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA PÓSIÇÃO INDICANDO QUE É RECIMENTO DE BOLETO BANCARIOS
                              'planos_contas_id'          => $dados_config->centros_custos_id,
                              'centros_custos_id'         => $dados_config->planos_contas_id,
                              'contas_bancarias_id'       => $Query_conta_bancaria->id,
                              'empresas_id'               => $COB_Empresa_Id,
                              'usuarios_id'               => $COB_Usuario_Id
                          ));


}else{

  $Query_update_titulo=titulos::find($Q_faturamento[0]->titulos_bancarios_id);
  $Query_update_titulo->update_attributes(
                                          array(
                                              'status'         =>1,
                                              'cod_ult_mov_rem'=>$Query_update_titulo->cod_mov_rem,
                                              'stflagrem'      =>1, /* avisamos para o sistema que este registro deve ser enviado ao banco pois houve movimentação */
                                              'dt_pagamento'   =>$FRM_dtpgto,
                                              'dt_atualizacao' =>date("Y-m-d"),
                                              'vlr_pago'       =>$vlr_valor_pago,
                                              'cod_mov_rem'    =>remessas::Cod_Tab_Remessa(756,"MOV12"), /* codigo de movimentação interna do sistema para contrele e baixa de titulos sem registro */
                                              'mov_manual'     =>"S",
                                              'local_pagamento'=>'PGTO NO CEDENTE'
                                            ));


  /********************************************** lançamentos no caixa  **********************************************************************************/

  // recupera o centro de custo e plano de conta padrao da conta
  $dados_config=configs::find_by_empresas_id($COB_Empresa_Id);

  // query para recupera a forma de recebimento padrão dessa conta
  $Query_conta_bancaria=contas_bancarias::find_by_sql("SELECT id,formas_recebimentos_id FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='0' AND status='1'");

  // cria o historico do lançamento no credito
  $historico_C='RECEBIMENTO DE TITULO ';
  $detalhes_C='PAGAMENTO REALIZADO NO CEDENTE VALOR NOMINAL DO TITULO '.number_format($FRM_vlr_nominal,2,",",".").' VALOR TOTAL PAGO '.number_format($vlr_valor_pago,2,",",".").'.';


  // lança os creditos na caixa
  $caixa = caixa::create(
                        array('historico'                 => $historico_C,
                              'detalhes'                  => $detalhes_C,
                              'valor'                     => $vlr_valor_pago,
                              'data'                      => date("Y-m-d"),
                              'tipolancamento'            => 1,
                              'tipo'                      => 'c',
                              'formas_recebimentos_id'     => $Query_conta_bancaria[0]->formas_recebimentos_id,
                              'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA PÓSIÇÃO INDICANDO QUE É RECIMENTO DE BOLETO BANCARIOS
                              'planos_contas_id'          => $dados_config->centros_custos_id,
                              'centros_custos_id'         => $dados_config->planos_contas_id,
                              'contas_bancarias_id'       => $Query_conta_bancaria[0]->id,
                              'empresas_id'               => $COB_Empresa_Id,
                              'usuarios_id'               => $COB_Usuario_Id
                          ));

}/* FECHA ELSE TPBAIXA*/


}if($FRM_action == "reverse"){


  $FRM_id_titulo      = isset( $_POST['id'])           ?  $_POST['id']             : tool::msg_erros("O Campo id é Obrigatorio.");
  $FRM_tpbaixa        = isset( $_POST['tpbaixa'])      ?  $_POST['tpbaixa']        : tool::msg_erros("O Campo tipo de baixa é Obrigatorio.");

  $erro="";


  /*tratamos o titulo*/
  /* recupera os dados do titulo*/
  $Query_update_titulo=titulos::find($FRM_id_titulo);


  /* quardamos os dados do titulo para atualizações futuras*/
  $vlr_valor_pago = $Query_update_titulo->vlr_pago;
  $FRM_vlr_tarifa = $Query_update_titulo->vlr_tarifa;

  if(!$Query_update_titulo){$erro="Falha ao atualizar titulo!";exit();}

  /* recuperamos todas as parcelas referentes aquele titulo*/
  $Q_faturamento = faturamentos::find_by_sql("SELECT *
                                            FROM faturamentos
                                            WHERE titulos_bancarios_id='".$FRM_id_titulo."'");

  // recupera o centro de custo e plano de conta padrao da conta
  $dados_config=configs::find_by_empresas_id($COB_Empresa_Id);

  if($FRM_tpbaixa == 1){
  // query para recupera a forma de recebimento padrão dessa conta
  $Query_conta_bancaria=contas_bancarias::find($Query_update_titulo->contas_bancarias_id);
  }else{
  // query para recupera a forma de recebimento padrão dessa conta
  $Query_conta_bancaria=contas_bancarias::find_by_sql("SELECT id,formas_recebimentos_id FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='0' AND status='1'");
  }


  // cria o historico do lançamento no credito
  $historico_C='ESTORNO RECEBIMENTO TITULO BANCARIO';
  $detalhes_C='ESTORNO DE TITULO COM BAIXA INCORRETA TITULO Nº'.$FRM_id_titulo.'';


  /* lançamos os valores no caixa*/
  // lança os creditos na caixa
  $caixadeb = caixa::create(
                            array('historico'                 => $historico_C,
                                  'detalhes'                  => $detalhes_C,
                                  'valor'                     => $vlr_valor_pago,
                                  'data'                      => date("Y-m-d"),
                                  'tipolancamento'            => 1,
                                  'tipo'                      => 'd',
                                  'formas_recebimentos_id'    => $Query_conta_bancaria->formas_recebimentos_id,
                                  'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA PÓSIÇÃO INDICANDO QUE É RECIMENTO DE BOLETO BANCARIOS
                                  'centros_custos_id'         => $dados_config->centros_custos_id,
                                  'planos_contas_id'          => $dados_config->planos_contas_id,
                                  'contas_bancarias_id'       => $Query_conta_bancaria->id,
                                  'empresas_id'               => $COB_Empresa_Id,
                                  'usuarios_id'               => $COB_Usuario_Id
                              ));

  if(!$caixadeb){$erro="Falha ao lançar valor no caixa cod 001!";exit();}


  // cria o historico do lançamento no debito
  if($FRM_vlr_tarifa > 0){

    $historico_D='ESTONO DEBITO TARIFA BANCARIA.';
    $detalhes_D='ESTORNO DE TARIFA COM BAIXA INCORRETA TITULO Nº'.$FRM_id_titulo.'';

    // lança os debitos na caixa
    $caixacred = caixa::create(
                            array('historico'                 => $historico_D,
                                  'detalhes'                  => $detalhes_D,
                                  'valor'                     => $FRM_vlr_tarifa,
                                  'data'                      => date("Y-m-d"),
                                  'tipolancamento'            => 1,
                                  'tipo'                      => 'c',
                                  'formas_recebimentos_id'    => $Query_conta_bancaria->formas_recebimentos_id,
                                  'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA PÓSIÇÃO INDICANDO QUE É RECIMENTO DE BOLETO BANCARIOS
                                  'planos_contas_id'          => $dados_config->planos_contas_id_d,
                                  'centros_custos_id'         => $dados_config->centros_custos_id,
                                  'contas_bancarias_id'       => $Query_conta_bancaria->id,
                                  'empresas_id'               => $COB_Empresa_Id,
                                  'usuarios_id'               => $COB_Usuario_Id
                              ));
    if(!$caixacred){$erro="Falha ao lançar valor no caixa cod 002!";exit();}
   }

  $list_fat= new ArrayIterator($Q_faturamento);
  while($list_fat->valid()){

      /*realiza o update da parcela reabrindo para emissão do titulo novamente*/
      $up_faturamento = faturamentos::find($list_fat->current()->id);
      $up_faturamento->update_attributes(
                                array(
                                      'status'            => 0,
                                    'tipo_baixa'        =>'',
                                    'dt_pagamento'      =>"0000-00-00",
                                    'valor_pago'        =>"0",
                                    'ultima_alteracao'  =>date("Y-m-d h:m:s"),
                                    'flag_pago'         =>'FATURADA',
                                    'usuarios_id'       =>$COB_Usuario_Id,
                                    'obs'       =>'HOUVE UM ESTORNO DE TITULO PARA ESTÃ PARCELA'
                                   ));

      /* se houver erro inicia o contador*/
    if(!$up_faturamento){$erro++;}

    $list_fat->next();

  /*altera o status dos procedimentos caso haja reabrindo para faturamento novamente*/
      $procedimentos = procedimentos::find('all', array('conditions' => array('faturamentos_id = ?', $list_fat->current()->id)));

      $list_procedimentos= new ArrayIterator($procedimentos);


      while($list_procedimentos->valid()){


        $up_pro = procedimentos::find($list_procedimentos->current()->id);

        $up_pro->update_attributes(array('status'=>0));

        if(!$up_pro){$erro="Falha ao atualizar procedimento!";exit();}

        $list_procedimentos->next();
    }


  } /* fim do while list procedimentos*/


  /* revertemos o titulo*/
  $Query_update_titulo->update_attributes(
                      array(
                        'status'         => 0,
                        'dt_pagamento'   => "0000-00-00",
                        'dt_atualizacao' => date("Y-m-d"),
                        'vlr_pago'       => '0.00',
                        'stflagrem'      => 1, /* avisamos para o sistema que este registro deve ser enviado ao banco pois houve movimentação */
                        'cod_mov_rem'    => remessas::Cod_Tab_Remessa(756,"MOV02"), /* codigo de movimentação interna do sistema para controle e baixa de titulos sem registro */
                        'vlr_tarifa'      => '0.00',
                        'dt_processamento'=> "0000-00-00",
                        'dt_deb_tarifa'   => "0000-00-00",
                        'st_flag_ret'     => '0',
                        'obs'             =>'<i class="uk-icon-small uk-icon-check uk-text-primary "></i> Titulo estornado.',
                        'usuarios_id'     =>$COB_Usuario_Id
                      ));



      if($erro !=""){
        echo "<div class='uk-text-muted uk-text-success'>".$erro."</br></div>";
      }else{
        echo "<div class='uk-text-muted uk-text-success'> Titulo Estonado!</br></div>";
      }





}if($FRM_action == "cancel"){

$FRM_id_titulo      = isset( $_POST['id'])              ?  $_POST['id']             : tool::msg_erros("O Campo id é Obrigatorio.");
$erro=0;


#######################################################################################################################################################################
/* cancela o titulo*/
$Query_update_titulo=titulos::find($FRM_id_titulo);
$Query_update_titulo->update_attributes(
                                        array(
                                            'status'         =>2,
                                            'cod_ult_mov_rem'=>$Query_update_titulo->cod_mov_rem,
                                            'stflagrem'      =>0, /* avisamos para o sistema que este registro deve ser enviado ao banco pois houve movimentação */
                                            'dt_atualizacao' =>date("Y-m-d"),
                                            'cod_mov_rem'    =>remessas::Cod_Tab_Remessa(756,"MOV13"), /* MOV13- titulo Codigo interno sistema */
                                            'mov_manual'     =>"S",
                                            'obs'            =>'TITULO CANCELADO'
                                          ));


/* se houver erro inicia o contador*/
if(!$Query_update_titulo){$erro++;}

/* recuperamos todas as parcelas referentes aquele titulo*/
$Q_faturamento = faturamentos::find_by_sql("SELECT id FROM faturamentos WHERE titulos_bancarios_id='".$FRM_id_titulo."'");
$t_parcelas_fat=count($Q_faturamento);

$list_fat= new ArrayIterator($Q_faturamento);
while($list_fat->valid()){


#######################################################################################################################################################################
  /*realiza o update da parcela reabrindo para emissão do titulo novamente*/
    $up_faturamento = faturamentos::find($list_fat->current()->id);
    $up_faturamento->update_attributes(
                              array(
                                  'status'                => 0,
                                  'titulos_bancarios_id'  =>'0',
                                  'ultima_alteracao'      =>date("Y-m-d h:m:s"),
                                  'flag_pago'             =>'FATURADO',
                                  'obs'                   =>'HOUVE UM CANCELAMENTO DE TITULO PARA ESTÁ PARCELA',
                                  'usuarios_id'           =>$COB_Usuario_Id
                                 ));
#######################################################################################################################################################################



#######################################################################################################################################################################
  /*altera o status dos procedimentos caso haja reabrindo para faturamento novamente*/
  $procedimentos = procedimentos::find_by_faturamentos_id($list_fat->current()->id);

  $list_procedimentos= new ArrayIterator($procedimentos);
  while($list_procedimentos->valid()){

    $up_pro = procedimentos::find($list_procedimentos->current()->id);
    $up_pro->update_attributes(array('status'=>0));

    if(!$up_pro){$erro++;}

  $list_procedimentos->next();

#######################################################################################################################################################################


/* se houver erro inicia o contador*/
if(!$up_faturamento){$erro++;}

  $list_fat->next();

  } /* fim do while list faturamentos*/


  if($erro > 0){
    echo "<div class='uk-text-muted uk-text-success'> Erro ao realizar Cancelamento!</br></div>";
  }else{
    echo "<div class='uk-text-muted uk-text-success'> Titulo Cancelado!</br></div>";
  }
}
}
?>