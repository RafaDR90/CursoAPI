<?php
namespace controllers;

use lib\Pages,
    utils\Utils;
use utils\ValidationUtils,
    models\Usuario,
    repositoris\UsuarioRepository,
    lib\Security;

class UsuarioController{
    private Pages $pages;
    private UsuarioRepository $usuarioRepository;
    public function __construct()
    {
        $this->pages=new Pages();
        $this->usuarioRepository=new UsuarioRepository();
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
        $email=ValidationUtils::sanidarStringFiltro($_POST['datos']['email']);
        $password=ValidationUtils::sanidarStringFiltro($_POST['datos']['password']);
        $usuario=Usuario::fromArray([['email'=>$email,'password'=>$password]]);
        $resultado=$usuario[0]->validaEmailYPassword();
        if(is_string($resultado)){
            $this->pages->render('usuario/RegisterView', ['error'=>$resultado]);
            exit();
        }
        if (!$this->usuarioRepository->emailDisponible($usuario[0]->getEmail())){
            $this->pages->render('usuario/RegisterView', ['error'=>'El email ya esta registrado']);
            exit();
        }
        $usuario[0]->setPassword(Security::encryptPassword($usuario[0]->getPassword()));
        $usuario[0]->setToken(Security::createToken(Security::claveSecreta(),['email'=>$usuario[0]->getEmail(),'rol'=>$usuario[0]->getRol()]));
        $usuario[0]->setTokenExp(date("Y-m-d H:i:s", time() + 1800));
        $usuario[0]->setConfirmado(false);
        if($this->usuarioRepository->insertaUsuario($usuario[0])){
            $htmlContent=Utils::creaHtmlContentConfirmacion($usuario[0]);
            Utils::enviarCorreoConfirmacion($htmlContent, $usuario[0]->getEmail());
            $this->pages->render('usuario/RegisterView', ['exito'=>'Usuario registrado correctamente']);
            exit();
        }else{
            $this->pages->render('usuario/RegisterView', ['error'=>'Error al registrar el usuario']);
            exit();
        }
    }
    public function creaToken()
    {
        if (!Utils::isLogued()){
            $this->pages->render('landingPage/LandingPageView');
            exit();
        }
        $newToken=Security::createToken(Security::claveSecreta(),['email'=>$_SESSION['usuario']['email'],'rol'=>$_SESSION['usuario']['rol']]);
        $newTokenExp=date("Y-m-d H:i:s", time() + 1800);
        $userInBd=$this->usuarioRepository->getUsuarioPorEmail($_SESSION['usuario']['email']);
        $updToken=$this->usuarioRepository->actualizaToken($userInBd[0]['id'], $newToken, $newTokenExp);
        if (!$updToken){
            $this->pages->render('usuario/LoginView', ['error'=>'Error al actualizar el token, contacte con soporte tecnico']);
            exit();
        }
        $this->pages->render('usuario/TokenView', ['token'=>$newToken,]);
    }
    public function autentificarToken($token)
    {
        $tokenData=Security::getTokenDataOf($token);
        $token=ValidationUtils::sanidarStringFiltro($token);
        if (!$tokenData){
            $this->usuarioRepository->borrarUsuarioPorToken($token);
            $this->pages->render('usuario/RegisterView', ['error'=>'Token invalido, vuelva a registrarse']);
            exit();
        }
        $resultado=$this->usuarioRepository->confirmaUsuario($token);
        if (!$resultado){
            $this->pages->render('usuario/RegisterView', ['error'=>'Parece que ha habido algun problema al confirmas el usuario, contacte con soporte tecnico']);
            exit();
        }
        $this->pages->render('usuario/LoginView', ['exito'=>'Usuario confirmado correctamente']);
    }

    public function showLogin()
    {
        if (Utils::isLogued()){
            $this->pages->render('landingPage/LandingPageView');
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"]!="POST"){
            $this->pages->render('usuario/LoginView');
            exit();
        }
        $email=ValidationUtils::sanidarStringFiltro($_POST['datos']['email']);
        $password=ValidationUtils::sanidarStringFiltro($_POST['datos']['password']);
        $usuario=Usuario::fromArray([['email'=>$email,'password'=>$password]]);
        $resultado=$usuario[0]->validaEmailYPassword();
        if(is_string($resultado)){
            $this->pages->render('usuario/LoginView', ['error'=>$resultado]);
            exit();
        }
        $userInBd=$this->usuarioRepository->getUsuarioPorEmail($usuario[0]->getEmail());
        if (!$userInBd){
            $this->pages->render('usuario/LoginView', ['error'=>'El usuario no existe']);
            exit();
        }
        if (!Security::validatePassword($usuario[0]->getPassword(), $userInBd[0]['password'])){
            $this->pages->render('usuario/LoginView', ['error'=>'La contraseña no es correcta']);
            exit();
        }
        if (!$userInBd[0]['confirmado']){
            $this->pages->render('usuario/LoginView', ['error'=>'El usuario no esta confirmado, revise su correo']);
            exit();
        }
        //email y contraseña validos, se crea un nuevo token y se guarda en la bd
        $newToken=Security::createToken(Security::claveSecreta(),['email'=>$userInBd[0]['email'],'rol'=>$userInBd[0]['rol']]);
        $newTokenExp=date("Y-m-d H:i:s", time() + 1800);
        $updToken=$this->usuarioRepository->actualizaToken($userInBd[0]['id'], $newToken, $newTokenExp);
        if (!$updToken){
            $this->pages->render('usuario/LoginView', ['error'=>'Error al actualizar loguear el usuario, contacte con soporte tecnico']);
            exit();
        }
        $_SESSION['usuario']=['email'=>$userInBd[0]['email'],'rol'=>$userInBd[0]['rol']];
        $this->pages->render('usuario/TokenView', ['token'=>$newToken,'exito'=>'Usuario logueado correctamente']);
    }

    public function logout()
    {
        Utils::deleteSession('usuario');
        $this->pages->render('landingPage/LandingPageView');
    }
}