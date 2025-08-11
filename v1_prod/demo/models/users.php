<?php

class users extends ActiveRecord\Model{

//nome da tabela no banco de dados
static $table_name='usuarios';

/* VALIDAMOS O CAMPO LOGIN PARA QUE NÃO SEJA ACEITO EM BRANCO E QUE TENHA SO LETRAS*/
static function valid_login($login_valido){

	$login_valido	=	trim($login_valido); /* remove os espaços do inicio */

	// valida login em branco
	if(empty($login_valido)){
		echo'O campo Login não deve fica em branco.';
		exit();
	// verifica se possui apelas letras
	}if(!ctype_alpha($login_valido) or !preg_match('/^[a-zA-Z0-9]+/', $login_valido)){
		echo "O campo login deve possui apenas letras.";
		exit();
	}else{
		return $login_valido;
	}

}


/* VALIDAMOS O CAMPO SENHA PARA QUE NÃO SEJA ACEITO EM BRANCO E QUE TENHA 8 DIGITOS*/
static function valid_password($password){

	if(empty($password)){
		echo'O campo senha  não deve ficar em branco.';
		exit();
	}if(strlen($password) < 6  or strlen($password) > 8){
		echo'Sua senha deve ter no mimino 6 e no maximo 8 digitos.';
		exit();
	}/*if(preg_match('/^[a-zA-Z0-9]+/', $password)){
		echo'A senha deve possui letras e numeros.';
		exit();
	}*/else{
			return $password;
			}

}

/* VALIDAMOS O HORARIO DE ACESSO DO USUARIO */
static function interval_access($user,$session_logado){

	$callbackObj="0";

	/* verificamos quando foi o ultimo login se foi a mais de 1 hora e o usuario não deslogou do sistema forçamos o logout*/

	/* data e hora do ultimo acesso */
	$dataacesso = new ActiveRecord\DateTime($user->ultimo_acesso);
	$dataacesso = $dataacesso->format('Y-m-d H:i:s');


	$date1 = strtotime(''.date("Y-m-d H:i:s").'');
	$date2 = strtotime(	''.$dataacesso.'');


	$subTime = $date1 - $date2;
	$ano = ($subTime/(60*60*24*365));
	$dia = ($subTime/(60*60*24))%365;
	$hora = ($subTime/3600)%24;
	$minuto = ($subTime/60)%60;


	// verificamos se ja existe sessão ativa e desabilita a verificação de usuario logado
	if($session_logado == 1){

		/* verificamos se ja existe um login ativo */
		if($user->logado == 1){

			/* intevalo do ultimo acesso */
			if( $hora == 0 ){
				/* veficamos se o ultimo login foi a menos de 60 minutos*/
				if( $minuto < 59 ){

					$callbackObj ='Seu ultimo login foi a '.($minuto).' minutos aguarde '.(59-$minuto).' minutos para um novo login';
					return $callbackObj;
					exit();

				}else{
					/* setamos o cmapo logado como 0 indicando que o usuario está livre para fazer um novo login */
					users::ultimo_acesso($user->id,0);

				}

			}
		}
	}



	/* se o usuario  for diferente de master ele verifica o horario de acesso*/
	if($user->acessos_id  < 3){

		/* validação antes do meio dia*/
		if( date("Hm") < "1200" ){

			$ini_fim  = explode("-",$user->interval_am);
			$time_ini = $ini_fim[0];	//00:00
			$time_fim = $ini_fim[1];	//00:00

			if(date("Hm") < $time_ini){

				$callbackObj='Acesso não autorizado horario de inicio  ->'.substr($time_ini,0,2).':'.substr($time_ini,2,2).'';

			}if(date("Hm") > $time_fim ){

				$callbackObj='Fora de horario de acesso!';
				$callbackObj.='<br>';
				$callbackObj.='Periodo '.substr($time_ini,0,2).':'.substr($time_ini,2,2).' as '.substr($time_fim,0,2).':'.substr($time_fim,2,2).'';
			}else{
				$callbackObj="0";
			}

			/* validação pos meio dia */
		}else{

			$ini_fim  = explode("-",$user->interval_pm);
			$time_ini = $ini_fim[0];	//00:00
			$time_fim = $ini_fim[1];	//00:00

			if($time_ini > date("Hm") ){

				$callbackObj='Acesso não autorizado horario de inicio  ->'.substr($time_ini,0,2).':'.substr($time_ini,2,2).'!';

			}if($time_fim < date("Hm") ){

				$callbackObj='Fora de horario de acesso!';
				$callbackObj.='<br>';
				$callbackObj.='Periodo '.substr($time_ini,0,2).':'.substr($time_ini,2,2).' as '.substr($time_fim,0,2).':'.substr($time_fim,2,2).'';
			}else{
				$callbackObj="0";
			}
		}

	}else{

		$callbackObj="0";
	}

	return $callbackObj;
}


/* VALIDAMOS OS DADOS DE LOGIN ,SENHA, HORARIO, E DIA DE ACESSO DO USUARIO */
static function check_login($user,$password,$session_logado){


$callbackObj='0';

/* verificamos se o login ou senha são validos*/
if(!$user){

	$callbackObj = 'Usúario não Localizado!';

}else{

	// checa se a empresa está ativa
	$check_empresa =empresas::find($user->empresas_id);

	if($check_empresa->status == 1){

		/*validação da senha*/
		$password =	tool::hash_user($password,$user->salt); /* gera um hash da senha*/

		if ($password['password'] === $user->senha){ /* verifica a paridade da senha*/

			// corrige bug do php coloca posição 1 - 1
			$days=" ".$user->day_access_user;
			$day_now =substr($days,1,1);

			// checa o horario de acesso
			$check_day =users::interval_access($user,$session_logado);

			if( $check_day != '0' ){

				$callbackObj = $check_day;

			}else{

				/* verificamos se o dia esta liberado para acesso*/
				if($day_now == 1 or $user->acessos_id == 4){


						// verifico se o usuario ainda está ativo no sistema
						if($user->status != 0){

							//verificamos se é para validar data de acesso e se a senha expirou
							if($user->senha_expira == 1){

								$dataagora=date("Y-m-d");										// data agora
								$now = new ActiveRecord\DateTime($user->data_senha_expira); 	// data adcionada no cadastro
								$dataexpirasenha= $now->format("Y-m-d"); 						// formata a data

									//compara a data de hoje com a data do cadastro para ver se ja expirou
									// se a data do cadsagtro for maior que a data de hoje usuario é valido
									if($dataagora >= $dataexpirasenha){

										$callbackObj = 'Sua Senha Expirou favor entrar em contato com o administrador!';
									}else{

										login_attempts::LimparTentativas($user->id);/* limpa as tentativas de login*/
									}
							}else{

								login_attempts::LimparTentativas($user->id);/* limpa as tentativas de login*/
							}

						}else{
							$callbackObj = 'Usuario Desativado no sistema contate o administrador!';
						}

				}else{

					$callbackObj = 'Acesso não autorizado!';
				}
			}


		}else{


			if (login_attempts::ExistemTentativas($user->id)) {


					if (login_attempts::TentativasRestantes($user->id) == 0 ){

						$callbackObj = 'Ultima tentativa!';

					}elseif (login_attempts::TentativasRestantes($user->id) <= 2 ){

						$callbackObj = 'Ainda lhe restam '.login_attempts::TentativasRestantes($user->id).' Tentativas!';

					}else {
						$callbackObj = 'Senha Incorreta!';
					}

						login_attempts::RegistrarTentativa($user->id); /* registra a tentativa de login*/

				}else {

					$callbackObj = 'Usuario Bloqueado! Entre em contato com administrador.';
				}

		}

	}else{
		$callbackObj = 'Empresa Desativada!';
	}

}

	return $callbackObj;
}


//  adciona o ultimo acesso do usuario no banco e seta o campo logado para controle de acesso simultaneo
static function ultimo_acesso($user_id,$logado){

		 $up_user = self::find($user_id);
		 $up_user ->ultimo_acesso = date("Y-m-d H:m:s");
		 $up_user ->ip_acesso = self::get_client_ip();
		 $up_user ->logado = $logado;
		 $up_user ->save();
}

/* retorna o ip de acesso*/
static function get_client_ip() {
    $ipaddress = '192.168.0.1';
   //$http_client_ip       = $_SERVER['HTTP_CLIENT_IP'];
	//$http_x_forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'];
	//$remote_addr          = $_SERVER['REMOTE_ADDR'];

	/* VERIFICO SE O IP REALMENTE EXISTE NA INTERNET */
	//if(!empty($http_client_ip)){
	//    $ipaddress = $http_client_ip;
	    /* VERIFICO SE O ACESSO PARTIU DE UM SERVIDOR PROXY */
	//} elseif(!empty($http_x_forwarded_for)){
	 //   $ipaddress = $http_x_forwarded_for;
	//} else {
	    /* CASO EU NÃO ENCONTRE NAS DUAS OUTRAS MANEIRAS, RECUPERO DA FORMA TRADICIONAL */
	 //   $ipaddress = $remote_addr;
	//}

    return $ipaddress;
}



}
?>