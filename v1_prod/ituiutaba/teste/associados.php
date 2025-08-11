<?php
//classe de validação de cnpj e cpf

require_once("../conexao.php");
$cfg->set_model_directory('../models/');

$all = associados::all();

$total = count($all);


$j=0;
for($linha=1; $linha < $total; $linha ++){

    $a = associados::find_by_sql("SELECT associados.matricula,associados.nm_associado,convenios.razao_social FROM associados left join convenios ON convenios.id = associados.convenios_id WHERE associados.matricula='".$linha."'");

if(count($a) > 0){

    echo "<span style='color: #00b248; width: 500px;min-width: 500px;display: inline-grid;'>".$linha." ) -> ".strtoupper($a[0]->nm_associado)."</span><span style='color: #00b248; width: 300px;border-left: 1px solid #000; padding-left: 5px;'>".strtoupper($a[0]->razao_social)."</span><br>";

}else {

    echo "<span style='color: red;width: 400px;min-width: 400px;display: inline-grid; '>".$linha." ) -> MATRICULA FANTASMA</span><br>";

   if($linha < 2500){
       $j++;
   }

}


}

echo "---------------------------------- Matriculas ghost importação base inicial ___________________________________> ".$j."";
echo "---------------------------------- Matriculas ghost pós importação ___________________________________> ".$j."";