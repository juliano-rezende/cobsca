<?php

require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


    $bairros = bairros::find_by_sql("SELECT *, COUNT(*) as total FROM bairros GROUP BY estados_id,cidades_id, descricao HAVING COUNT(*) > 1");

    $obj = array();

    foreach($bairros as $bairro){
        
        $bairrosList = bairros::find_by_sql("SELECT id FROM bairros WHERE estados_id = '".$bairro->estados_id."' AND cidades_id = '".$bairro->cidades_id."' AND descricao LIKE '".$bairro->descricao."' ");
        
        $ids = array();
        foreach($bairrosList as $item){
            array_push($ids,$item->id);
        }
        array_push($obj,$ids);
        
    }
    
  
  $idsDelete = '';
   
   foreach($obj as $key => $value){
       
     $mutate = '';
     foreach($value as $iten){
         
         if($value[0] == $iten){continue;}
         $mutate .= ','.$iten;
         $idsDelete .= ','.$iten;
     }
     
     $mutate = substr($mutate,1);
     
     if($mutate == ''){continue;}

     $sqlUpdate = "UPDATE logradouros SET bairros_id = {$value[0]} WHERE bairros_id IN ({$mutate});";
   
     echo $sqlUpdate."<br>";
     
   }
   
   $idsDelete = substr($idsDelete,1);
   
   $sqlDelete = "DELETE FROM bairros WHERE id IN ({$idsDelete});";
   
   echo $sqlDelete."<br>";
    


    
 
    
     
    		 
  