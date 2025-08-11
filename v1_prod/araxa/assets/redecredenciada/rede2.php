<?php
require_once("../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

?>
</div>



<nav class="uk-navbar" style="text-align:right; padding:3px; padding-right:10px; ">
  <a  onclick="PrintRede();"><i class="uk-icon-print uk-icon-medium "></i></a>
</nav>

<div id="RedeCredenciada" style="border:0;height:512px; overflow-y:scroll; margin: 0; padding: 5px; ">


  <div id="rede_header" style="padding-top: 10px;  height: 60px;">

      <div style="width: 150px; float: left; text-align: left; padding-left: 5px;"><img src="imagens/empresas/001.png" style="width: 150px; height: 50px; "> </div>
      <div style="float: left; margin-left:150px; text-align: left; padding-top:20px; font-weight: bold;"> REDE CREDENCIADA</div>
      <div style="width: 200px; float: right; text-align: right; font-size: 12px;padding-top:0;"><?php echo "Araxá ".tool::DataAtualExtenso(1); ?></div>

  </div>
  <hr style="border-color: #e5e5e5;"></hr>
    <div style="font-size: 11px; color:#666;">Atenção: Prezado usuario os valores expressos nesta tabela poderão sofrer reajustes sem prévio aviso obdecendo os critérios de parceria com os profissionais e ou empresas parceiras em os mesmos são os responsáveis pelas definições dos descontos de seus serviços ofertados.</div>
  <hr style="border-color: #e5e5e5;"></hr>

  <div id="rede_body" style="padding-left: 5px;">
    <?php
    $Query_par=med_parceiros::find_by_sql("SELECT id,fone1,CASE WHEN tp_parceiro = 'J' THEN nm_fantasia ELSE nm_parceiro END AS descricao_par  FROM med_parceiros ");
    $List_par= new ArrayIterator($Query_par); 
    while($List_par->valid()):


    echo'<div style="border:0; margin-bottom: 2px;">
             <h3 style="text-transform: uppercase;padding-left: 2px;border-left: 3px solid #f57f17;font-size: 16px;">'.$List_par->current()->descricao_par.' - ['.tool::MascaraCampos("??-????-????",$List_par->current()->fone1).']</h3>
          </div>';


            $Query_esp=med_especialidades::find_by_sql("SELECT med_especialidades.id,med_especialidades.descricao,med_areas.descricao as dec_area 
                                                        FROM med_especialidades 
                                                        LEFT JOIN med_areas ON med_areas.id = med_especialidades.med_areas_id 
                                                        WHERE med_especialidades.med_parceiros_id='".$List_par->current()->id."' AND 
                                                        EXISTS (SELECT id FROM med_procedimentos  WHERE med_procedimentos.med_especialidades_id = med_especialidades.id)");

            $List_esp= new ArrayIterator($Query_esp);
            while($List_esp->valid()):

            echo'<div style="font-size: 12px; padding-left:5px; font-weight: normal;text-transform: uppercase; margin:0; margin-bottom:2px; margin-left:15px;border-left: 3px solid #4527a0;">'.$List_esp->current()->dec_area.' - '.$List_esp->current()->descricao.'</div>';

                echo '<ul class="uk-list-striped" style="margin-left:20px;">';
                               
                $Query_pro=med_procedimentos::find_by_sql("SELECT id,descricao,vlr_custo,tx_adm FROM med_procedimentos WHERE med_especialidades_id='".$List_esp->current()->id."'");
                $List_pro= new ArrayIterator($Query_pro);
                while($List_pro->valid()):

                echo'<li style="text-transform: uppercase; font-size: 10px; line-height:25px;"><div style="float:left;">'.$List_pro->current()->descricao.'</div><div style="float:right; width:80px; text-align:left;"> </div></li>';

                $List_pro->next();
                endwhile;
                echo '</ul>';

            $List_esp->next();
            endwhile;

    echo '</br >';
    $List_par->next();
    endwhile;

    ?>
  </div>

</div>



 <script type="text/javascript" charset="utf-8" async defer>
     function PrintRede(){

  jQuery( "#RedeCredenciada" ).print();

}
 </script>