<?php
namespace controllers;

use lib\Pages;

class UsuarioController{
    private Pages $pages;
    public function __construct()
    {
        $this->pages=new Pages();
    }

    public function showLandingPage():void{
        ob_start();
        $this->pages->render('landingPage/LandingPageView');
        ob_end_flush();
    }
}