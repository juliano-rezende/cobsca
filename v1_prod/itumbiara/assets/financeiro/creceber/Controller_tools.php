<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

/* ARQUIVO PARA TRATAMENTO DOS VALORES DO FORMULARIO*/
/*VALIDAÇÃO PARA CALCULO DE VALOR TOTAL VALOR+JUROS+MULTA-DESCONTOS*/

$action		=	isset( $_POST['action'])	? $_POST['action']: tool::msg_erros("O Campo action Obrigatorio Faltando.");

//formata moeda
 $cp1	=	tool::limpamoney($_POST['vlr_nominal_cx']);	/*valor nominal*/
 $cp2	=	tool::limpamoney($_POST['multa_cx']);	/*multa*/
 $cp3	=	tool::limpamoney($_POST['juros_cx']);	/*juros*/
 $cp4	=	tool::limpamoney($_POST['descontos_cx']);	/*descontos*/


 $soma	=	$cp1+$cp2+$cp3; /*soma do valor nominal + juros + multa*/

/*validamos a soma para evitar que o valor seja zero*/
if($soma < 0){echo '":"","callback":"0","vlr":"Valor não pode ser mebor ou igual a zero!","status":"danger';}

/* subtraimos o total menos o descontos*/
$sub_desc	= $soma-$cp4;

/* se o valor dos descontos for maior que zero*/
 if($cp4 > 0){
			$vlr=	number_format($sub_desc,2,",",".");
		}else{
				 $vlr= number_format($soma,2,",",".");
		}
echo '":"","callback":"1","vlr":"'.$vlr.'","status":"success';
?>
