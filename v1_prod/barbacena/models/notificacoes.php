<?php

class notificacoes extends ActiveRecord\Model{


public static function Status_msg($indice,$status){


	if($status == 0){

		switch ($indice) {

		case 0:	$return="uk-text-muted";						break;
		case 1:	$return="uk-text-primary";		break;
		case 2:	$return="uk-text-warning";		break;
		case 3:	$return="uk-text-danger";		break;

		}

		// retorna p codigo solicitado
		return $return;
	}

	}

}


?>