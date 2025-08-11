<div class="tabs-spacer" style="display:none;">
  <?php
  require_once("../../../sessao.php");
  require_once("../../../conexao.php");
  $cfg->set_model_directory('../../../models/');
  ?>
</div>
<nav class="uk-navbar ">
  <table class="uk-table" >
    <thead >
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-left" style="width:300px;" colspan="4" >Recebivéis por período</th>
        <th class="uk-text-left" ></th>
      </tr>
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:20px"></th>
        <th class="uk-width uk-text-center" style="width:120px;" >Mês</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Qte Pgtos</th>
        <th class="uk-width uk-text-center" style="width:150px;" >Valor Recebido</th>
        <th class="uk-text-left" ></th>
      </tr>
    </thead>
  </table>
</nav>
<div id="Grid_recebimentos" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-95;?>px; overflow-y: scroll;">
<table  class="uk-table" >
  <tbody>
    <?php
    $linha=1;

    // loop dos anos
    for($ano='2014'; $ano<=date('Y'); $ano++){

      for($mes='01'; $mes<='12'; $mes++){
        $i= $ano."-".str_pad($mes , 2 , '0' , STR_PAD_LEFT)."-01";
        $f =$ano."-".str_pad($mes , 2 , '0' , STR_PAD_LEFT)."-31";

        echo'<div class="tabs-spacer" style="display:none;">';
        $query = faturamentos::find_by_sql("SELECT SUM(valor_pago) as soma, COUNT(id) as total FROM faturamentos WHERE dt_pagamento BETWEEN '".$i."' AND '".$f."'");
        echo'</div>';
        echo'<tr>';
        echo'<th class="uk-width uk-text-center" style="width:20px;">'.$linha.'</th>';
        echo'<td class="uk-width uk-text-center" style="width:120px;" >'.str_pad($mes , 2 , '0' , STR_PAD_LEFT).'/'.$ano.'</td>
        <td class="uk-width uk-text-center" style="width:100px;" >'.$query[0]->total.'</td>
        <td class="uk-width uk-text-center" style="width:150px;" >R$ '.number_format($query[0]->soma, 2, ',', '.').'</td>
        <td class="uk-text-left" ></td>';
        echo'</tr>';
        $linha++;
      }
    }
    ?>

  </tbody>
</table>
</div>