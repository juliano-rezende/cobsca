
<?php

ob_start();
set_time_limit(0);
ignore_user_abort(1);

require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$FRM_convenio_id    = isset( $_GET['conv_id'])  ? $_GET['conv_id']                : tool::msg_erros("O Campo convenio_id é Obrigatorio.");
$FRM_ref            = isset( $_GET['ref'])      ? $_GET['ref']                    : tool::msg_erros("O Campo refer é Obrigatorio."); 
$FRM_ref            = "01".tool::LimpaString($FRM_ref);
$FRM_ref            = tool::InvertDateTime($FRM_ref,0);

$FRM_action         = isset( $_GET['action'])   ? $_GET['action']                 : tool::msg_erros("O Campo action Obrigatorio faltando.");

$dadosassociado=associados::find_by_sql("SELECT
                                           associados.matricula,
                                           associados.nm_associado,
                                           associados.cpf,
                                           associados.rg,
                                           associados.dt_cadastro,
                                           (SELECT valor FROM faturamentos WHERE matricula =  associados.matricula AND faturamentos.referencia='".$FRM_ref."' AND faturamentos.status='0' GROUP BY faturamentos.referencia) AS valor,
                                           convenios.cnpj,
                                           convenios.razao_social,
                                           empresas.logomarca
                                           FROM
                                           associados
                                           LEFT JOIN faturamentos ON associados.matricula = faturamentos.matricula
                                           LEFT JOIN convenios ON associados.convenios_id = convenios.id
                                           LEFT JOIN empresas ON associados.empresas_id = empresas.id
                                           WHERE associados.status='1' AND  associados.convenios_id='".$FRM_convenio_id."' GROUP BY associados.matricula");

$List= new ArrayIterator($dadosassociado);


/*'.
          utf8_decode($List[0]->razao_social)."  CNPJ ".tool::MascaraCampos("??.???.???/????-??",$List[0]->cnpj)
      .'*/



$html ='
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>';

/*CABEÇALHO DO RELATORIO*/
$html.='<div style="border:0; width: 98%; margin: 0 auto;">

          <div id="col_logo" style="width: 30%;float: left;padding:10px;"><img style="max-height:40px;" width: 110px; height:40px;  src="../../../imagens/empresas/001.png"></img></div>
          
          <h1 class="uk-h2" style="margin-left:150px; padding-top:25px;">EXTRATO DE FATURAMENTO CONVÊNO</h1>

        </div>';
$html.='<div style="border:0; width: 98%; margin: 0 auto;">
          
        <tr style="line-height:25px;">
          <h1 class="uk-h6" style="margin-left:50px; padding-top:5px;text-transform: uppercase;"><b>Empresa:</b>'.utf8_encode($List[0]->razao_social).'</h1>
          <h1 class="uk-h6" style="margin-left:50px;"><b>CNPJ:</b>'.tool::MascaraCampos("??.???.???/????-??",$List[0]->cnpj).'</h1>

        </div>';

/*CABEÇALHO DA TABELA*/
$html.='<table  class="uk-table uk-table-striped" >';

$html.='<thead>
        <tr style="line-height:25px;">
            <th class="uk-width uk-text-left " style="width:20px"></th>
            <th class="uk-width uk-text-left" style="width:100px;" >Matricula</th>
            <th class="uk-width uk-text-left"   style="width:300px;">Nome</th>
            <th class="uk-width uk-text-left" style="width:120px;" >CPF</th>
            <th class="uk-width uk-text-left" style="width:120px;" >RG</th>
            <th class="uk-width uk-text-left" style="width:120px;" >Dt Cadastro</th>
            <th class="uk-width  uk-text-left" style="width:120px;" >Valor</th>
        </tr>
        </thead><tbody>';


$linhas="";
$linha=1;

$filiados=0;
$vlrtotal=0;

while($List->valid()):

/*REGISTROS DA TABELA*/
$dt_cad   = new ActiveRecord\DateTime($List->current()->dt_cadastro);

$linhas.='<tr style="line-height:25px;">
        <th class="uk-width uk-text-left" style="width:20px;">'.$linha.'</th>
        <td class="uk-width uk-text-left" style="width:100px;">'.$List->current()->matricula.' </td>
        <td class="uk-width uk-text-left" style="width:300px; text-transform: uppercase;">'.$List->current()->nm_associado.'</td>
        <td class="uk-width uk-text-left" style="width:120px;" >'.tool::MascaraCampos("???.???.???-??",$List->current()->cpf).'</td>
        <td class="uk-width uk-text-left" style="width:120px;" >'.$List->current()->rg.'</td>
        <td class="uk-width uk-text-left" style="width:120px;" >'.$dt_cad->format('d/m/Y').' </td>
        <td class="uk-width uk-text-left" style="width:120px;" >'.number_format($List->current()->valor,2,",",".").'</td>
    </tr>';

$linha++;
$filiados++;
$vlrtotal+=$List->current()->valor;

$List->next();
endwhile;

$html.=$linhas.'</tbody>';

$html.=' </table>';

$html.='<div style="border:0; padding:10x; width: 100%; margin:0 auto; color:#000; bottom:0; position:absolute;">
          
          <div class="uk-h5">
            <p>Total de filiados ativos: '.$filiados.'</p>
            <p>Valor faturado: '.number_format($vlrtotal,2,",",".").'</p> 
            <p>Gerado em: '.date("d/m/Y").' as '.date("h:i:s").'</p> 
          </div>
        </div>';


$html.='</body></html>';


require_once("../../../library/mpdf60/mpdf.php");

$mpdf = new mPDF('utf-8', 'A4-L',5,5,5,5,5,5);//new mPDF('c','A4','','',5,5,5,5,5,5);
$stylesheet = file_get_contents('../../../framework/uikit-2.24.0/css/uikit.css');
$stylesheet .= file_get_contents('../../../css/doc.uikit.css');
//coloca o estilo no html
$mpdf->WriteHTML($stylesheet,1);
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
//echo $html;

/* faz o donwload*/
if($FRM_action =='D'){
$mpdf->Output("Extrato_".date("dmY").".pdf",'D');
/* envia por email*/
}elseif($FRM_action =='E'){
$mpdf->Output('../temp/filename.pdf','F');
/* imprimir*/
}else{
$mpdf->Output();
}
exit();

?>



