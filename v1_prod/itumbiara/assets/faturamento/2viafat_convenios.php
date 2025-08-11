<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$queryfatu=associados::find_by_sql("SELECT SQL_CACHE
                                       faturamentos.id,
                                       faturamentos.referencia,
                                       associados.matricula,
                                       associados.nm_associado,
                                       faturamentos.dt_vencimento,
                                       faturamentos.valor,
                                       (SELECT valor FROM procedimentos WHERE valor > 0 and matricula  = associados.matricula and status='0' and faturamentos_id = faturamentos.id) as valor_pro
                                    FROM
                                        faturamentos
                                    LEFT JOIN associados ON associados.matricula = faturamentos.matricula

                                    WHERE
                                      faturamentos.convenios_id = '".$FRM_convenio_id."'
                                      AND faturamentos.status = '0'
                                      AND faturamentos.tipo_parcela='M'
                                      AND faturamentos.referencia='".$FRM_referencia."'
                                      ORDER BY faturamentos.matricula");

?>
</div>
<div id="container" style="margin: 0 auto; width: 800px; height:1024px;">


    <div id="instr_header" style="margin-bottom: 50px;">
        <h1><?php echo $dadosboleto["identificacao"]; ?></h1>
        <address>Lista de Funcionários</address>
    </div>  <!-- id="instr_header" -->

<table style="width: 100%;" class="uk-table">
  <thead style="background-color: #f5f5f5; line-height: 30px">
    <tr>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5">Codígo</th>
        <th align="left"  style="width:300px;border-bottom:1px dashed #e5e5e5">Nome Funcionario</th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5" >Referencia</th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5" >Mensalidade</th>
        <th align="center"  style="width:120px;border-bottom:1px dashed #e5e5e5">Consulas/Exames</th>
        <th align="center"  style="border-bottom:1px dashed #e5e5e5" >Sub-Total</th>
    </tr>
    </thead>
  <tbody style="font-size: 9px;">
<?php


// laço que loopa os lançamentos dos convenios  agrupando por data
$listfat= new ArrayIterator($queryfatu);
$lf=1; // linha de titulos

$vl_t="";

while($listfat->valid()):

$ref = new ActiveRecord\DateTime($listfat->current()->referencia);
$dtvenc = new ActiveRecord\DateTime($listfat->current()->dt_vencimento);

?>
    <tr style="line-height: 30px;">

        <td align="center" style="border-bottom:1px dashed #e5e5e5"><?php echo $listfat->current()->matricula; ?></td>
        <td align="left"  style="border-bottom:1px dashed #e5e5e5"><?php echo strtoupper($listfat->current()->nm_associado); ?></td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5"><?php echo tool::Referencia($ref->format('Ymd'),"/"); ?></td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5">

           <?php echo number_format($listfat->current()->valor,2,",","."); ?>

        </td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5">

           <?php echo number_format($listfat->current()->valor_pro,2,",","."); ?>

        </td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5"><?php echo number_format($listfat->current()->valor_pro+$listfat->current()->valor,2,",","."); ?></td>

    </tr>
  <?php

$valor_t_pro    +=  $listfat->current()->valor_pro;
$vl_t           +=  $listfat->current()->valor;

$lf++;
$listfat->next();
endwhile;
?>
  </tbody>
  <tfoot>
    <tr style="line-height: 30px;">
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5">Total</th>
        <th align="left"  style="width:300px;border-bottom:1px dashed #e5e5e5"></th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5" ></th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5" ></th>
        <th align="center"  style="width:120px;border-bottom:1px dashed #e5e5e5"></th>
        <th align="center"  style="border-bottom:1px dashed #e5e5e5" ><?php echo number_format($vl_t+$valor_t_pro,2,',','.'); ?></th>
    </tr>
  </tfoot>
 </table>
 </div>
 <?php echo date("d/m/Y h:m:s"); ?>
 <<script type="text/javascript" charset="utf-8" async defer>
     window.print();
 </script>