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

}