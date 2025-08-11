<?php

class med_parceiros extends ActiveRecord\Model{


/* define a classe medica*/
static function ClasseMedica($classe){

	
		if($classe!=""){
		
			switch ($classe) {
				case "CRP"://CLASSE PSICOLOGICA
					echo'<option value="CRP" selected>CRP</option>';
					echo'<option value="CRM">CRM</option>';
					echo'<option value="CRO">CRO</option>';
					echo'<option value="CRF">CRF</option>';        
					break;
				case "CRO"://CLASSE ODONTO
					echo'<option value="CRP">CRP</option>';
					echo'<option value="CRM">CRM</option>';
					echo'<option value="CRO" selected>CRO</option>';
					echo'<option value="CRF">CRF</option>';        
					break;
				case "CRM"://CLASSE MEDICA
					echo'<option value="CRP">CRP</option>';
					echo'<option value="CRM" selected>CRM</option>';
					echo'<option value="CRO">CRO</option>';
					echo'<option value="CRF">CRF</option>'; 
					break;
				case "CRf"://CLASSE MEDICA
					echo'<option value="CRP">CRP</option>';
					echo'<option value="CRM" >CRM</option>';
					echo'<option value="CRO">CRO</option>';
					echo'<option value="CRF" selected>CRF</option>'; 
					break;
				default:
				echo'<option value="" selected></option>';
					echo'<option value="CRP">CRP</option>';
					echo'<option value="CRM">CRM</option>';
					echo'<option value="CRO">CRO</option>';
					echo'<option value="CRF">CRF</option>';	
					}
			
			}else{
				echo'<option value="" selected></option>';
				echo'<option value="CRP">CRP</option>';
				echo'<option value="CRM">CRM</option>';
				echo'<option value="CRO">CRO</option>';
				echo'<option value="CRF">CRF</option>';	
			}
}


}


?>