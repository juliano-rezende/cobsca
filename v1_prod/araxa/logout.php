<?php
ob_start();
ini_set("session.cache_limiter", "");

session_start();

require_once("conexao.php");
$cfg->set_model_directory('models/');


// define um prefixo para o sistema pra podermos gerenciar as sessões
require "config_ini.php";

if(isset($_SESSION[''.$Prefixo_SYS.'logado'])){

//seta o ultimo acesso  enviamos  o id do usuario e o 1 indicando para setar o campo para usuario logado
$updateacesso		=	users::ultimo_acesso($_SESSION[''.$Prefixo_SYS.'usuario_id'],0);

// impa a sessão atual do usuario forçando um novo login
session_unset($_SESSION[''.$Prefixo_SYS.'logado']);

header("Location: index.php");// redireciona para a pagina de login novamente
exit();

}else{

header("Location: index.php");// redireciona para a pagina de login novamente
exit();
}
?>

