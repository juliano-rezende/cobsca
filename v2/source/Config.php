<?php

/** CONFIGURAÇÕES PADRÃO DO SISTEMA*/
define("CONFIG_SYSTEM", [
    "timeZone" => "America/Sao_Paulo",
    "nameSysten" => "Sistema para controle de associados",
    "languageBase" => "pt-br",
    "version" => "1.0",
    "prefix" => "sca",
    "developed" => "INOVA Tecnologia"
]);

/** URL BASE */
define("URL_BASE", "http://localhost/www.cobsca.com.br/v3");

/** DIR ROOT UPLOADS  */
define("URL_UPLOADS", [
    "system" => "/assets/midias/system",
    "image" => "/assets/midias/upload/image",
    "audio" => "/assets/midias/upload/audio",
    "video" => "/assets/midias/upload/video",
    "file" => "/assets/midias/upload/file"
]);

/** FAVICON ICO */
define("FAVICON_ICO", "" . URL_UPLOADS["system"] . "favicon.ico");

/* DATA MYSQL */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "151.106.99.7",
    "port" => "3306",
    "dbname" => "u907824302_chat_v02",
    "username" => "u907824302_chat_v02",
    "passwd" => "=2p8&CRoQ5tR",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

// HOST SMTP
define("CONF_SMTP_MAIL", [
    "host" => "smtp.weblink.com.br",
    "port" => "587",
    "user" => "no-reply@connectchat.com.br",
    "passwd" => "@A0mHLR?@Gy",
    "from_name" => "CONNECT-CHAT - favor não responder este e-mail",
    "from_email" => "no-reply@connectchat.com.br"
]);

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri) {
        return URL_BASE . "/{$uri}";
    }
    return URL_BASE;
}