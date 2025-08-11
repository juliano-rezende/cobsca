<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$Query_all=associados::find_by_sql("SELECT * FROM convenios ");

$List= new ArrayIterator($Query_all);


?>
</div>

<nav class="uk-navbar ">
<table  class="uk-table" >
  <thead >
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px"></th>
      <th class="uk-width uk-text-center" style="width:90px;" >Codigo</th>
      <th class="uk-width uk-text-left" style="width:300px;" >Razão Social</th>
      <th class="uk-width uk-text-left" style="width:250px;" >Nome Fantasia</th>
      <th class="uk-width uk-text-center" style="width:150px;" >Dt Cadastro</th>
      <th class="uk-width uk-text-left" style="width:220px;" >E-mail</th>
      <th class="uk-width uk-text-center" style="width:150px;" >Cnpj</th>
      <th class="uk-text-center"  ></th>
    </tr>
    </thead>
 </table>
</nav>
<div id="Grid_Convenios" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-40;?>px; overflow-y:auto;">


<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody>
  <?php
  $linha=0;
  while($List->valid()):

  $linha++;

  $dt_cad   = new ActiveRecord\DateTime($List->current()->dt_cadastro);

/* validação de nono digito*/
if(strlen(tool::LimpaString($List->current()->fone_cel)) == "10"){

$fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($List->current()->fone_cel),0,2)."0".substr(tool::LimpaString($List->current()->fone_cel),2,8));

}else{

$fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($List->current()->fone_cel),0,2)." ".substr(tool::LimpaString($List->current()->fone_cel),2,8));
}

if($List->current()->status == 0){$st="uk-text-muted";}else{$st="uk-text-primary uk-text-bold";}

?>
      <tr style="line-height:22px;" class="<?php echo $st; ?>" >
        <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo tool::CompletaZeros(2,$List->current()->empresas_id).".".tool::CompletaZeros(2,$List->current()->id); ?> </th>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:300px;max-width: 300px;"><?php echo $List->current()->razao_social; ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:250px; max-width: 250px;"><?php echo $List->current()->nm_fantasia; ?></td>
        <td class="uk-width uk-text-center" style="width:150px;" ><?php echo $dt_cad->format('d/m/Y') ?></td>
        <td class="uk-width uk-text-left" style="width:220px;" ><?php echo $List->current()->email; ?></td>
        <td class="uk-width uk-text-center" style="width:150px;" ><?php echo tool::MascaraCampos("??.???.???/????-??",$List->current()->cnpj); ?></td>
        <td class="uk-text-center"></td>
      </tr>
  <?php
  $List->next();
  endwhile;
  ?>
  </tbody>
</table>

</div>