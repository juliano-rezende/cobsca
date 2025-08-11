<!doctype html>
<html lang="pt-br" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="ConnectChat - chatbot">
    <meta content="Inova Tecnologia" name="author">
    <meta name="keywords" content="Sistema de chatbot com atendimento multi usuarios."/>


    <!-- Favicon -->
    <link rel="icon" href="<?=url();?>/assets/midias/system/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?=url();?>/assets/midias/system/favicon.ico" />

    <!-- Title -->
    <title><?= $title; ?></title>

    <!--Bootstrap.min css-->
    <link rel="stylesheet" href="<?=url("theme/admin");?>/plugins/bootstrap/css/bootstrap.min.css">

    <!-- Dashboard css -->
    <link href="<?=url("theme/admin");?>/css/style.css" rel="stylesheet" />

    <!-- Custom scroll bar css-->
    <link href="<?=url("theme/admin");?>/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />

    <!-- Horizontal-menu css -->
    <link href="<?=url("theme/admin");?>/plugins/horizontal-menu/horizontalmenu.css" rel="stylesheet">

    <!--Daterangepicker css-->
    <link href="<?=url("theme/admin");?>/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />

    <!-- Sidebar Accordions css -->
    <link href="<?=url("theme/admin");?>/plugins/accordion1/css/easy-responsive-tabs.css" rel="stylesheet">

    <!-- Rightsidebar css -->
    <link href="<?=url("theme/admin");?>/plugins/sidebar/sidebar.css" rel="stylesheet">

    <!---Font icons css-->
    <link href="<?=url("theme/admin");?>/plugins/iconfonts/plugin.css" rel="stylesheet" />
    <link href="<?=url("theme/admin");?>/plugins/iconfonts/icons.css" rel="stylesheet" />
    <link href="<?=url("theme/admin");?>/fonts/fonts/font-awesome.min.css" rel="stylesheet">

</head>
<body class="bg-account">
<!-- page -->
<div class="page">
    <!-- page-content -->
    <div class="page-content">
        <div class="container text-center text-dark">
            <div class="display-1  text-dark mb-5">500</div>
            <p class="h5 font-weight-normal mb-7 leading-normal">Oops! Erro interno do servidor.</p>
            <a class="btn btn-primary  mb-5" href="javascript:void(0);" onclick="window.history.back();" >
                Voltar para tela inicial
            </a>
        </div>
    </div>
    <!-- page-content end -->
</div>
<!-- page End-->

<!-- Jquery js-->
<script src="<?=url("theme/admin");?>/assetsjs/vendors/jquery-3.2.1.min.js"></script>

<!--Bootstrap.min js-->
<script src="<?=url("theme/admin");?>/plugins/bootstrap/popper.min.js"></script>
<script src="<?=url("theme/admin");?>/plugins/bootstrap/js/bootstrap.min.js"></script>

<!--Jquery Sparkline js-->
<script src="<?=url("theme/admin");?>/assetsjs/vendors/jquery.sparkline.min.js"></script>

<!-- Chart Circle js-->
<script src="<?=url("theme/admin");?>/assetsjs/vendors/circle-progress.min.js"></script>

<!-- Star Rating js-->
<script src="<?=url("theme/admin");?>/plugins/rating/jquery.rating-stars.js"></script>

<!-- Sidebar Accordions js -->
<script src="<?=url("theme/admin");?>/plugins/accordion1/js/easyResponsiveTabs.js"></script>

<!--Moment js-->
<script src="<?=url("theme/admin");?>/plugins/moment/moment.min.js"></script>

<!-- Daterangepicker js-->
<script src="<?=url("theme/admin");?>/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Custom scroll bar js-->
<script src="<?=url("theme/admin");?>/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- Custom js-->
<script src="<?=url("theme/admin");?>/js/custom.js"></script>

</body>
</html>