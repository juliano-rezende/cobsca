<?php
require_once("../../../../sessao.php");
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');


// variavel com o cod do banco
$FRM_conta_bancaria  = isset( $_POST['cod_contas_id_ret']) ?  $_POST['cod_contas_id_ret']  : tool::msg_erros("O Campo cod_banco é Obrigatorio.");
// variavel com os arquivos a serem importados
$FRM_arquivo    =  isset( $_FILES['arquivo'])      ?  $_FILES['arquivo']       : tool::msg_erros("O Campo arquivo é Obrigatorio.");




// validação do banco para não vir vazio
if($FRM_conta_bancaria==""){echo "<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i> Por favor selecione um banco.</br></div>";}

//diretorio
$caminho ="../../../../arquivos/retornos/arq/emp_".$COB_Empresa_Id."_conta_bancaria_".$FRM_conta_bancaria."/".date("mY")."/";


//cria o diretorio do arquivo caso não exista
if (!file_exists($caminho)) { mkdir($caminho, 0777, true); }


// loop dos arquivos
for ($k = 0; $k < count($FRM_arquivo['name']); $k++) {

/* validações do arquivo */


if($FRM_arquivo['error'][$k] != UPLOAD_ERR_OK){

switch($fileError){
     case UPLOAD_ERR_INI_SIZE:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Erro ao importar arquivo ".$FRM_arquivo['name'][$k]." tamanho limite excedido.</br>
              </div>");
          break;
     case UPLOAD_ERR_FORM_SIZE:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Erro ao importar arquivo ".$FRM_arquivo['name'][$k]." tamanho limite excedido.</br>
              </div>");
          break;
     case UPLOAD_ERR_PARTIAL:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Arquivo importado ".$FRM_arquivo['name'][$k]." parcialmente.</br>
              </div>");
          break;
     case UPLOAD_ERR_NO_FILE:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Arquivo ".$FRM_arquivo['name'][$k]." não importado.</br>
              </div>");
          break;
     case UPLOAD_ERR_NO_TMP_DIR:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Diretorio do arquivo ".$FRM_arquivo['name'][$k]." não encontrado.</br>
              </div>");
          break;
     case UPLOAD_ERR_CANT_WRITE:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Erro ao escrever arquivo ".$FRM_arquivo['name'][$k]." no diretorio informado.</br>
              </div>");
          break;
     case  UPLOAD_ERR_EXTENSION:
          echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Arquivo Erro de extenção no php.</br>
              </div>");
          break;
     default: echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning uk-text-warning' ></i> Arquivo importado ".$FRM_arquivo['name'][$k]." incompleto.</br>
              </div>");
              break;
    }

}

// trata o cod do banco
$FRM_cod_conta  = explode("_",$FRM_conta_bancaria);
$FRM_cod_conta  = $FRM_cod_conta[0];


$dadosconta=contas_bancarias::find_by_sql("SELECT cod_banco
                                          FROM  contas_bancarias
                                          WHERE empresas_id='".$COB_Empresa_Id."' AND  id='".$FRM_cod_conta."' ");


$destino = $caminho.$FRM_arquivo['name'][$k];


// movendo o arquivo para a pasta especifica
if (move_uploaded_file($FRM_arquivo['tmp_name'][$k], $destino)) {

  $query = retornos::create(
              array(
                  'nm_arquivo' => $FRM_arquivo['name'][$k],
                  'path' => $caminho,
                  'dt_importacao' => date("Y-m-d"),
                  'cod_banco' => $dadosconta[0]->cod_banco,
                  'contas_bancarias_id'=>$FRM_cod_conta,
                  'empresas_id' => $COB_Empresa_Id
                  ));

echo ("<div class='uk-alert uk-alert-success'>
                <i class='uk-icon-success ' ></i> Arquivo ".$FRM_arquivo['name'][$k]." importado com sucesso.</br>
              </div>");

}else{

 echo ("<div class='uk-alert uk-alert-warning'>
                <i class='uk-icon-warning ' ></i> Erro ao mover o arquivo ".$FRM_arquivo['name'][$k]." para o diretorio informado.</br>
              </div>");

}

}



