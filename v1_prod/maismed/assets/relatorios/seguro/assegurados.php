<div class="tabs-spacer" style="display:none;">
<?php 
require_once("../../sessao.php");
error_reporting(0);
@ini_set('display_errors', '0');
@ini_set('register_globals', '0');
set_time_limit(0);
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
require_once("../../functions/funcoes.php");

if(isset($_GET['ano'])){$ano=$_GET['ano'];}else{$ano=date("Y");}

$letra=$_GET['letra'] ? $_GET['letra']:"a";

if(isset($_GET['letra'])){
	$like="AND nmassegurado LIKE '".$letra."%'"; 
	$order="ORDER BY cdconvenio,nmassegurado ASC ";
	}else{
		$like="AND nmassegurado LIKE '".$letra."%'";  
		$order="ORDER BY cdconvenio,matricula ASC ";
		}
		
// inicia o loop das contas
$query=seguro::find_by_sql("SELECT * FROM tbseguro WHERE cdempresa='".$SCA_Id_empresa."' AND ativo='1' ".$like." GROUP BY matricula ".$order." ");
$associados= new ArrayIterator($query);

?>
</div>

<table width="100%"   cellpadding="0" cellspacing="0" >
<thead class="thead" >
        <tr style="height:30px" >
        <th width="121"  class="text_center" >&nbsp;</th>
        <th width="201"  class="text_center" ><a href="JavaScript:void(0);" title="Ano Anterior" id="Btn_Year_Prev" style=" font-size:20px;"><i class="icon-reply-1" ></i></a>
          <input class="input_text w_100 center " name="anoconsulta" id="anoconsulta" type="text" value="<?php echo $ano; ?>" />
          <?php
		  if($ano<date("Y")){
			  echo'<a href="JavaScript:void(0);" title="Próximo Ano" id="Btn_Year_Next" style=" font-size:20px;"><i class="icon-forward" ></i></a>';
			  }
		  ?>
        <th width="738"  class="text_center" style=" text-transform:uppercase; color: <?php if($_GET['letra']){} ?>">
        <?php
		
		
		$alfa=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","u","v","w","x","y","z");
		
		foreach ($alfa as $Letra) {
					
				if($letra == $Letra){$color="#f00";}else{$color="";}

				echo "<a href=\"#\" onclick=\"ListaAlfabeto('".$Letra."')\" style=\" color:".$color.";\">".$Letra."</a>";
			}
		?>
        
      
         
        

 
          <th width="115"  class="text_right">
            <a href="JavaScript:void(0);" title="Imprimir" id="Btn_Imprimir_Balancete" style=" font-size:20px;" onclick="Print();"><i class="icon-print-1" ></i></a>

            
            </th>
    </tr>
   </thead>
</table>
  <table width="100%"   cellpadding="0" cellspacing="0">
    <thead class="thead" >
      <tr style="height:27px;" >
		<th width="80"  class="text_center" >Codigo</th>
        <th width="80"  class="text_center" >Data nasc</th>
        <th width="80"  class="text_center"  >Idade</th>
        <th width="280"  class="text_left" >Nome do Associado</th>        
        <th width="39"  class="text_center">Jan</th>
        <th width="39"  class="text_center">Fev</th>
        <th width="39"  class="text_center">Mar</th>
        <th width="39"  class="text_center">Abr</th>
        <th width="39"  class="text_center">Mai</th>
        <th width="39"  class="text_center">Jun</th>
        <th width="39"  class="text_center">Jul</th>
        <th width="39"  class="text_center">Ago</th>
        <th width="39"  class="text_center">Set</th>
        <th width="39"  class="text_center">Out</th>
        <th width="39"  class="text_center">Nov</th>
        <th width="39"  class="text_center">Dez</th>
        <th  class="text_center">Ultima Obs</th>
      </tr>
    </thead>
</table>
<div id="print" style="height:548px; background-color:#fff; width:100%; border:0; padding:0;  overflow:auto; ">
  <table width="100%"   cellpadding="0" cellspacing="0">
	<tbody class="tbody">
<?php
$id_tr=1;
while($associados->valid()):
?>	     <tr  style="cursor:default;">
         <th width="70"  class="text_center"  >
		 <?php echo $associados->current()->cdempresa.".".$associados->current()->cdconvenio.".". $associados->current()->matricula; ?>
         </th>
          <td width="80"  class="text_center"  >
		<?php 
		$now = new ActiveRecord\DateTime($associados->current()->datanasc);
		echo $now->format('d/m/Y'); 
		 ?>
        </td>
          <td width="80"  class="text_center"  >
		<?php 
		$now = new ActiveRecord\DateTime($associados->current()->datanasc);
		$date = new DateTime($now->format("d-m-Y")); // data de nascimento
		$interval = $date->diff( new DateTime( date("Y-m-d")) ); // data agora
		$idde=$interval->format( '%Y' ); //formato da idade
		echo $idde." Anos";
		 ?>
          </td>
          <td width="280"  class="text_left" style="text-transform: uppercase;" >
		  <?php echo utf8_encode(strtolower($associados->current()->nmassegurado));?>
          </td>
	        <?php
			$id_td=1;
			
			for($i=1; $i<13; $i++){ 
			
			$datasegurado=$ano.'-'.$i.'-01';
			
			$id_linha=$id_tr."_".$id_td;

			$Query_ref=seguro::find_by_matricula_and_datasegurar($associados->current()->matricula,$datasegurado);	
			
			$dtnasc=$date = new ActiveRecord\DateTime($associados->current()->datanasc); // data de nascimento
			
			
			echo"<td width=\"35\"  class=\"text_center\" id=\"ico_".$id_linha."\" onclick=\"Status('".$id_linha."','".$Query_ref->idreg."','".$associados->current()->matricula."','".base64_encode($associados->current()->nmassegurado)."','".$associados->current()->cdconvenio."','".$dtnasc->format('Y-m-d')."','".$associados->current()->cpf."','".$associados->current()->estadocivil."');\">";
			
			if($Query_ref==true){
				
			switch ($Query_ref->st){
				case 0: 
						echo"<i class=\"icon-right-thin\" style=\"color:#ff0; font-size:12px; cursor:pointer;\"  ></i>";
				break;
				case 1: 
						echo"<i class=\"icon-right-thin\" style=\"color:#063; font-size:12px; cursor:pointer;\"   ></i>";	
				break;
				case 2: 
						echo"<i class=\"icon-down-thin\" style=\"color:#06F; font-size:12px; cursor:pointer;\"  ></i>";											
				break;
				case 3: 
						echo"<i class=\"icon-up-thin\" style=\"color:#f00; font-size:12px; cursor:pointer;\"  ></i>";		
				break;
				}
								
			}else{echo"<i class=\"icon-resize-horizontal\"  style=\"color:#ccc; font-size:12px; cursor:pointer;\" ></i>";	}
			
			$id_td++;
				}
			
			echo'</td>';
			
			
			?>
      <td  class="text_center"><?php echo strtolower(utf8_encode($associados->current()->obs));?></td>
          </tr>
    
<?php
$id_tr++;
$associados->next();
endwhile; 

?>
</tbody>
</table>
<link rel="stylesheet" href="css/style_table.css?<?php echo microtime(); ?>" />
<link rel="stylesheet" href="css/style_forms.css?<?php echo microtime(); ?>" />
<link rel="stylesheet" href="imagens/icons/css/fontello.css?<?php echo microtime(); ?>">
<link rel="stylesheet" href="imagens/icons/css/animation.css?<?php echo microtime(); ?>">


</div>
<script type="text/javascript">

function Print(){
	$( "#print" ).print();
}


$("#Btn_Year_Next").click(function(){

var anocorrente=parseInt($("#anoconsulta").val()) + 1 ;

LoadContent('relatorios/seguro/assegurados.php?ano='+anocorrente+'','content');

});
$("#Btn_Year_Prev").click(function(){

var anocorrente=parseInt($("#anoconsulta").val()) - 1 ;

LoadContent('relatorios/seguro/assegurados.php?ano='+anocorrente+'','content');

});

function Status(idlinha,idreg,matricula,nmassegurado,cdconvenio,datanasc,cpf,estadocivil){


New_window('500','200','Alterar Status','relatorios/seguro/fr_planilha.php?idlinha='+idlinha+'&idreg='+idreg+'&matricula='+matricula+'&cdconvenio='+cdconvenio+'&nmassegurado='+nmassegurado+'&datanasc='+datanasc+'&cpf='+cpf+'&estadocivil='+estadocivil+'');
	
	}
function ListaAlfabeto(string){
	
LoadContent('relatorios/seguro/assegurados.php?letra='+string+'','content');	
	}


</script>
