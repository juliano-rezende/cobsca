
<div id="container" style="height: 980px; margin-top: 50px;">


<table style="width: 100%;" class="uk-table">

  <thead >
    <tr>
        <td align="center" colspan="6" style=" font-weight: bold;; font-size: 18px; text-transform: uppercase;" > <?php echo $dadosboleto["identificacao"]; ?></td>
    </tr>
     <tr>
        <td align="center" colspan="6" >CNPJ: <?php echo isset($dadosboleto["cpf_cnpj"]) ? $dadosboleto["cpf_cnpj"] : ''; ?></td>
    </tr>
    <tr>
        <td align="center" colspan="6">ENDEREÇO: <?php echo $dadosboleto["endereco"]; ?> <?php echo $dadosboleto["cidade_uf"]; ?></td>
    </tr>
    <tr>
        <td align="center" colspan="6" ><hr /></td>
    </tr>
    <tr style="line-height:35px;">
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5; background-color:#e1e1e1;">Codígo</th>
        <th align="left"  style="width:300px;border-bottom:1px dashed #e5e5e5; background-color:#e1e1e1;">Nome Funcionario</th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5; background-color:#e1e1e1;" >Referencia</th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5; background-color:#e1e1e1;" >Mensalidade</th>
        <th align="center"  style="width:120px;border-bottom:1px dashed #e5e5e5; background-color:#e1e1e1;">Consulas/Exames</th>
        <th align="center"  style="border-bottom:1px dashed #e5e5e5; background-color:#e1e1e1;" >Sub-Total</th>
    </tr>
    </thead>
  <tbody style="font-size: 9px;">
<?php

// laço que loopa os lançamentos dos convenios  agrupando por data
$listfat= new ArrayIterator($queryfatu);
$lf=1; // linha de titulos

$vl_t="";
$valor_t_pro="";

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
        <th align="center"  style="border-bottom:1px dashed #e5e5e5" ><?php echo number_format($vl_t+$valor_t_pro,2,',','1'); ?></th>
    </tr>
  </tfoot>
 </table>
 </div>

