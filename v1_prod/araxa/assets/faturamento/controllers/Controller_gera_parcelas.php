<?php
$Frm_cad   =  true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$FRM_matricula    =  isset( $_POST['matricula'])     ? $_POST['matricula']    : tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_p_vencimento  =  isset( $_POST['p_vencimento'])    ? $_POST['p_vencimento']  : tool::msg_erros("O Campo Primeiro Vencimento é Obrigatorio.");
$FRM_nmparcelas    =  isset( $_POST['nmparcelas'])    ? $_POST['nmparcelas']    : tool::msg_erros("O Campo Numero de Parcelas é Obrigatorio.");
$FRM_geradoem    =  date("Y-m-d h:m:s");

// recupera o convenio
$busca_convenio_id  = associados::find($FRM_matricula);

//busca dados de cobranca
$dadosencontrados  = dados_cobranca::find_by_matricula($FRM_matricula);

// verifica se veio uma data de vencimento
if($FRM_p_vencimento == ""):
  
  // CRIA A DATA DE VENCIMENTO
  $max_ref = faturamentos::find_by_sql("SELECT MAX((referencia)) AS referencia FROM  faturamentos WHERE matricula='".$FRM_matricula."' AND ( status='0' or status='1' )");
  $now  = new ActiveRecord\DateTime($max_ref[0]->referencia);
  
  $data = explode('-',''.$now->format('d-m-Y').'');
  
  $mes  = $data[1]+1;              // mes
  $dia  = $dadosencontrados->dt_venc_p;    // dia
  $ano  = $data[2];              // ano
else:
  // TRATA A DATA DE VENCIMENTO
  $data = explode('/',''.$FRM_p_vencimento.'');
  $mes  = $data[1];  //mes
  $dia  = $data[0];  //dia
  $ano  = $data[2];  //ano
endif;



for($i = 0;$i < $FRM_nmparcelas;$i++):
  
  $datavenc   = mktime(0, 0, 0, $mes+$i, $dia, $ano);
  $vencimento  = date('Y-m-d',$datavenc).' 00:00:00';
  $referencia  = date('Y-m',$datavenc).'-01';

  // define os valores
  $valor          =  $dadosencontrados->valor;
  $dado_cobranca_id    =  $dadosencontrados->id;
  $forma_cobranca_id    =  $dadosencontrados->forma_cobranca_id;
  $formascobranca_sys_id  =  $dadosencontrados->formascobranca_sys_id;
  
  // verifica a parcela
  $confirma_ref  =  faturamentos::find_by_sql("SELECT id,status FROM faturamentos WHERE matricula ='".$FRM_matricula."' AND referencia ='".$referencia."' AND tipo_parcela='M' ");
  
    
  if($confirma_ref){
    
    
    if($confirma_ref[0]->status == "0" ){
      
      echo ("<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i> Já existe uma parcela em aberto com a referência ".tool::Referencia(tool::LimpaString($referencia),"/")." para essa matricula. </br></div>");
      
    } elseif($confirma_ref[0]->status == "1"){
      
      echo ("<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i> Já existe uma parcela quitada com a referência ".tool::Referencia(tool::LimpaString($referencia),"/")." para essa matricula. </br></div>");
      
    }elseif(($confirma_ref[0]->status == "2" || $confirma_ref[0]->status == "3")){
      
      $update_ref  =  faturamentos::find($confirma_ref[0]->id);
      $update_ref->update_attributes(array('status'=>0,'ultima_alteracao'     => $FRM_geradoem,'dt_vencimento'     => $vencimento,'valor'          => $valor));
      echo ("<div class='uk-alert uk-alert-success'><i class='uk-icon-success ' ></i> Parcela reativada com a refência ".tool::Referencia(tool::LimpaString($referencia),"/")." para está matricula.</br></div>");
    }
    
    
  } else{
    
    if($dadosencontrados->valor == "0"):
      
      echo tool::msg_erros("Dados de cobrança invalidos");
      
    else:
      
       
      $create_fat = faturamentos::create(array(
        'matricula'       => $FRM_matricula,
        'ultima_alteracao'     => $FRM_geradoem,
        'dt_vencimento'     => $vencimento,
        'referencia'       => $referencia,
        'valor'          => $valor,
        'dados_cobranca_id'   => $dado_cobranca_id,
        'usuarios_id'       => $COB_Usuario_Id,
        'tipo_parcela'       => 'M',
        'empresas_id'      => $COB_Empresa_Id,
        'convenios_id'      => $busca_convenio_id->convenios_id,
        'flag_pago'        =>'FATURADO'
      ));
      
      
      if($create_fat):
        
        echo ("<div class='uk-alert uk-alert-success'>
        <i class='uk-icon-success ' ></i>
        criada com sucesso referencia ".tool::Referencia(tool::LimpaString($referencia),"/")." </br></div>");
      else:
        
        echo ("<div class='uk-alert uk-alert-danger'>
        <i class='uk-icon-danger ' ></i> Erro ao gerar referencia ".tool::Referencia(tool::LimpaString($referencia),"/")."</br></div>");
      endif;
      
    endif;
    
  }
endfor;
?>
