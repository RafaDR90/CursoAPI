<?php
namespace services;
use models\Ponente;
use repositoris\PonenteRepository;
class PonenteService
{
    private PonenteRepository $ponenteRepository;
    public function __construct()
    {
        $this->ponenteRepository = new PonenteRepository();
    }
    public function creaPonente(Ponente $ponente)
    {
        return $this->ponenteRepository->creaPonente($ponente);
    }

    public function editaPonente(Ponente $ponente){
        return $this->ponenteRepository->editaPonente($ponente);
    }

    public function borraPonente(Ponente $ponente){
        return $this->ponenteRepository->borraPonente($ponente);
    }

    public function listaPonentesAll()
    {
        return $this->ponenteRepository->listaPonentesAll();
    }
}