<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header" >
            <h2><i class="uk-icon-filter uk-icon-archive" ></i> Contas</h2>
		</div>
            <form method="post" class="uk-form">
            <fieldset style="width:400px; background-color:transparent;">
            <?php
            $consolidado=0;
            echo'<div class="tabs-spacer" style="display:none;">';
            $contabancaria=contas_bancarias::find_by_sql("SELECT id,nm_conta FROM contas_bancarias WHERE  status='1' AND empresas_id='".$COB_Empresa_Id."'");
            $listconta= new ArrayIterator($contabancaria);
            echo'</div>';
            while($listconta->valid()):
            echo'<div class="tabs-spacer" style="display:none;">';
            // soma todas as entradas
            $result= caixa::find_by_sql("SELECT SUM(valor) AS sum FROM caixa WHERE contas_bancarias_id='".$listconta->current()->id."' AND data <='".date("Y-m-d")."' AND tipo='c' AND empresas_id='".$COB_Empresa_Id."'");
            $entradas=$result[0]->sum;
            // soma todas as saidas
            $result = caixa::find_by_sql("SELECT SUM(valor) AS sum FROM caixa WHERE contas_bancarias_id='".$listconta->current()->id."' AND data <='".date("Y-m-d")."' AND tipo='d' AND empresas_id='".$COB_Empresa_Id."'");
            $saidas=$result[0]->sum;
            echo'</div>';

            $total=$entradas-$saidas;
            $consolidado+=$total;
            ?>
            <label>
              <span style="width: 150px; text-transform:uppercase;"><?php echo $listconta->current()->nm_conta; ?></span>
              <input name="vatual" type="text" class="w_150 uk-text-center uk-text-primary uk-text-bold"  id="vatual" value="<?php echo number_format($entradas-$saidas,2,',','.'); ?>" readonly="readonly" />

            </label>
            <?php
            $listconta->next();
            endwhile;
?>

            <label>
              <span style="width: 150px;">SUB TOTAL</span>
              <input name="vatual" type="text" class=" w_150 uk-text-center uk-text-bold"  id="vatual" value="<?php echo number_format($consolidado,2,',','.'); ?>" readonly="readonly" />
            </label>

            <hr>
            <label>
            <span style="width: 150px;">Alternar Conta</span>
            <select name="conta_id_alt" class="select" id="conta_id_alt">
            <?php
            $conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
            $desconta= new ArrayIterator($conta);
            while($desconta->valid()):
            echo'<option value="'.$desconta->current()->id.'" >'.utf8_encode($desconta->current()->nm_conta).'</option>';
            $desconta->next();
            endwhile;
            ?>
            </select>
            </label>
            </fieldset>
            </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_001_alt_conta" class="uk-button  uk-button-small uk-button-primary" ><i class="uk-icon-exchange" ></i> Altenar</a>
            <a href="JavaScript:void(0);" id="Btn002_alterna_conta" class="uk-button uk-button-danger  uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>
</div>
<script type="text/javascript">

jQuery("#Btn_001_alt_conta").click(function(){

jQuery("#Dvconta").slideToggle(200);

LoadContent('assets/financeiro/caixa/Grid_lancamentos.php?conta_id='+jQuery("#conta_id_alt").val()+'&periodo=0','gridlancamentos');
});


</script>