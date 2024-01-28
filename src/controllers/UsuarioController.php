<?php
namespace controllers;

use lib\Pages,
    utils\Utils;
use Utils\ValidationUtils;

class UsuarioController{
    private Pages $pages;
    public function __construct()
    {
        $this->pages=new Pages();
    }

    public function showRegister()
    {
        if (Utils::isLogued()){
            $this->pages->render('landingPage/LandingPageView');
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"]!="POST"){
            $this->pages->render('usuario/RegisterView');
            exit();
        }
        $email=ValidationUtils::sanidarStringFiltro($_POST['email']);
        $password=ValidationUtils::sanidarStringFiltro($_POST['password']);

    }
}