<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Favicon -->
    <link rel="icon" href="<?=url("theme/site");?>/images/brand/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?=url("theme/site");?>/images/brand/favicon.ico" />

    <!-- Title -->
    <title><?= $title; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=url("theme/site");?>/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="<?=url("theme/site");?>/css/ionicons.min.css">
    <link rel="stylesheet" href="<?=url("theme/site");?>/css/owl.carousel.css">
    <link rel="stylesheet" href="<?=url("theme/site");?>/css/owl.theme.css">
    <link rel="stylesheet" href="<?=url("theme/site");?>/css/style.css">
</head>
<body>
<header id="home" class="gradient-violat">
    <nav class="navbar navbar-default navbar-fixed-top">
        <?php if ($v -> section("sidebar")):
            echo $v -> section("sidebar");
        else:
            ?>
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span class="logo-wraper logo-white">
                <img src="<?= url("theme/site"); ?>/images/Logo.png" alt="">lazy fox
              </span></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav  navbar-right">
                        <li class="active"><a href="#home">Home <span class="sr-only">(current)</span></a></li>
                        <li><a href="#blog-card">About</a></li>
                        <li><a href="#introduction">Intro</a></li>
                        <li><a href="#feature">Product</a></li>
                        <li><a href="#newsletter">Contact</a></li>
                        <li><a href="#" class="btn btn-orange border-none btn-rounded-corner btn-navbar">Download<span class="icon-on-button"><i class="ion-ios-cloud-download-outline"></i></span></a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
                <hr class="navbar-divider">
            </div><!-- /.container-fluid -->
        <?php
        endif;
        ?>
    </nav>
</header>
<?= $v->section("content");?>
<footer class="padding-top-120">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="footer-left-content">
                    <div class="logo-colored">
                        <img src="<?=url("theme/site");?>/images/Logo_color.png" alt="">lazy fox
                    </div>
                    <div class="content">
                        <p class="margin-bottom-30 margin-top-30">Built using the latest web technologies like css3, and jQuery, rest assured Sedna look.</p>
                        <p>+88456895872, +88456595572</p>
                        <p>example@mail.com. lazyfox@mail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="footer-list-wiget">
                            <h4>learn more</h4>
                            <div class="list-group">
                                <a href="#" class="list-group-item">About Dolphin</a>
                                <a href="#" class="list-group-item">Learn CSS</a>
                                <a href="#" class="list-group-item">Meeting Tools</a>
                                <a href="#" class="list-group-item">Pricing</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="footer-list-wiget">
                            <h4>Support</h4>
                            <div class="list-group">
                                <a href="#" class="list-group-item">FAQ</a>
                                <a href="#" class="list-group-item">Contact US</a>
                                <a href="#" class="list-group-item">Outlook Plugin</a>
                                <a href="#" class="list-group-item">Phone Control</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="footer-list-wiget">
                            <h4>About</h4>
                            <div class="list-group">
                                <a href="#" class="list-group-item">About US</a>
                                <a href="#" class="list-group-item">Careear</a>
                                <a href="#" class="list-group-item">Privat policy</a>
                                <a href="#" class="list-group-item">Treams</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="footer-list-wiget">
                            <h4>Links</h4>
                            <div class="list-group">
                                <a href="#" class="list-group-item">Home</a>
                                <a href="#" class="list-group-item">About</a>
                                <a href="#" class="list-group-item">Product</a>
                                <a href="#" class="list-group-item">Intro</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="copyright-cta">
            <p class="text-uppercase">All rights Reserved By <a href="https://themewagon.com/"><span class="span text-violat ">Themewagon</span></a></p>
        </div>
    </div>
    </div>
    <div class="footer-end-line"></div>
</footer>
<div id="scroll-top-div" class="scroll-top-div">
    <div class="scroll-top-icon-container">
        <i class="ion-ios-arrow-thin-up"></i>
    </div>
</div>
<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="<?=url("theme/site");?>/js/jquery.min.js"></script>
<script src="<?=url("theme/site");?>/js/bootstrap.min.js"></script>
<script src="<?=url("theme/site");?>/js/owl.carousel.min.js"></script>
<script src="<?=url("theme/site");?>/js/script.js"></script>
<?= $v->section("scripts");?>
</body>
</html>
