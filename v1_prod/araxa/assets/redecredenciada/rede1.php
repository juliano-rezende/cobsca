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

   <div style="border:0; margin-bottom: 2px;">
    <h3 style="text-transform: uppercase;padding-left: 2px;border-left: 3px solid #f57f17;font-size: 16px;">CONSULTAS</h3>
   </div>

    <?php
    $Query_esp=med_especialidades::find_by_sql("SELECT med_especialidades.descricao,med_areas.descricao AS desc_area 
                                                FROM med_especialidades LEFT JOIN med_areas ON med_especialidades.med_areas_id = med_areas.id  
                                                WHERE med_areas_id='1'
                                                GROUP BY med_especialidades.descricao");
    $List_esp= new ArrayIterator($Query_esp); 
    while($List_esp->valid()):


          echo'<div style="font-size: 12px; padding-left:5px; font-weight: normal;text-transform: uppercase; margin:0; margin-bottom:2px; margin-left:15px;border-left: 3px solid #4527a0;">'.$List_esp->current()->desc_area."  ( ".$List_esp->current()->descricao.' )</div>';

         

          $Query_esp1=med_especialidades::find_by_sql("
          SELECT med_especialidades.med_parceiros_id ,med_procedimentos.descricao as desc_med, med_procedimentos.vlr_custo,med_procedimentos.tx_adm,med_parceiros.fone1
          FROM med_especialidades 
          LEFT JOIN med_procedimentos ON med_procedimentos.med_especialidades_id = med_especialidades.id
          LEFT JOIN med_parceiros ON med_especialidades.med_parceiros_id = med_parceiros.id
          WHERE med_areas_id='1' AND med_procedimentos.status ='1' AND med_especialidades.descricao ='".$List_esp->current()->descricao."'");


           $List_esp1= new ArrayIterator($Query_esp1);
            while($List_esp1->valid()):


              echo '<ul class="uk-list-striped" style="margin-left:20px;">';
                               
                echo'<li style="text-transform: uppercase; font-size: 10px; line-height:25px;">
                      <div style="float:left;">'.$List_esp1->current()->desc_med.' - ['.tool::MascaraCampos("??-????-????",$List_esp1->current()->fone1).']</div>
                      <div style="float:right; width:80px; text-align:left;"> </div>
                    </li>';

                echo '</ul>';


            $List_esp1->next();
            endwhile;

    echo '</br >';
    $List_esp->next();
    endwhile;

    ?>

  <div style="border:0; margin-bottom: 2px;">
    <h3 style="text-transform: uppercase;padding-left: 2px;border-left: 3px solid #f57f17;font-size: 16px;">EXAMES</h3>
   </div>
  </div>

 <?php
    $Query_esp=med_especialidades::find_by_sql("SELECT med_especialidades.descricao,med_areas.descricao AS desc_area 
                                                FROM med_especialidades LEFT JOIN med_areas ON med_especialidades.med_areas_id = med_areas.id  
                                                WHERE med_areas_id='2'
                                                GROUP BY med_especialidades.descricao");
    $List_esp= new ArrayIterator($Query_esp); 
    while($List_esp->valid()):


          echo'<div style="font-size: 12px; padding-left:5px; font-weight: normal;text-transform: uppercase; margin:0; margin-bottom:2px; margin-left:15px;border-left: 3px solid #4527a0;">'.$List_esp->current()->desc_area."  ( ".$List_esp->current()->descricao.' )</div>';

         

          $Query_esp1=med_especialidades::find_by_sql("
          SELECT med_especialidades.med_parceiros_id ,med_procedimentos.descricao as desc_med, med_procedimentos.vlr_custo,med_procedimentos.tx_adm,med_parceiros.fone1
          FROM med_especialidades 
          LEFT JOIN med_procedimentos ON med_procedimentos.med_especialidades_id = med_especialidades.id
          LEFT JOIN med_parceiros ON med_especialidades.med_parceiros_id = med_parceiros.id
          WHERE med_areas_id='2' AND med_procedimentos.status ='1' AND med_especialidades.descricao ='".$List_esp->current()->descricao."'");


           $List_esp1= new ArrayIterator($Query_esp1);
            while($List_esp1->valid()):


              echo '<ul class="uk-list-striped" style="margin-left:20px;">';
                               
                echo'<li style="text-transform: uppercase; font-size: 10px; line-height:25px;">
                      <div style="float:left;">'.$List_esp1->current()->desc_med.' - ['.tool::MascaraCampos("??-????-????",$List_esp1->current()->fone1).']</div>
                      <div style="float:right; width:80px; text-align:left;"> </div>
                    </li>';

                echo '</ul>';


            $List_esp1->next();
            endwhile;

    echo '</br >';
    $List_esp->next();
    endwhile;

    ?>


</div>



 <script type="text/javascript" charset="utf-8" async defer>
     function PrintRede(){

  jQuery( "#RedeCredenciada" ).print();

}
 </script>