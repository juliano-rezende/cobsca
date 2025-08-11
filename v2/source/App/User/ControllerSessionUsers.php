<?php

namespace Source\App\User;

/**
 * Class ControllerSessionUsers
 * @package Source\App\SessionSeller
 */
class ControllerSessionUsers
{
    /**
     * ControllerSessionUsers constructor.
     */
    public function __construct()
    {}

    /**
     *  SESSION
     * metodo por iniciar a sessão
     */
    private function initUser()
    {
        /**
         * verifica o status da sessão
         */
        if (session_status() != PHP_SESSION_ACTIVE) {
            /**
             * inicia a sessão
             */
            session_start();
        }
    }

    /**
     * METODO DE LOGAR O USUARIO
     * @param $obSeller
     */
    public function loginSeller($obSeller,$user)
    {
        /*
         * inicia a sessão
         */
        self::initUser();

        /*
         * session de usuario
         */
        $_SESSION['dashUser'] = [
            "id"                => $obSeller->id,
            "mkt_place"         => $obSeller->mkt_place,
            "seller_token"      => $obSeller->seller_token,
            "token_api_cob"     => $obSeller->token_api_cob,
            "cob_api_id"        => $obSeller->cob_api_id,
            "login"             => $obSeller->login,
            "avatar"            => $obSeller->avatar,
            "master"            => $user
        ];

        /*
         * redireciona o usuario para home
         */
        $callback["is_logged"] = ["codigo" => "200", "url_is_logged" => "" . URL_BASE . "/dashboard/inicio"];
        echo json_encode($callback);
        die;
    }

    /*
     * METODO PARA DESLOGAR O USUARIO
     */

    /**
     *
     */
    public function destroySessionUser()
    {
        /*
         * inicia a sessão
         */
        self::initUser();
        unset($_SESSION['dashUser']);
        session_destroy($_SESSION['dashUser']);

        header('location:' . URL_BASE . '/dashboard');
        die;
    }

    /*
     * DADOS DO USUARIO
     * retorna os dados da sessão do usuario
     */

    /**
     * @return mixed|null
     */
    public function getDataUserLogged()
    {
        /*
         * inicia a sessão
         */
        self::initUser();
        /*
         * retorna os dados do usuario
         */
        return self::isLoggedUser() ? $_SESSION['dashUser'] : null;
    }

    /**
     * @return bool
     */
    public function isLoggedUser()
    {
        /*
         * inicia a sessão
         */
        self::initUser();
        return isset($_SESSION['dashUser']['id']);
        header('location:' . URL_BASE . '/dashboard');
        die;
    }

    /**
     *
     */
    public function requiredloginUser()
    {
        if (!self::isLoggedUser()) {
            header('location:' . URL_BASE . '/dashboard');
            die;
        } else {
            /*
             * carrega os dados da sessão do usuario
             */
            self::getDataUserLogged();
        }
    }

    /**
     *
     */
    public function requireLogoutUser()
    {
        if (self::isLoggedUser()) {
            header('location:' . URL_BASE . '/dashboard/inicio');
            die;
        }
    }

}