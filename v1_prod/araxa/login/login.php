<?php

/* ativamos o buffer de saida */
ob_start();
/* Iniciamos a sessão */
session_start();

/* carregamos bibliotecas externas */
require_once ("../config_ini.php");
require_once ("../conexao.php");
$cfg->set_model_directory('../models/');


// valida horario de backup para não deixar logar
tool::valid_time_backup(date("h:m"));
//valida a altura da tela
tool::valid_heigth_screen(trim($_POST['Heigth']));



/* verificamos se existe um sessão ativa para este usuario */
if(isset($_SESSION[''.$Prefixo_SYS.'logado']) ){

    echo'1';exit();// callback jquery

}else{ $session_logado=0; }


//valida o campo login para evitar injeções sql
$username=isset( $_POST['login']) ?
				 users::valid_login($_POST['login']) :
				 tool::msg_erros("O campo login é obrigatorio.");
				 ;
//valida o campo password para evitar injeções sql
$password=isset( $_POST['senha']) ?
				 users::valid_password($_POST['senha']) :
				 tool::msg_erros("O campo senha é obrigatorio.");
				 ;

//conexão com o banco de dados pelo nome de usuario
$user 	= users::find_by_login($username);

// checa o usuario e a senha
$check = users::check_login($user,$password,$session_logado);

// se voltar diferente de zero é porque
// ouve algum erro ao processar o login ai
//exibimos a mensagem de erro
if( $check != '0'){

	tool::msg_erros($check);

// se tudo correu bem iniciamos os dados de sessão
}else{

// Obtém o string usuário-agente do usuário.
$user_browser = $_SERVER['HTTP_USER_AGENT'];
$user_addr    = $_SERVER['REMOTE_ADDR'];


  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$user_browser,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$user_browser,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
  } elseif(preg_match('|Firefox/([0-9\.]+)|',$user_browser,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Firefox';
  } elseif(preg_match('|Chrome/([0-9\.]+)|',$user_browser,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Chrome';
  } elseif(preg_match('|Safari/([0-9\.]+)|',$user_browser,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Safari';
  } else {
    // browser not recognized!
    $browser_version = 0;
    $browser= 'other';
  }


 //if($browser !='Firefox'){tool::msg_erros("Navegador incompativel com sistema atual. Por favor utilize o firefox.");}


//regenera o id da sessão
session_regenerate_id();
//nomeia a sessão
session_name(md5($Prefixo_SYS.$user_addr.$user_browser)."");


// variaveis de sessão

// seta a sessao logado como verdadeira
$_SESSION[''.$Prefixo_SYS.'logado']				  =	true;
// nomeia a sessao;// nomeia a sessao
$_SESSION[''.$Prefixo_SYS.'session_name']		=	(md5($Prefixo_SYS.$user_addr.$user_browser)."");
// id da empresa do usuario
$_SESSION[''.$Prefixo_SYS.'empresa_id']			=	$user->empresas_id;
// ADM DO SISTEMA SIM OU NÃO
//retorna de 1 a 3 sendo 1 user 2 gerente 3 admintrador total
$_SESSION[''.$Prefixo_SYS.'acesso_id']			=	$user->acessos_id;
//id do usuario
$_SESSION[''.$Prefixo_SYS.'usuario_id']			=	$user->id;
//id do usuario
$_SESSION[''.$Prefixo_SYS.'convenio_id']    = $user->convenios_id;
//notificações usuario
$_SESSION[''.$Prefixo_SYS.'notificar']      = $user->notificar;
//controles faturamento usuario
$_SESSION[''.$Prefixo_SYS.'faturamento']    = $user->faturamento;
// nome do usuario
$_SESSION[''.$Prefixo_SYS.'username']			  =	$user->login;
//joga o time na sessão para validar o tempo da sessão
$_SESSION[''.$Prefixo_SYS.'created']			  =	time(); // hora do login
// tempo que a sessão terá de validade de 10 minutos
$_SESSION[''.$Prefixo_SYS.'duraction']			= 3600;
/* captura a resolução e joga na sessao do usuario para ajustar os grids*/
$_SESSION[''.$Prefixo_SYS.'ScreenHeigth']		=	$_POST['Heigth'];
/* captura a resolução e joga na sessao do usuario para ajustar os grids*/
$_SESSION[''.$Prefixo_SYS.'ScreenWidth']		=	$_POST['Width'];
/* captura a resolução e joga na sessao do usuario para ajustar os grids*/
$_SESSION[''.$Prefixo_SYS.'navegador']      = $browser;


//seta o ultimo acesso  enviamos  o id do usuario e o 1 indicando para setar o campo para usuario logado
$updateacesso		=	users::ultimo_acesso($user->id,1);

echo'1';// callback jquery

}

?>
