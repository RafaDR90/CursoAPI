<?php
namespace routes;

use controllers\APIPonenteController,
    lib\ResponseHttp,
    lib\Security,
    controllers\AuthController;

use lib\Router;
class Routes{
    const PATH="/cursoAPI";

    /**
     * Obtiene las rutas de la aplicacion
     * @return void
     */
    public static function getRoutes(){
    $router=new Router();

    // CREO CONTROLADORES
    $APIPonenteController=new APIPonenteController();

    // PAGINA PRINCIPAL
        $router->post(self::PATH.'/creaPonente', function () use ($APIPonenteController){
            $APIPonenteController->creaPonente();
        });


    // LA PAGINA NO SE ENCUENTRA
        $router->any('/404', function (){
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