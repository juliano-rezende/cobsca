<?php
// blibliotecas
require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


if($_POST['dep'] == "0"){


$query="
SELECT
  nm_associado
FROM
  associados
WHERE
  matricula ='".$_POST['mat']."' ";


$Qsolicitantes=associados::find_by_sql($query);
  
$solicitante= new ArrayIterator($Qsolicitantes);

while($solicitante->valid()):
?>  
<option value="<?php echo intval($_POST['mat']); ?>"><?php  echo strtoupper($solicitante->current()->nm_associado); ?></option>
<?php
$solicitante->next();
endwhile;




}else{




	$query="
SELECT
  id,nome
FROM
  dependentes
WHERE
  matricula ='".$_POST['mat']."' ";


$Qsolicitantes=dependentes::find_by_sql($query);
  
$solicitante= new ArrayIterator($Qsolicitantes);

while($solicitante->valid()):
?>  
<option value="<?php echo strtoupper($solicitante->current()->id); ?>"><?php  echo strtoupper($solicitante->current()->nome); ?></option>
<?php
$solicitante->next();
endwhile;



}



















?>