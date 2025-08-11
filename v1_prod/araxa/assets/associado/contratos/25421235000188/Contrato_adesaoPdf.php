<?php

ob_start();
set_time_limit(0);
ignore_user_abort(1);

include("../../../../sessao.php");
require_once("../../../../library/mpdf60/mpdf.php");
include("../../../../conexao.php");

echo'<div class="tabs-spacer" style="display:none;">';

$cfg->set_model_directory('../../../../models/');

$FRM_matricula    = isset( $_GET['matricula'])  ? $_GET['matricula']/* variavel com os ids das parcelas*/
                        : tool::msg_erros("O Campo matricula Obrigatorio faltando.");


$dadosassociado= associados::find_by_sql("SELECT
                      SQL_CACHE associados.*,
                      logradouros.descricao as nm_logradouro,
                      logradouros.cep,
                      estados.sigla AS nm_estado,
                      cidades.descricao AS nm_cidade,
                      bairros.descricao AS nm_bairro,
                      convenios.nm_fantasia as nm_convenio,
                      convenios.tx_adesao ,
                      usuarios.login as nm_usuario,
                      empresas.logomarca,
                      (
                      SELECT cidades.descricao
                      FROM
                      empresas
                      LEFT JOIN logradouros ON empresas.logradouros_id = logradouros.id
                      LEFT JOIN cidades ON logradouros.cidades_id = cidades.id
                      WHERE logradouros.id = empresas.logradouros_id) as cidade_emp,
                      empresas.razao_social,
                      empresas.fone_fixo as fone_fixo_emp,
                      empresas.fone_cel as fone_cel_emp,
                      empresas.cnpj,
                      vendedores.nm_vendedor as nm_vendedor,
                      configs.nm_seguradora,configs.num_apolice,configs.cnpj_seg,configs.vlr_apol_seg,configs.vlr_aux_fun,
                      dados_cobranca.dt_venc_p,dados_cobranca.valor,dados_cobranca.forma_cobranca_id,dados_cobranca.formascobranca_sys_id
                    FROM
                      associados
                      LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
                      LEFT JOIN estados ON estados.id = logradouros.estados_id
                      LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
                      LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
                      LEFT JOIN usuarios ON usuarios.id = associados.usuarios_id
                      LEFT JOIN convenios ON convenios.id = associados.convenios_id
                      LEFT JOIN vendedores ON vendedores.id = associados.vendedores_id
                      LEFT JOIN empresas ON associados.empresas_id = empresas.id
                      LEFT JOIN dados_cobranca ON associados.matricula = dados_cobranca.matricula
                      LEFT JOIN configs ON configs.empresas_id =  empresas.id
                    WHERE
                      associados.matricula = ".$FRM_matricula."");

$dadosdependentes=dependentes::find_by_sql("SELECT
                     SQL_CACHE dependentes.*,
                     parentescos.descricao
                     FROM
                      dependentes
                     LEFT JOIN parentescos ON dependentes.parentescos_id = parentescos.id
                     WHERE dependentes.matricula='".$FRM_matricula."' and dependentes.status='1'");

$dcad = new ActiveRecord\DateTime($dadosassociado[0]->dt_cadastro);

echo'</div>';








$html .='
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>';

$html ='';


$html .='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-weight:normal;font-size: 15px; font-family:Georgia, Times New Roman, Times, serif;">
            <thead>
                <tr>
                    <th width="88" rowspan="2" align="right" valign="middle"><img src="../../../imagens/logomarcas/'.$Querydetalhes[0]->logoorganiz.'" width="60" height="60"></th>
                    <th width="16" rowspan="2" align="center" valign="middle"></th>
                    <th width="116" rowspan="2" align="left" valign="middle"><span style="padding-right: 5px; font-size:10px;"><img src="../../../imagens/logomarcas/'.$Querydetalhes[0]->logoprojeto.'" alt="" width="60" height="60"></span></th>
                    <th width="573" rowspan="2" align="left" valign="middle" style="text-transform: uppercase;">'.$Querydetalhes[0]->razao_social.'<br />
Rua São Luis,163-centro-Araxá/mg<br />
Tel: 34 36612750 - E-mail: casanazare2002@terra.com.br </th>
                    <th width="171" rowspan="2" align="right" valign="middle" style="padding-right: 5px; font-size:10px;">&nbsp;</th>
                    <th width="171" height="44" align="right" valign="top" style="padding-right: 5px; font-size:10px;">Gerado em: '.date("d/m/Y h:i:s").'</th>
                </tr>
                <tr>
                  <th height="45" align="right" valign="top" style="padding-right: 5px;">&nbsp;</th>
                </tr>
                <tr>
                  <th height="28" colspan="6" align="center" style="text-transform: uppercase;font-size: 11px;">
                    RELAÇÃO DE INSCRITOS PROJETO '.$Querydetalhes[0]->titulo_obra.'( '.$Querydetalhes[0]->slogan.' )
                  </th>
                </tr>
            </thead>
        </table>';

$html .='<table width="100%" style="font-size: 11px;font-family:Georgia, Times New Roman, Times, serif;" class="uk-table">
            <thead>
              <tr>
                <th bgcolor="#f5f5f5" align="center" style="width: 80px;text-transform: uppercase;" height="22">Seq</th>
                <th bgcolor="#f5f5f5" align="left" style="width: 280px;text-transform: uppercase;">Nome</th>
                <th bgcolor="#f5f5f5" align="center" style="width: 120px;text-transform: uppercase;">Data Nasc</th>
                <th bgcolor="#f5f5f5" align="left" style="text-transform: uppercase;" >Endereço</th>
                <th bgcolor="#f5f5f5" align="center" style="width: 120px;text-transform: uppercase;">Data Cadastro</th>
                <th bgcolor="#f5f5f5" align="center" style="width: 80px;text-transform: uppercase;">Situação</th>
              </tr>
            </thead>
            <tbody>';

$i=1;
while($ArrayBeneficiarios->valid()):

$datanasc = new ActiveRecord\DateTime($ArrayBeneficiarios->current()->dt_nascimento);
$datacad = new ActiveRecord\DateTime($ArrayBeneficiarios->current()->dt_cadastro);


if($ArrayBeneficiarios->current()->status == 1){$status="Ativo";$color="#0066FF";}else{$status="Inativo";$color="#FF0000";}

$html .=' <tr>
          <td height="25" align="center" style="border-bottom:1px dashed #ccc;">'. $i.'</td>
          <td align="left" style="border-bottom:1px dashed #ccc;text-transform: capitalize;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            '.$ArrayBeneficiarios->current()->nm_beneficiario.'
          </td>
          <td align="center" style="border-bottom:1px dashed #ccc;">'.$datanasc->format('d/m/Y').'</td>
          <td align="left" style="border-bottom:1px dashed #ccc;text-transform: capitalize;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'.utf8_encode($ArrayBeneficiarios->current()->endereco).'</td>
          <td align="center" style="border-bottom:1px dashed #ccc;">'.$datacad->format('d/m/Y').'</td>
          <td align="center" style="border-bottom:1px dashed #ccc; color:'.$color.';">'.$status.'</td>
          </tr>';
$i++;
$ArrayBeneficiarios->next();
endwhile;

$html .='</tbody>
          </table>';

$html.='</body></html>';

$mpdf = new mPDF('utf-8', 'A4-L',5,5,7,7,5,11);//new mPDF('c','A4','','',5,5,5,5,5,5);
$mpdf->SetDisplayMode('fullpage');
$mpdf->setFooter('Pagina {PAGENO}');
$mpdf->WriteHTML($html);
//echo $html;
if($FRM_action =='D'){
$mpdf->Output("Lista_Nominal_".date("dmY").".pdf",'D');
}elseif($FRM_action =='E'){
$mpdf->Output();
}else{
$mpdf->Output();
}




?>

