<?php


namespace Source\App\Error;


use League\Plates\Engine;

class ControllerError
{

    /** @var  $error*/
    private $error;

    /** @var $view */
    private $view;


    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../../theme/views/error", "php");
    }

    public function allErrors(array $data): void
    {

        $this->error = $data["errcode"];


        if ($this->error == 400) {

            echo $this->view->render("400",[
                "title" => "Erro 400 ".URL_BASE
            ]);


        } elseif ($this->error == 401) {

            echo $this->view->render("401", [
                "title" => "Erro 401 " . URL_BASE
            ]);

        } elseif ($this->error == 403) {

            echo $this->view->render("403", [
                "title" => "Erro 403 | " . URL_BASE
            ]);

        } elseif ($this->error == 404) {

            echo $this->view->render("404", [
                "title" => "Erro 404 | " . URL_BASE
            ]);

        } elseif ($this->error == 405) {

            echo $this->view->render("405", [
                "title" => "Erro 405 | " . URL_BASE
            ]);

        } elseif ($this->error == 500) {

            echo $this->view->render("500", [
                "title" => "Erro 500 | " . URL_BASE
            ]);

        } elseif ($this->error == 501) {

            echo $this->view->render("501", [
                "title" => "Erro 501 | " . URL_BASE
            ]);

        } elseif ($this->error == 503) {

            echo $this->view->render("503", [
                "title" => "Erro 503 | " . URL_BASE
            ]);

        } else {

            echo $this->view->render("notFound", [
                "title" => "Erro notFound | " . URL_BASE
            ]);

        }


    }


}