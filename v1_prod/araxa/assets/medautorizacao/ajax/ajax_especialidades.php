<?php
// blibliotecas
require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$query="
SELECT
  id,descricao
FROM
  med_especialidades
WHERE
  med_parceiros_id ='".$_POST['parId']."' AND med_areas_id ='".$_POST['areaId']."' ";


$query_c=med_especialidades::find_by_sql($query);
  
$especparceiro= new ArrayIterator($query_c);
echo '<option value="" >Selecionar</option>';
while($especparceiro->valid()):
?>  
<option value="<?php  echo $especparceiro->current()->id; ?>"><?php  echo strtoupper($especparceiro->current()->descricao); ?></option>
<?php
$especparceiro->next();
endwhile;


?>