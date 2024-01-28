<?php
namespace routes;

use controllers\APIPonenteController,
    lib\ResponseHttp,
    lib\Security,
    controllers\AuthController,
    controllers\UsuarioController,
    lib\Pages;

use FontLib\Table\Type\post;
use lib\Router;
class Routes{
    const PATH="/CursoAPI";


    /**
     * Obtiene las rutas de la aplicacion
     * @return void
     */
    public static function getRoutes(){
    $router=new Router();

    // CREO CONTROLADORES
    $APIPonenteController=new APIPonenteController();
    $usuarioController=new UsuarioController();

    // PAGINA PRINCIPAL
    $router->get(self::PATH, function () use ($usuarioController){
        $pages=new Pages();
        $pages->render('landingPage/LandingPageView');
        });

    //USUARIO
        $router->get(self::PATH."/registro", function () use ($usuarioController){
            $usuarioController->showRegister();
        });
        $router->post(self::PATH."/registro", function () use ($usuarioController){
            $usuarioController->showRegister();
        });

    //PONENTE CRUD
        $router->post(self::PATH.'/creaPonente', function () use ($APIPonenteController){
            $APIPonenteController->creaPonente();
        });
        $router->put(self::PATH.'/editaPonente', function () use ($APIPonenteController){
            $APIPonenteController->editaPonente();
        });
        $router->delete(self::PATH.'/borraPonente', function () use ($APIPonenteController){
            $APIPonenteController->borraPonente();
        });
        $router->get(self::PATH.'/listaPonentes', function () use ($APIPonenteController){
            $APIPonenteController->listaPonentesAll();
        });


    // LA PAGINA NO SE ENCUENTRA
        $router->any('/404', function (){
            die("rutanoexiste");
            header('Location: ' . self::PATH . '/error');
            });
        $router->get(self::PATH.'/error', function (){
            ResponseHttp::statusMessage(404, "La pagina no se encuentra");
            });

        // PRUEBAS
        $router->get(self::PATH.'/pruebaauth', function (){
            echo Security::encryptPassword("Rafa");
            });
        $router->get(self::PATH.'/pruebavalidacion', function (){
            echo Security::validatePassword("Rafa", '$2y$10$As1arMFfOxIqsNk/edHNKOw/YD4DhrGYjKd834O1DJhGEBTjOS7P2');
        });
        $router->get(self::PATH.'/pruebakey', function (){
            echo Security::claveSecreta();
        });

        $router->get(self::PATH.'/pruebatoken', function (){
            echo Security::createToken(Security::claveSecreta(), ["nombre"=>"Rafa", "apellido"=>"Garcia","email"=>"rafa@rafa.com"]);
        });

        $router->resolve();
        }

}

?>