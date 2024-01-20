<?php
namespace controllers;
use lib\ResponseHttp,
    lib\BaseDeDatos,
    models\Ponente,
    services\PonenteService;

ResponseHttp::getCabeceras('POST');
class APIPonenteController{
    private PonenteService $ponenteService;
    public function __construct()
    {
        $this->ponenteService = new PonenteService();
    }

    public function creaPonente()
    {
        $data=json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->apellidos) && !empty($data->email) && !empty($data->imagen) && !empty($data->tags) && !empty($data->redes)){
            $ponente=new Ponente(null, $data->nombre, $data->apellidos, $data->email, $data->imagen, $data->tags, $data->redes);
            if ($this->ponenteService->creaPonente($ponente)){
                ResponseHttp::statusMessage(201, "Ponente creado");
            }else{
                ResponseHttp::statusMessage(503, "No se ha podido crear el ponente");
            }
        }
    }

    public function editaPonente(){
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
        $ponentes=$this->ponenteService->listaPonentesAll();
        if (isset($ponentes)){
            ResponseHttp::statusMessage(200, json_encode($ponentes));
            echo json_encode($ponentes);
        }else{
            ResponseHttp::statusMessage(503, "No se ha podido obtener la lista de ponentes");
        }

    }

}