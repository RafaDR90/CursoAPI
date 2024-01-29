<?php
namespace controllers;
use lib\ResponseHttp,
    lib\BaseDeDatos,
    models\Ponente,
    services\PonenteService,
    repositoris\UsuarioRepository;
use lib\Security;


class APIPonenteController{
    private PonenteService $ponenteService;
    public function __construct()
    {
        $this->ponenteService = new PonenteService();
    }

    public function creaPonente()
    {
        ResponseHttp::getCabeceras('POST');

        $tokenData=Security::getTokenData();

        if (!$tokenData){
            ResponseHttp::statusMessage(401, "No autorizado");
            exit();
        }

        $usuarioRepository=new UsuarioRepository();
        //Si el email no esta en la BD hace un 401
        $usuario=$usuarioRepository->getUsuarioPorEmail($tokenData->data->email);
        if (!$usuario){
            ResponseHttp::statusMessage(401, "No autorizado");
            exit();
        }
        if ($usuario[0]['token_id']!=$tokenData->data->token_id){
            ResponseHttp::statusMessage(401, "No autorizado");
            exit();
        }

        $data=json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->apellidos) && !empty($data->email) && !empty($data->imagen) && !empty($data->tags) && !empty($data->redes)){
            $ponente=new Ponente(null, $data->nombre, $data->apellidos, $data->email, $data->imagen, $data->tags, $data->redes);
            if ($this->ponenteService->creaPonente($ponente)){
                $nuevoTokenId=Security::generarTokenId();
                $nuevoToken=Security::createToken(Security::claveSecreta(), ['email'=>$usuario[0]['email'], 'rol'=>$usuario[0]['rol'], 'token_id'=>$nuevoTokenId]);
                if (!$usuarioRepository->actualizaToken($usuario[0]['id'], $nuevoToken, date("Y-m-d H:i:s", time() + 1800), $nuevoTokenId)){
                    ResponseHttp::statusMessage(503, "No se ha podido actualizar el token, vuelva a hacer login para obtener uno nuevo");
                    exit();
                }
                ResponseHttp::statusMessage(201, "Ponente creado");
                echo json_encode(['NuevoToken'=>$nuevoToken]);
            }else{
                ResponseHttp::statusMessage(503, "No se ha podido crear el ponente");
            }
        }
    }

    public function editaPonente(){
        ResponseHttp::getCabeceras('PUT');
        $data=json_decode(file_get_contents("php://input"));
        if (!empty($data->id) && !empty($data->nombre) && !empty($data->apellidos) && !empty($data->email) && !empty($data->imagen) && !empty($data->tags) && !empty($data->redes)){
            $ponente=new Ponente($data->id, $data->nombre, $data->apellidos, $data->email, $data->imagen, $data->tags, $data->redes);
            if ($this->ponenteService->editaPonente($ponente)){
                ResponseHttp::statusMessage(201, "Ponente editado");
            }else{
                ResponseHttp::statusMessage(204, "No se ha podido editar el ponente");
            }
        }else{
            ResponseHttp::statusMessage(503, "Faltan datos");
        }
    }

    public function borraPonente()
    {
        ResponseHttp::getCabeceras('DELETE');
        $data=json_decode(file_get_contents("php://input"));
        if (!empty($data->id)){
            $ponente=new Ponente($data->id);
            if ($this->ponenteService->borraPonente($ponente)){
                ResponseHttp::statusMessage(202, "Ponente borrado");
            }else{
                ResponseHttp::statusMessage(503, "No se ha podido borrar el ponente");
            }
        }else{
            ResponseHttp::statusMessage(503, "Faltan datos");
        }
    }

    public function listaPonentesAll()
    {
        ResponseHttp::getCabeceras('GET');
        $ponentes=$this->ponenteService->listaPonentesAll();
        if (isset($ponentes)){
            ResponseHttp::statusMessage(200, json_encode($ponentes));
            echo json_encode($ponentes);
        }else{
            ResponseHttp::statusMessage(503, "No se ha podido obtener la lista de ponentes");
        }

    }

}