<?php

require_once __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(URL_BASE);


/*
 * AUTENTICAÇÃO USUARIO
 */
$router->group(null)->namespace("Source\App\User");
$router->get("/", "ControllherAuthUsers:formAuth");
$router->post("/login", "ControllherAuthUsers:authUser", "form.auth.user");
$router->get("/sair", "ControllherAuthUsers:logout");
$router->get("/recuperar", "ControllherAuthUsers:formRecovery");
$router->get("/recuperar/{key}", "ControllherAuthUsers:formNewPassword");
$router->post("/recuperar-senha", "ControllherAuthUsers:emailRecoveryUser", "form.recovery.User");
$router->post("/recuperar-senha-confirma", "ControllherAuthUsers:confirmRecoveryUser", "form.confirm.recovery.user");
$router->get("/perfil", "ControllerProfileUser:profile");
$router->post("/editar-perfil", "ControllerProfileUser:updateProfile", "form.perfil.edit");
$router->post("/editar-senha", "ControllerProfileUser:updatePassword", "form.pwd.edit");




/*
 * ERROS
 */
$router->group("ooops")->namespace("Source\App\Error");
$router->get("/{errcode}", "ControllerError:allErrors");

$router->dispatch();

if ($router->error()) {
    $router->redirect("/ooops/{$router->error()}");
}