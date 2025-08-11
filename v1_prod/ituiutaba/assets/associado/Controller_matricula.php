<?php
$Frm_cad   =  true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');




$FRM_acao  =  isset( $_POST['action']) ? $_POST['action'] : tool::msg_erros("O Campo action é Obrigatorio.");


if($FRM_acao == "cancel_contrato"){
// variaveis do formulario
$FRM_matricula  =  isset( $_POST['mat'])     ? $_POST['mat']      : tool::msg_erros("O Campo mat é Obrigatorio.");
$FRM_motivo_id  =  isset( $_POST['motivo'])   ? $_POST['motivo']    : tool::msg_erros("O Campo motivo é Obrigatorio.");
$FRM_historico  =  isset( $_POST['detalhes'])  ? $_POST['detalhes']  : tool::msg_erros("O Campo detalhes é Obrigatorio.");

$query_errors="";


$queryfat=faturamentos::find_by_sql("SELECT count(id) as total_ab FROM faturamentos  WHERE faturamentos.matricula='".$FRM_matricula."' AND faturamentos.dt_vencimento <'".date("Y-m-d")."' and faturamentos.status='0'");
$procedimentos = procedimentos::find_by_sql("SELECT count(id) as total_ab FROM procedimentos WHERE status='0' AND matricula='".$FRM_matricula."'");

if($queryfat[0]->total_ab > 0 || $procedimentos[0]->total_ab > 0){
    echo "Associado com pendencias de faturamento, Por favor verificar na aba faturamentos.";
    exit();
}

// recuperamos o motivo do cancelamento
$query_motivo=motivos_cancelamentos::find($FRM_motivo_id);

// update do status na tabela associados
$query_assoc=associados::find($FRM_matricula);
$query_assoc->update_attributes(array('status'=>0,'dt_cancelamento' => date("Y-m-d")));



// motamos a tela de detalhes do cancelamento

$detalhes='
    <img class="uk-comment-avatar" src="imagens/avatars/cancel.png" width="20" height="20" alt="">
    <h4 class="uk-comment-title">Cancelamento de Contrato</h4>
    <div class="uk-comment-meta">Motivo : '.$query_motivo->descricao.'</div>
    <div class="uk-comment-meta">Histórico: '.$FRM_historico.'</div>
';
// criamos o historico
$Query_create= historicos::create( array(
                    'matricula'=>$FRM_matricula,
                    'data'=>date("Y-m-d"),
                    'historico'=>$detalhes,
                    'usuarios_id'=>$COB_Usuario_Id,
                    'empresas_id'=>$COB_Empresa_Id
                    ));

if(!$Query_create ){
    $query_errors.=("Erro ao criar historico do cancelamento.</br>");
}else{

  // cancelamos todas as parcelas em aberto
  $queryfat=faturamentos::find_by_sql("SELECT faturamentos.id,
                        faturamentos.status,
                        faturamentos.dt_cancelamento,
                        faturamentos.titulos_bancarios_id,
                        faturamentos.contas_bancarias_id,
                        titulos_bancarios.cod_remessa
                     FROM faturamentos
                     LEFT JOIN titulos_bancarios ON faturamentos.titulos_bancarios_id = titulos_bancarios.id
                     WHERE faturamentos.matricula='".$FRM_matricula."' AND faturamentos.status='0' ");
  $list= new ArrayIterator($queryfat);

  while($list->valid()):

  $update_fat = faturamentos::find($list->current()->id);
  $update_fat  ->update_attributes(
                  array('status'=>'2',
                      'dt_cancelamento'=>date("Y-m-d")
                  ));



  /*altera o status dos procedimentos caso haja reabrindo para faturamento novamente
  $procedimentos = procedimentos::find_by_faturamentos_id($list->current()->id);
  if($procedimentos){
    $list_procedimentos= new ArrayIterator($procedimentos);
          while($list_procedimentos->valid()){

            $up_pro = procedimentos::find($list_procedimentos->current()->id);
            $up_pro->update_attributes(array('status'=>1));

            if(!$up_pro){
            $query_errors.=("<div class='uk-alert uk-alert-danger'><i class='uk-icon-warning  uk-text-danger' ></i> Erro ao cancelar procedimento nº ".$list_procedimentos->current()->id.".</br></div>");

            }

        $list_procedimentos->next();
    } /* fim do while list procedimentos

  }*/

  if(!$update_fat ){
      $query_errors.=("Erro ao cancelar parcela nº ".$list->current()->id.".</br>");
  }else{

    /* verificamos se a parcela possui titulo bancario atrelado a ela se sim cancela o mesmo*/
    if($list->current()->titulos_bancarios_id > 0 ){

    /* realiza as atualizações na tabela titulos */
    $update_titulo=titulos::find($list->current()->titulos_bancarios_id);

    /* recupera o codigo do banco do titulo */
    $dados_conta_bancaria=contas_bancarias::find($list->current()->contas_bancarias_id);

    if($update_titulo->cod_remessa > '0'){

      $update_titulo->update_attributes(
                        array(
                            'status'    =>2,
                            'dt_atualizacao'=>date("Y-m-d"),
                            'stflagrem'    =>1, /* avisamos para o sistema que este registro deve ser enviado ao banco pois*/
                            'cod_mov_rem'  =>remessas::Cod_Tab_Remessa($dados_conta_bancaria->cod_banco,"MOV02"),
                            'mov_manual'  =>"S",
                            'obs'      =>'Na fila para pedido de baixa no banco remessa ultima transmissão '.$list->current()->cod_remessa.'.'));

    }else{
      $update_titulo->update_attributes(
                        array(
                            'status'    =>2,
                            'dt_atualizacao'=>date("Y-m-d"),
                            'stflagrem'    =>0, /* avisamos para o sistema que este registro deve ser enviado ao banco pois*/
                            'cod_mov_rem'  =>remessas::Cod_Tab_Remessa($dados_conta_bancaria->cod_banco,"MOV13"),
                            'mov_manual'  =>"S",
                            'obs'      =>'Parcela cancelada no faturamento | titulo não enviado ao banco'));

        if(!$update_titulo){
          $query_errors.=("Erro ao cancelar titulo nº ".$list->current()->titulos_bancarios_id.".</br>");
        }
    }
    }

  }

  $list->next();
  endwhile;
}

    if($queryfat[0]->total_ab > 0 || $procedimentos[0]->total_ab > 0){
        echo "Associado com pendencias de faturamento, Favor chercar na aba faturamentos.";
        exit();
    }

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
  if($query_errors !=""){
      echo $query_errors;
  }else{
      echo 1;
  }


}elseif($FRM_acao == "reat_contrato"){

// variaveis do formulario
$FRM_matricula  =  isset( $_POST['mat'])     ? $_POST['mat']      : tool::msg_erros("O Campo mat é Obrigatorio.");
$FRM_historico  =  isset( $_POST['detalhes'])  ? $_POST['detalhes']  : tool::msg_erros("O Campo detalhes é Obrigatorio.");


// update do status na tabela associados
$query_assoc=associados::find($FRM_matricula);

$dcad = new ActiveRecord\DateTime($query_assoc->dt_cadastro);
$dcancel = new ActiveRecord\DateTime($query_assoc->dt_cancelamento);


 $detalhes='
    <img class="uk-comment-avatar" src="imagens/avatars/cancel.png" width="20" height="20" alt="">
    <h4 class="uk-comment-title">Reativação de Contrato</h4>
    <div class="uk-comment-meta">Data/hora : '.date("d/m/Y h:i:s").'</div>
    <div class="uk-comment-meta">Data contrato anterior: '.$dcad->format("d/m/Y").'</div>
    <div class="uk-comment-meta">Data cancelmento anterior: '.$dcancel->format("d/m/Y").'</div>
    <div class="uk-comment-meta">Histórico: '.$FRM_historico.'</div>
';

// criamos o historico
$Query_create= historicos::create(
                      array(
                          'matricula'=>$FRM_matricula,
                          'data'=>date("Y-m-d"),
                          'historico'=>$detalhes,
                          'usuarios_id'=>$COB_Usuario_Id,
                          'empresas_id'=>$COB_Empresa_Id
                          ));
$query_assoc->update_attributes(array('status'=>1,'dt_cadastro'=>date("Y-m-d")));


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
  if(!$query_assoc or !$Query_create){
      echo '":"","callback":"1","msg":"Erro ao Reativar contrato!","status":"warning';
  }else{
      echo '":"","callback":"0","msg":"Contrato Reativado!.","status":"success';
  }


}elseif($FRM_acao == "add_historico"){


// variaveis do formulario
$FRM_matricula  =  isset( $_POST['mat'])     ? $_POST['mat']      : tool::msg_erros("O Campo mat é Obrigatorio.");
$FRM_motivo_id  =  isset( $_POST['motivo'])    ? $_POST['motivo']      : tool::msg_erros("O Campo motivo é Obrigatorio.");
$FRM_historico  =  isset( $_POST['detalhes'])  ? $_POST['detalhes']  : tool::msg_erros("O Campo detalhes é Obrigatorio.");
$FRM_historico  =   str_replace('<p>', "", $FRM_historico);
$FRM_historico  =   str_replace('</p>', "", $FRM_historico);


/* ligaçõe telefonicas */
if($FRM_motivo_id == 0){

  $img="phone";
  $title="Contato telefonico";
}
/* envio de sms */
if($FRM_motivo_id == 1){

  $img="sms";
  $title="Contato por SMS";
}
/* outros motivos*/
if($FRM_motivo_id == 2){

  $img="outros";
  $title="Outro motivo";
}



// motamos a tela de detalhes do historico
 $detalhes='
    <img class="uk-comment-avatar" src="imagens/avatars/'.$img.'.png" width="20" height="20" alt="">
    <h4 class="uk-comment-title">'.$title.'</h4>
    <div class="uk-comment-meta">Data/hora : '.date("d/m/Y h:i:s").'</div>
    <div class="uk-comment-meta">Histórico: '.$FRM_historico.'</div>
';


// criamos o historico
$Query_create= historicos::create(
                      array(
                          'matricula'=>$FRM_matricula,
                          'data'=>date("Y-m-d"),
                          'historico'=>$detalhes,
                          'usuarios_id'=>$COB_Usuario_Id,
                          'empresas_id'=>$COB_Empresa_Id
                          ));


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
if(!$Query_create){
  echo 1;
}else{
  echo 0;
}



}elseif($FRM_acao == "remove_historico"){

$FRM_id  =  isset( $_POST['id'])     ? $_POST['id']      : tool::msg_erros("O Campo id é Obrigatorio.");


$remover = historicos::find($FRM_id);
$remover->delete();

  // VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
  if(!$remover){
      echo 1;
  }else{
      echo 0;
  }

}elseif($FRM_acao == "reg_spc"){

$FRM_matricula  =  isset( $_POST['mat'])     ? $_POST['mat']      : tool::msg_erros("O Campo mat é Obrigatorio.");

// update do spc na tabela associados
$query_assoc=associados::find($FRM_matricula);
$query_assoc->update_attributes(array('spc'=>1));

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
if($query_assoc==true ){echo $FRM_matricula;}else{echo tool::msg_erros("Erro ao registrar spc.");}

}elseif($FRM_acao == "rem_spc"){

$FRM_matricula  =  isset( $_POST['mat'])     ? $_POST['mat']      : tool::msg_erros("O Campo mat é Obrigatorio.");

// update do spc na tabela associados
$query_assoc=associados::find($FRM_matricula);
$query_assoc->update_attributes(array('spc'=>0));

if($query_assoc==true ){echo $FRM_matricula;}else{echo tool::msg_erros("Erro ao remover spc.");}

}


?>