<?php
$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro
require_once "../../../sessao.php";
echo '<div class="tabs-spacer" style="display:none;">';
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
echo '</div>';


$FRM_convenio_id = isset($_POST['ftg_convenio_id']) ? $_POST['ftg_convenio_id'] :
    tool::msg_erros("O Campo ftg_convenio_id é Obrigatorio.");
$FRM_p_vencimento = isset($_POST['ftg_venc']) ? $_POST['ftg_venc'] :
    tool::msg_erros("O Campo Primeiro ftg_venc é Obrigatorio.");
$FRM_mes = isset($_POST['ftg_m']) ? $_POST['ftg_m'] :
    tool::msg_erros("O Campo Numero de ftg_m é Obrigatorio.");
$FRM_ano = isset($_POST['ftg_y']) ? $_POST['ftg_y'] :
    tool::msg_erros("O Campo Numero de ftg_y é Obrigatorio.");
// DEFINIR A VARIAVEL LINHA COMO VAZIA ANTES DE PREECHER COM O RESULTADO ABAIXO //
$linha = "";
$line=1;


if ($FRM_convenio_id != 0) {

// ************************************************************************************************************************************************************* //
// SELECIONA TODOS OS ASSOCIADOS E SEUS DADOS DO CONVENIO ESPECIFICO //

    echo '<div class="tabs-spacer" style="display:none;">';

    $Query = associados::find_by_sql("
	SELECT associados.convenios_id,associados.nm_associado,associados.matricula AS mat_associado, planos.valor,dados_cobranca.dt_venc_p,dados_cobranca.id as dados_id
	FROM  associados
	INNER JOIN dados_cobranca ON dados_cobranca.matricula = associados.matricula
	LEFT JOIN planos ON planos.id = dados_cobranca.planos_id
	WHERE associados.convenios_id = '" . $FRM_convenio_id . "' and associados.status='1'
	");

    echo '</div>';

// ************************************************************************************************************************************************************* //
// LOOP DE TODOS OS ASSOCIADOS ENCONTRADOS //
    foreach ($Query as $value) {

        $matricula = $value->mat_associado;
        $tipo_parcela = "M";                            // mensalidade
        $valor = $value->valor;
        $flag_pago = "FATURADO";
        $usuarios_id = $COB_Usuario_Id;
        $empresas_id = $COB_Empresa_Id;
        $convenios_id = $value->convenios_id;
        $dados_cobranca_id = $value->dados_id;


// ************************************************************************************************************************************************************* //
// ****************** VERIFICAMOS SE VEIO DATA DE VENCIMENTO  *************** //
// SE NÃO VIER DATA DE VENCIMENTO ASSUME A DATA ESCOLHIDA NA HORA DO CADASTRO //
// SE NÃP VIER DATA DE VENCIMENTO A REFERENCIA VAI SER O MES CORRENTE         //

        if ($FRM_p_vencimento == "") {
            $referencia = date("Y-m-d");    // ano mes dia
            $dt_vencimento = $value->mat_associado;
        } else {
            $referencia = $FRM_ano . "-" . $FRM_mes . "-01";    // ano mes dia
            $dt_vencimento = tool::InvertDateTime(tool::limpastring($FRM_p_vencimento), 0);
        }


// ************************************************************************************************************************************************************* //
// VERIFICA SE JÁ EXISTE AQUELA PARCELA PARA O ASSOCIADO //

        echo '<div class="tabs-spacer" style="display:none;">';
        $Query_confirm = faturamentos::find_by_sql("SELECT id FROM faturamentos WHERE referencia='" . $referencia . "' AND matricula='" . $matricula . "' AND ( status='0' or status='1' ) and tipo_parcela='M'");
        echo '</div>';

// ************************************************************************************************************************************************************* //
// SE NÃO EXISTIR ELE CRIA //

        if (!$Query_confirm) {

// ************************************************************************************************************************************************************* //
// QUERY PARA CRIAR A PARCELA SE ELA NÃO EXISTIR //

            echo '<div class="tabs-spacer" style="display:none;">';
            $faturamento = faturamentos::create(array(
                'matricula' => $matricula,
                'ultima_alteracao' => date("Y-m-d h:m:s"),
                'dt_vencimento' => $dt_vencimento,
                'referencia' => $referencia,
                'valor' => $valor,
                'flag_pago' => $flag_pago,
                'tipo_parcela' => 'M',
                'usuarios_id' => $usuarios_id,
                'empresas_id' => $empresas_id,
                'convenios_id' => $convenios_id,
                'dados_cobranca_id' => $dados_cobranca_id
            ));
            echo '</div>';

            $linha .= '<tr>';
            $linha .= '<th class="uk-width uk-text-center" style="width:15px;" >' . $line . '</th>';
            $linha .= '<th class="uk-width uk-text-center" style="width:100px;" >' . $matricula . '</th>';
            $linha .= '<td class=" uk-text-left" style="text-transform:uppercase; " >' . $value->nm_associado . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_mes . "/" . $FRM_ano . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_p_vencimento . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:135px;" ><i class="uk-icon-check uk-text-success"></i></td>';
            $linha .= '</tr>';


// ************************************************************************************************************************************************************* //
// SE A PARCELA EXISTE APENAS RETORNAMOS UM CALLBCK INFORMANDO QUE JÁ EXISTE //

        } else {

            $linha .= '<tr>';
            $linha .= '<th class="uk-width uk-text-center" style="width:15px;" >' . $line . '</th>';
            $linha .= '<th class="uk-width uk-text-center" style="width:100px;" >' . $matricula . '</th>';
            $linha .= '<td class=" uk-text-left" style="text-transform:uppercase; " >' . $value->nm_associado . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_mes . "/" . $FRM_ano . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_p_vencimento . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:135px;" ><i class="uk-icon-remove uk-text-danger"></i></td>';
            $linha .= '</tr>';
        }

        $line++;
    }// final foreach


// CRIA PARA TODOS OS CONVENIOS
} else {

// ************************************************************************************************************************************************************* //
// SELECIONA TODOS OS ASSOCIADOS E SEUS DADOS DO CONVENIO ESPECIFICO //

    echo '<div class="tabs-spacer" style="display:none;">';

    $Query = associados::find_by_sql("
	SELECT associados.convenios_id,associados.nm_associado,associados.matricula AS mat_associado, planos.valor,dados_cobranca.dt_venc_p,dados_cobranca.id as dados_id
	FROM  associados
	INNER JOIN dados_cobranca ON dados_cobranca.matricula = associados.matricula
	LEFT JOIN planos ON planos.id = dados_cobranca.planos_id
	");
    echo '</div>';

// ************************************************************************************************************************************************************* //
// LOOP DE TODOS OS ASSOCIADOS ENCONTRADOS //

    foreach ($Query as $value) {

        $matricula = $value->mat_associado;
        $tipo_parcela = "M";                            // mensalidade
        $valor = $value->valor;
        $flag_pago = "FATURADO";
        $usuarios_id = $COB_Usuario_Id;
        $empresas_id = $COB_Empresa_Id;
        $convenios_id = $value->convenios_id;
        $dados_cobranca_id = $value->dados_id;


// *************************************************************************************************************************************************************//
// ****************** VERIFICAMOS SE VEIO DATA DE VENCIMENTO  *************** //
// SE NÃO VIER DATA DE VENCIMENTO ASSUME A DATA ESCOLHIDA NA HORA DO CADASTRO //
// SE NÃP VIER DATA DE VENCIMENTO A REFERENCIA VAI SER O MES CORRENTE         //

        if ($FRM_p_vencimento == "") {

            $referencia = date("Y-m-d");    // ano mes dia
            $dt_vencimento = $value->mat_associado;

        } else {

            $referencia = $FRM_ano . "-" . $FRM_mes . "-01";    // ano mes dia
            $dt_vencimento = tool::InvertDateTime(tool::limpastring($FRM_p_vencimento), 0);
        }

// ************************************************************************************************************************************************************* //
// VERIFICA SE JÁ EXISTE AQUELA PARCELA PARA O ASSOCIADO //

        echo '<div class="tabs-spacer" style="display:none;">';
        $Query_confirm = faturamentos::find_by_matricula_and_referencia_and_tipo_parcela($matricula, $referencia,$tipo_parcela);
        echo '</div>';

// ************************************************************************************************************************************************************* //
// SE NÃO EXISTIR ELE CRIA //

        if (!$Query_confirm) {

// ************************************************************************************************************************************************************* //
// QUERY PARA CRIAR A PARCELA SE ELA NÃO EXISTIR //

            echo '<div class="tabs-spacer" style="display:none;">';

            $faturamento = faturamentos::create(array(
                'matricula' => $matricula, 'ultima_alteracao' => date("Y-m-d h:m:s"), 'dt_vencimento' => $dt_vencimento, 'referencia' => $referencia, 'valor' => $valor,
                'flag_pago' => $flag_pago, 'dados_cobranca_id' => $dados_cobranca_id, 'usuarios_id' => $usuarios_id, 'empresas_id' => $empresas_id, 'convenios_id' => $convenios_id, 'tipo_parcela' => 'M'

            ));
            echo '</div>';

            $linha .= '<tr>';
            $linha .= '<th class="uk-width uk-text-center" style="width:15px;" >' . $line . '</th>';
            $linha .= '<th class="uk-width uk-text-center" style="width:100px;" >' . $matricula . '</th>';
            $linha .= '<td class=" uk-text-left" style="text-transform:uppercase; " >' . $value->nm_associado . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_mes . "/" . $FRM_ano . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_p_vencimento . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:135px;" ><i class="uk-icon-check uk-text-success"></i> Parcela Gerada</td>';
            $linha .= '</tr>';


// ********************************************************************************************************************************************************* //
// SE A PARCELA EXISTE APENAS RETORNAMOS UM CALLBCK INFORMANDO QUE JÁ EXISTE //
        } else {

            $linha .= '<tr>';
            $linha .= '<th class="uk-width uk-text-center" style="width:15px;" >' . $line . '</th>';
            $linha .= '<th class="uk-width uk-text-center" style="width:100px;" >' . $matricula . '</th>';
            $linha .= '<td class=" uk-text-left" style="text-transform:uppercase; " >' . $value->nm_associado . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_mes . "/" . $FRM_ano . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:90px;" >' . $FRM_p_vencimento . '</td>';
            $linha .= '<td class="uk-width uk-text-center" style="width:150px;" ><i class="uk-icon-warning uk-text-warning"></i> Parcela Existente</td>';
            $linha .= '</tr>';
        }

        $line++;
    }// final foreach


}

// *********************************************************************************************************************************************************** //
// ABRIMOS A TABELA PARA PREENCHER LOGO ABAIXO  //
echo '<table  class="uk-table uk-table-header">';
echo '<thead class="uk-gradient-cinza">';
echo '<tr>';
echo '<th class="uk-width uk-text-center" style="width:15px;" >#</th>';
echo '<th class="uk-width uk-text-center" style="width:100px;" >Matricula</th>';
echo '<th class=" uk-text-left"  >Conveniado</th>';
echo '<th class="uk-width uk-text-center"  style="width:90px;" >Referencia</th>';
echo '<th class="uk-width uk-text-center" style="width:90px;" >Vencimento</th>';
echo '<th class="uk-width uk-text-center" style="width:135px;" >Status</th>';
echo '<th class="uk-width uk-text-center" style="width:15px;" ></th>';
echo '</tr>';
echo '</thead>';
echo '</table>';
echo '<div id="result" style="overflow-y:scroll; height: 470px;">';
echo '<table class="uk-table uk-table-striped uk-table-hover">';
echo '<tbody>';
echo $linha;
echo '</tbody>';
echo '</table>';
echo '</div>';

?>
<script type="text/javascript">
    modal.hide();
</script>