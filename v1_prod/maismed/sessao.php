<?php
ob_start();
ini_set("session.cache_limiter", "");
session_start();

// define um prefixo para o sistema pra podermos gerenciar as sessões
require "config_ini.php";


// so inicia se a variavel de sessão logado existir
if(isset($_SESSION[''.$Prefixo_SYS.'logado'])){

	$created	= $_SESSION[''.$Prefixo_SYS.'created'];
	$duraction	= $_SESSION[''.$Prefixo_SYS.'duraction'];




	if(($created+$duraction) > time()){

			// carrega as variaveis e seus valores
			$COB_Empresa_Id		=	$_SESSION[''.$Prefixo_SYS.'empresa_id'];			// id da empresa do usuario
			$COB_Acesso_Id			=	$_SESSION[''.$Prefixo_SYS.'acesso_id'];				// adm sim ou não
			$COB_Usuario_Id		=	$_SESSION[''.$Prefixo_SYS.'usuario_id'];			// id do usuario
			$COB_username			=	$_SESSION[''.$Prefixo_SYS.'username'];				// nome do usuario
			$COB_Heigth				=	$_SESSION[''.$Prefixo_SYS.'ScreenHeigth'];			// captura a altura da tela e joga na sessao
			$COB_Width				=	$_SESSION[''.$Prefixo_SYS.'ScreenWidth'];			// captura a largura da tela e joga na sessao
			$COB_Browser			=	$_SESSION[''.$Prefixo_SYS.'navegador'];				// identifica o navegador que está sendo usado

	}else{


		if(isset($Frm_cad)){

			// carrega as variaveis e seus valores
			$COB_Empresa_Id			=	$_SESSION[''.$Prefixo_SYS.'empresa_id'];			// id da empresa do usuario
			$COB_Acesso_Id			=	$_SESSION[''.$Prefixo_SYS.'acesso_id'];				// adm sim ou não
			$COB_Usuario_Id			=	$_SESSION[''.$Prefixo_SYS.'usuario_id'];			// id do usuario
			$COB_username			=	$_SESSION[''.$Prefixo_SYS.'username'];				// nome do usuario
            $COB_Heigth				=	$_SESSION[''.$Prefixo_SYS.'ScreenHeigth'];			// captura a altura da tela e joga na sessao
			$COB_Width				=	$_SESSION[''.$Prefixo_SYS.'ScreenWidth'];			// captura a largura da tela e joga na sessao
			$COB_Browser			=	$_SESSION[''.$Prefixo_SYS.'navegador'];				// identifica o navegador que está sendo usado

		}else{

				echo'<script type="text/javascript">location.href="logout.php";exit();</script>';
				exit();
			}

	}


}else{

echo'<script type="text/javascript">location.href="index.php";exit();</script>';
exit();
}
?>