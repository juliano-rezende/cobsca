<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <META HTTP-EQUIV="Pragma" CONTENT="no cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="cache-control" content="no-store,no-cache,must-revalidate"/>
    <meta http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT"/>
    <meta http-equiv="Last-Modified" content="Mon, 26 Jul 1997 05:00:00 GMT"/>
    <meta http-equiv="Expires" content="pt-br"/>
    <link rel="shortcut icon" href="imagens/favi-con.ico" type="image/x-icon">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/uikit.css">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/tooltip.min.css">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/notify.min.css">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/datepicker.min.css">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/form-advanced.min.css">
    <link href="css/style_window.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css"/>
    <link href="css/style_forms.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css"/>
    <link href="css/doc.uikit.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css"/>
<style>
    .uk-button-large{padding:25px; font-size: 30px}
</style>
</head>
<body class="uk-height-1-1" style=" height: 100vh;">
<div class="uk-vertical-align uk-text-center" style="padding-top: 10px;">
    <div class="uk-vertical-align-middle uk-text-center">
        <img class="uk-margin-bottom" width="140" height="120" src="imagens/empresas/001.png" alt="">
    </div>
    <div class="uk-grid" data-uk-grid-margin="" style="height: 100%; display: block">
        <div class="uk-width-medium-1-1 uk-row-first">
            <div class="uk-vertical-align uk-text-center" style=" height:500px; background-color: #f5f5f5;">
                <div class="uk-vertical-align-middle uk-width-1-2">
                    <h1 class="uk-heading-large" style="font-size: 200pt; padding: 60px; margin-bottom: 50px;" id="target">0000</h1>
                    <p class="uk-text- uk-margin-top uk-text-danger" style="font-size: 20pt; display: none;" id="nome"></p>
                    <p class="uk-text- uk-margin-top uk-text-danger" style="font-size: 20pt; display: none;" id="cpf"></p>
                </div>
            </div>
            <div class="uk-vertical-align uk-text-center uk-margin-top">
                <div class="uk-vertical-align-middle">
                    <a class="uk-button uk-button-large uk-button-success" data-action="start" href="#" id="iniciarbtn">Iniciar</a>
                    <a class="uk-button uk-button-large uk-button-primary" data-action="stop" href="#" id="pararbtn" style="display: none;">Parar</a>
                    <a class="uk-button uk-button-large uk-button-warning" data-action="clean" href="#" id="cleanbtn" style="display: none;">Limpar</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="js/jquery/jquery-1.9.1.js?<?php echo microtime(); ?>"></script>
<script type="text/javascript" src="framework/uikit-2.24.0/js/uikit.min.js"></script>
<script type="application/javascript">
    var createClock;
    $("#cleanbtn,#pararbtn").hide();

    function displayTime() {
        jQuery.ajax({
            type: "POST",
            url: "assets/sorteio/sorteio.php",
            cache: false,
            dataType: "json"
        }).done(function (data) {
            $("#target").html(data.matricula);
            $("#nome").html("Nome: "+data.nome);
            $("#cpf").html("CPF: "+data.cpf);
        });
    }

    function sstart() {
        createClock = setInterval(displayTime, 200);
    }

    function sstop() {
        clearInterval(createClock);
    }

    function sclean() {
        $("#target").html("0000");
        $("#nome").html("");
        $("#cpf").html("");
    }

    $("body").on("click", "[data-action]", function (e) {
        e.preventDefault();
        var action = $(this).attr("data-action");
        if (action == "start") {
            sstart();
            $("#cleanbtn,#iniciarbtn,#nome,#cpf").hide();
            $("#pararbtn").show();
        }
        if (action == "stop") {
            sstop();
            $("#cleanbtn,#nome,#cpf").show();
            $("#pararbtn").hide();
        }
        if (action == "clean") {
            sclean();
            $("#iniciarbtn").show();
            $("#cleanbtn").hide();

        }
    });
</script>
</html>