<?php
// inicio a sessao
session_start();
// verifica se está logado
if(!isset($_SESSION['logado'])):
	header('location:../index.php');
endif;


?>