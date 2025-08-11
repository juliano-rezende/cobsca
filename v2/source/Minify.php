<?php

$minify = filter_input(INPUT_GET, "minify", FILTER_VALIDATE_BOOLEAN);

if ($_SERVER["SERVER_NAME"] == "localhost" || $minify) {

    /**
     * mimify arquivos css e js página login.php
     */
//    $minCSS = new MatthiasMullie\Minify\CSS();
//    $minCSS->add(dirname(__DIR__, 1) . "/assets/css/main.css");
//    $minCSS->minify(dirname(__DIR__, 1) . "/assets/initial.min.css");


    /**
     * ARQUIVOS BASE PARA TODAS AS PÁGINAS
     */

    /*
     * <script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
     */
    $minJS = new MatthiasMullie\Minify\JS();
    $minJS->add(dirname(__DIR__, 1) . "/vendors/jquery/dist/jquery.min.js");
    $minJS->add(dirname(__DIR__, 1) . "/vendors/popper.js/dist/umd/popper.min.js");
    $minJS->add(dirname(__DIR__, 1) . "/vendors/bootstrap/dist/js/bootstrap.min.js");
    $minJS->minify(dirname(__DIR__, 1) . "/assets/js/min/jquery_boostrap.min.js");


    $minJS1 = new MatthiasMullie\Minify\JS();
    $minJS1->add(dirname(__DIR__, 1) . "/assets/js/login.js");
    $minJS1->minify(dirname(__DIR__, 1) . "/assets/js/min/login.min.js");

    $minJS2 = new MatthiasMullie\Minify\JS();
    $minJS2->add(dirname(__DIR__, 1) . "/assets/js/register.js");
    $minJS2->minify(dirname(__DIR__, 1) . "/assets/js/min/register.min.js");

    $minJS3 = new MatthiasMullie\Minify\JS();
    $minJS3->add(dirname(__DIR__, 1) . "/assets/js/forget.js");
    $minJS3->minify(dirname(__DIR__, 1) . "/assets/js/min/forget.min.js");

    $minJS4 = new MatthiasMullie\Minify\JS();
    $minJS4->add(dirname(__DIR__, 1) . "/assets/js/reactivation.js");
    $minJS4->minify(dirname(__DIR__, 1) . "/assets/js/min/reactivation.min.js");





}
