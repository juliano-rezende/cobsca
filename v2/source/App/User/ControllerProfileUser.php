<?php


namespace Source\App\User;

use League\Plates\Engine;
use Source\App\User\ControllerSessionUsers;



/**
 * Class ControllherAdmin
 * @package Source\App
 */
class ControllerProfileUser
{

    /** @var Engine */
    private $view;

    /**
     * ControllherAdmin constructor.
     */
    public function __construct($router)
    {
        $this->view = Engine::create(dirname(__DIR__, 2) . "/theme/views/user", "php");
        $this->view->addData(["router" => $router]);
    }

    public function profile(array $data): void
    {
        $lg = (new ControllerSessionUsers())->requireloginUser();

        $company = (new dashCompanys())->findById($_SESSION['dashUser']["id"]);
        $config = (new dashCompanyConfigs())->findById($_SESSION['dashUser']["id"]);

        echo $this->view->render("profile/profile", [
            "title" => "Perfil | " . URL_BASE,
            "company" => $company,
            "company_config" => $config
        ]);

    }

    public function updateProfile(array $data): void
    {
        $lg = (new ControllerSessionUsers())->requireloginUser();

        $this->firstname    = $data["firstname"];
        $this->lastname     = $data["lastname"];
        $this->email        = $data["email"];
        $this->countryphone = $data["countryphone"];
        $this->phone        = $data["phone"];
        $this->obs          = $data["obs"];
        $this->website      = $data["website"];


        if (empty($this->firstname) or empty($this->lastname) or empty($this->phone) or empty($this->email) or empty($this->countryphone)) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "os campos com (*) são obrigatórios.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $callback["error"] = true;
            $callback["message"] = ["title" => "Oooops!", "text" => "Email invalido.", "type" => "error"];
            echo json_encode($callback);
            die;
        }

        $Seller = (new dashCompanys())->findById($_SESSION['dashUser']["id"]);
        $Seller->responsible_first_name     = $this->firstname;
        $Seller->responsible_last_name      = $this->lastname;
        $Seller->responsible_email          = $this->email;
        $Seller->country_phone              = $this->countryphone;
        $Seller->responsible_phone_number   = $this->phone;
        $Seller->website                    = $this->website;
        $Seller->obs                        = $this->obs;
        $SellerId                           = $Seller->save();

        $callback["message"] = ["title" => "Sucesso.", "text" => "Seus dados foram atualizados.", "type" => "success"];
        echo json_encode($callback);
        die;

    }

    public function updatePassword(array $data): void
    {
        $lg = (new ControllerSessionUsers())->requireloginUser();

        $this->login            = $data["login"];
        $this->lastPassword     = $data["lastpwd"];
        $this->password         = trim($data["pwd"]);
        $this->passwordConfirm  = $data["confpwd"];

        $Seller = (new dashCompanys())->findById($_SESSION['dashCompanys']["id"]);

        if(isset($data["altpwd"])){

            if (!password_verify($this->lastPassword, $Seller->password)) {

                $callback["error"] = true;
                $callback["message"] = ["title" => "Oooops!", "text" => "Senha atual invalida.", "type" => "error"];
                echo json_encode($callback);
                return;
            }

            if ($this->password != $this->passwordConfirm) {
                $callback["error"] = true;
                $callback["message"] = ["title" => "Oooops!", "text" => "Nova senha e confirma senha não conferem.", "type" => "error"];
                echo json_encode($callback);
                return;
            }

            $this->password = password_hash("{$this->password}", PASSWORD_DEFAULT);

            $Seller->login        = $this->login;
            $Seller->password     = $this->password;
            $SellerId             = $Seller->save();

            $callback["message"] = ["title" => "Sucesso.", "text" => "Sua senha foi atualizada.", "type" => "success"];
            echo json_encode($callback);
            die;

        }else{

            $Seller->login        = $this->login;
            $SellerId             = $Seller->save();

            $callback["message"] = ["title" => "Parabéns.", "text" => "Login atualizado com sucesso.", "type" => "success"];
            echo json_encode($callback);
            die;
        }
    }


}