<?php require_once("sessao.php"); ?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("conexao.php");
$cfg->set_model_directory('models/');
/*recupera os dados da empresa*/
$dadosempresa=empresas::find($COB_Empresa_Id);
?>
</div>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control"  content="no-store,no-cache,must-revalidate" />
<meta http-equiv="Last-Modified"  content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT" />
<meta http-equiv="Last-Modified"  content="Mon, 26 Jul 1997 05:00:00 GMT" />
<meta http-equiv="Expires" content="pt-br" />
<title><?php echo "COB_".strtoupper($dadosempresa->razao_social); ?></title>
        <link rel="shortcut icon" href="imagens/favi-con.ico" type="image/x-icon">
        <link rel="stylesheet" href="framework/uikit-2.24.0/css/uikit.css">
        <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/tooltip.min.css">
        <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/notify.min.css">
        <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/datepicker.min.css">
        <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/form-advanced.min.css">
        <link href="css/style_window.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />
        <link href="css/style_forms.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />
        <link href="css/doc.uikit.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />

</head>
<body  class="uk-height-1-1">

<!--Modal preload -->
<div class="uk-modal uk-modal-loading">
    <div class="uk-modal-dialog" style="background-color:transparent;">
        <div style="color:#FFF; font-size:25px; text-align:center;"  >
        <i class='uk-icon-spinner uk-icon-spin'></i>
        <span id="msg_loading"> Carregando </span>
        </div>
    </div>
</div>

<input name="ch_ctt" id="ch_ctt" type="hidden" value="<?php echo $dadosempresa->cnpj; ?>" >
<input name="dash" id="dash" type="hidden" value="<?=$COB_Acesso_Id; ?>" >
<!--nav bar header -->
<nav class="tm-navbar uk-navbar uk-navbar-attached " >

 <a href="#nav_left_offcanvas" data-uk-offcanvas="{mode:'slide'}" class="uk-navbar-toggle uk-icon-hover uk-icon-small"></a>

    <ul class="uk-navbar-nav uk-hidden-small">
<!--                    <li data-uk-tooltip="" title="" data-cached-title="Tela Inicial">
                        <a  style="color:#2196F3;" id="Sup_Btn_001" >
                            <i class="uk-icon-home" id="Btn_home"></i> Inicio
                        </a>
                    </li>
 -->
        <li data-uk-tooltip="" title="" data-cached-title="Pagina Inicial" id="Sup_Btn_001">
            <a   style="border-bottom-color:#F90;" >
                <i class="uk-icon-home  "></i> Inicio
            </a>
        </li>
        <li data-uk-tooltip="" title="" data-cached-title="Localizar Associado" id="Sup_Btn_002">
            <a   style="border-bottom-color:#0CC;" >
                <i class="uk-icon-user"></i> Associados
            </a>
        </li>
    </ul>
    <div class="uk-navbar-flip" style="color: #fff; width:200px;">
        <div  style="line-height:auto; text-align: center;width:200px;" id="Coutdow"></div>
        <div  style="line-height:auto; text-align: center;width:200px;" >Tempo de sessão</div>
    </div>
    <div class="uk-navbar-flip" style="color: #fff; padding-top:10px; margin-right:10px;float: right; ">
        <i class="uk-icon-whatsapp"></i> Suporte: (34) 9 9763-0303 | (31) 9 9967-0084
    </div>

</nav>


<!-- This is the off-canvas sidebar -->
<div id="nav_left_offcanvas" class="uk-offcanvas">

    <div class="uk-offcanvas-bar">

    <div style="line-height: 80px; width: 100%; ">
        <div style="width: 49%; text-align: center; float: left;">
            <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" >
                <li ><a href="#" id="Btn_logout" class="uk-text-large"> <i class="uk-icon-power-off"></i> Logout</a></li>
            </ul>
        </div>
        <div style="width: 49%; text-align: center; float: right;">
            <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" >
                <li ><a href="#" id="Sup_Btn_004" class="uk-text-large"> <i class="uk-icon-key"></i> Alt Key</a></li>
            </ul>
        </div>
    </div>

    <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav="{multiple:true}">
         <li class="uk-nav-header uk-text-center">Navegação</li>
        <?php
            if(isset($COB_Usuario_Id)){

            if($COB_Acesso_Id == 4){

            echo'<div class="tabs-spacer" style="display:none;">';
            $menuall=menu::find('all');
            echo '</div>';

            $menu= new ArrayIterator($menuall);
            while($menu->valid()):
        ?>
            <li class="uk-parent">
            <a href="#"><?php echo ($menu->current()->descricao); ?></a>

                <ul class="uk-nav-sub ">

                    <?php echo'<div class="tabs-spacer" style="display:none;">';
                        $submenuall=submenu::all(array('conditions'=>array('menu_id=?  ',''.$menu->current()->id.''),'order' => 'descricao asc'));
                        echo '</div>';
                        $submenu= new ArrayIterator($submenuall);
                        while($submenu->valid()):

                    ?>
                            <li style="padding-left:10px;"><a id="<?php echo $submenu->current()->acao; ?>"><i class="uk-icon-<?php echo $submenu->current()->icon; ?>"></i> <?php echo ($submenu->current()->descricao);?></a></li>

                    <?php
                        $submenu->next();
                        endwhile;
                    ?>

                </ul>
            </li>
        <?php
        $menu->next();
        endwhile;

        }else{

        echo'<div class="tabs-spacer" style="display:none;">';
        $pmenuall=permissaomenu::find('all',array('conditions'=>array('usuario_id=?',''.$COB_Usuario_Id.''),'order' => 'menu_id asc'));
        echo '</div>';
        $pmenu= new ArrayIterator($pmenuall);
        while($pmenu->valid()):

            if($pmenu->current()->status==1){
                    echo'<div class="tabs-spacer" style="display:none;">';
                    $descricaomenu=menu::find($pmenu->current()->menu_id);
                    echo '</div>';

                    echo' <li class="uk-parent">';
                    echo'<a href="#">'.($descricaomenu->descricao).'</a>';
                        echo'<ul class="uk-nav-sub ">';
                            echo'<div class="tabs-spacer" style="display:none;">';
                            $submenuall=permissaosubmenu::all(array('conditions'=>array('menu_id=? and usuario_id=? ',''.$pmenu->current()->menu_id.'',''.$COB_Usuario_Id.'')));
                            echo '</div>';
                            $submenu= new ArrayIterator($submenuall);
                            while($submenu->valid()):
                                if($submenu->current()->status==1){
                                    echo'<div class="tabs-spacer" style="display:none;">';
                                        $descricaosubmenu=submenu::find($submenu->current()->submenu_id);
                                    echo '</div>';

                                    echo'<li style="padding-left:10px;"><a    id="'.$descricaosubmenu->acao.'">';
                                        echo'<i class="uk-icon-'.$descricaosubmenu->icon.'"></i>';
                                        echo' '.($descricaosubmenu->descricao).'</a>';
                                    echo'</li>';
                                }
                            $submenu->next();
                            endwhile;
                        echo'</ul>';
                    echo'</li>';
                }
        $pmenu->next();
        endwhile;
        }

}
        ?>
    </ul>
    </div>
</div>
<!--div onde vão ser criada as janelas -->
<div id="dock" style="padding:0;width:160px;background-color: transparent;position:absolute;float:left;top:70px;z-index:4;"></div>

<!--inicio content -->
<div id="content" style="width:<?php echo $COB_Width; ?>px; margin:auto; height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser);?>px; overflow-y:auto; z-index:10000;background-color: #f5f5f5;"><div >
</div>

</div>

</body>
<script src="js/jquery/jquery-1.9.1.js?<?php echo microtime(); ?>"></script>
<script src="library/graficos/js/highcharts.js"></script>
<script src="library/graficos/js/modules/exporting.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="framework/uikit-2.24.0/js/uikit.min.js"></script>
<script type="text/javascript" src="js/sca.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="js/window.min.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/tooltip.min.js"></script>
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/notify.min.js"></script>
<script type="text/javascript" src="framework/uikit-2.24.0/js/components/datepicker.min.js"></script>
<script type="text/javascript" src="js/jquery/plugins/jquery_masked/jquery.masked.full.js"></script>
<script type="text/javascript" src="js/jquery/plugins/jquery_print/jquery.printElement.min.js"></script>
<script type="text/javascript" src="js/jquery/plugins/jquery_posicao_cursor/jQuery.posicaoCursor.min.js"></script>
<?php include"js/sca.min.js.php";?>
</html>