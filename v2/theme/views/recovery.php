<!doctype html>
<html lang="pt-br" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="ConnectChat - Admin wtsApi" name="description">
    <meta content="Inova tecnologia" name="author">
    <meta name="keywords" content="admin chabot, chatbot, wtsApi chatbot, chat para whatsApp "/>

    <!-- Favicon -->
    <link rel="icon" href="<?= url() . URL_UPLOADS["system"]; ?>/brands/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="<<?= url() . URL_UPLOADS["system"]; ?>/brands/favicon.ico"/>

    <!-- Title -->
    <title><?= $title; ?></title>

    <!--Bootstrap.min css-->
    <link rel="stylesheet" href="<?= url("theme/admin"); ?>/plugins/bootstrap/css/bootstrap.min.css">

    <!-- Dashboard css -->
    <link href="<?= url("theme/admin"); ?>/css/style.css" rel="stylesheet"/>

    <!---Font icons css-->
    <link href="<?= url("theme/admin"); ?>/plugins/iconfonts/plugin.css" rel="stylesheet"/>
    <link href="<?= url("theme/admin"); ?>/plugins/iconfonts/icons.css" rel="stylesheet"/>
    <link href="<?= url("theme/admin"); ?>/fonts/fonts/font-awesome.min.css" rel="stylesheet">

    <!---Sweetalert Css-->
    <link href="<?= url("theme/admin"); ?>/plugins/sweet-alert/jquery.sweet-modal.min.css" rel="stylesheet"/>
    <link href="<?= url("theme/admin"); ?>/plugins/sweet-alert/sweetalert.css" rel="stylesheet"/>

</head>
<body>
<!--Global-Loader-->
<div id="global-loader">
    <img src="<?= url("theme/admin"); ?>/images/icons/loader.svg" alt="loader">
</div>
<!-- page -->
<div class="page">
    <!-- page-content -->
    <div class="page-content">
        <div class="container text-center text-dark">
            <div class="row">
                <div class="col-lg-6 d-block mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-md-12">
                            <div class="card p-5">
                                <div class="card-body">
                                    <?php
                                    if (isset($invalid)) {
                                        ?>
                                        <div class="container text-center text-dark">
                                            <div class="display-1  text-dark mb-5">401</div>
                                            <p class="h5 font-weight-normal mb-7 leading-normal"><?=$invalid;?></p>
                                            <a class="btn btn-primary  mb-5" href="<?= url(); ?>/empresas">
                                                Voltar para tela inicial
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    elseif (isset($expired)) {
                                        ?>
                                        <div class="container text-center text-dark">
                                            <div class="display-1  text-dark mb-5">419</div>
                                            <p class="h5 font-weight-normal mb-7 leading-normal"><?=$expired;?></p>
                                            <a class="btn btn-primary  mb-5" href="<?= url(); ?>/empresas">
                                                Voltar para tela inicial
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    elseif (isset($id)) {
                                        $key = base64_encode($id);
                                        ?>
                                        <form id="formConfirmRecovery" action="<?= $router->route("form.confirm.recovery.company"); ?>">
                                            <div class="text-center p-5">
                                                <img src="<?= url() . URL_UPLOADS["system"]; ?>/brands/logo.png" class="" width="140" alt="Logo chat">
                                            </div>
                                            <h5>Cadastrar nova senha</h5>
                                            <p class="text-muted">Digite sua nova senha</p>
                                            <input type="hidden" class="form-control" id="key" name="key" value="<?= $key; ?>">
                                            <div class="input-group mb-3">
                                                <span class="input-group-addon bg-white"><i class="fa fa-key"></i></span>
                                                <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Senha">
                                            </div>
                                            <div class="input-group mb-4">
                                                <span class="input-group-addon bg-white"><i class="fa fa-key"></i></span>
                                                <input type="password" class="form-control" id="confpwd" name="confpwd" placeholder="Confirmar senha">
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary btn-block">Salvar</button>
                                                </div>
                                                <div class="col-12">
                                                    <a href="<?= url(); ?>/empresas" class="btn btn-link box-shadow-0 px-0">Voltar</a>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <form id="formRecovery" action="<?= $router->route("form.recovery.company"); ?>">
                                            <div class="text-center mb-6">
                                                <img src="<?= url() . URL_UPLOADS["system"]; ?>/brands/logo.png" class="" alt="Logo chat">
                                            </div>
                                            <h3>Recuperar Senha</h3>
                                            <div class="input-group mb-4">
                                                <span class="input-group-addon bg-white"><i class="fa fa-at"></i></span>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary btn-block">Recuperar</button>
                                                </div>
                                                <div class="col-12">
                                                    <a href="<?= url(); ?>/empresas" class="btn btn-link box-shadow-0 px-0">Voltar</a>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- page-content end -->
</div>
<!-- page End-->

<!-- Jquery js-->
<script src="<?= url("theme/admin"); ?>/js/vendors/jquery-3.2.1.min.js"></script>

<!--Bootstrap.min js-->
<script src="<?= url("theme/admin"); ?>/plugins/bootstrap/popper.min.js"></script>
<script src="<?= url("theme/admin"); ?>/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Sweet alert js-->
<script src="<?= url("theme/admin"); ?>/plugins/sweet-alert/jquery.sweet-modal.min.js"></script>
<script src="<?= url("theme/admin"); ?>/plugins/sweet-alert/sweetalert.min.js"></script>

<!-- scritps js-->
<script src="<?= url(); ?>/assets/js/scripts.min.js?<?=microtime();?>"></script>
</body>
</html>