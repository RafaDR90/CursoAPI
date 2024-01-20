<?php
namespace repositoris;
use lib\BaseDeDatos;
use models\Ponente;

class PonenteRepository
{
    private $db;
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }

    public function creaPonente(Ponente $ponente)
    {
        try{
            $error=true;
            $insert=$this->db->prepara("INSERT INTO ponentes (nombre, apellidos, email, imagen, tags, redes) VALUES (:nombre, :apellidos, :email, :imagen, :tags, :redes)");
            $insert->bindValue(':nombre',$ponente->getNombre());
            $insert->bindValue(':apellidos',$ponente->getApellidos());
            $insert->bindValue(':email',$ponente->getEmail());
            $insert->bindValue(':imagen',$ponente->getImagen());
            $insert->bindValue(':tags',$ponente->getTags());
            $insert->bindValue(':redes',$ponente->getRedes());
            $insert->execute();
        }catch (PDOException $e){
            $error=false;
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }

}