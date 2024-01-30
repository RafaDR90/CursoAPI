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

        //Valido token
        $tokenData=ResponseHttp::validaToken();
        if (!$tokenData){
            ResponseHttp::statusMessage(401, "No autorizado");
            exit();
        }

        $usuarioRepository=new UsuarioRepository();

        //extraigo el usuario con el email del token
        $usuario=$usuarioRepository->getUsuarioPorEmail($tokenData->data->email);

        //Si el email no esta en la BD hace un 401
        if (!$usuario){
            ResponseHttp::statusMessage(401, "No autorizado");
            exit();
        }
        //compruebo que el token no este caducado
        $fechaToken= \DateTime::createFromFormat('Y-m-d H:i:s', $usuario[0]['token_exp']);
        $fechaActual=new \DateTime();
        if($fechaToken->getTimestamp()<$fechaActual->getTimestamp()){
            ResponseHttp::statusMessage(401, "No autorizado");
            exit();
        }
        //expiro el token en la BD
        $usuarioRepository->expiraToken($usuario[0]['id']);

        $data=json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->apellidos) && !empty($data->email) && !empty($data->imagen) && !empty($data->tags) && !empty($data->redes)){
            $ponente=new Ponente(null, $data->nombre, $data->apellidos, $data->email, $data->imagen, $data->tags, $data->redes);
            $res=$this->ponenteService->creaPonente($ponente);
            if (!$res){
                ResponseHttp::statusMessage(503, "No se ha podido crear el ponente, puede que ya exista");
                exit();
            }
            ResponseHttp::statusMessage(201, "Ponente creado");
        }else{
            ResponseHttp::statusMessage(503, "Faltan datos");
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