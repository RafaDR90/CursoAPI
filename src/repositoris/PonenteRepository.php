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
        try {
            $exito = true;

            // Comprobar si el email ya existe
            $consultaEmailExistente = $this->db->prepara("SELECT COUNT(*) FROM ponentes WHERE email = :email");
            $consultaEmailExistente->bindValue(':email', $ponente->getEmail());
            $consultaEmailExistente->execute();
            $cantidadEmails = $consultaEmailExistente->fetchColumn();
            $consultaEmailExistente->closeCursor();

            if ($cantidadEmails > 0) {
                // El email ya existe, no se puede insertar
                $exito = false;
            } else {
                // El email no existe, se puede proceder con la inserción
                $insert = $this->db->prepara("INSERT INTO ponentes (nombre, apellidos, email, imagen, tags, redes) VALUES (:nombre, :apellidos, :email, :imagen, :tags, :redes)");
                $insert->bindValue(':nombre', $ponente->getNombre());
                $insert->bindValue(':apellidos', $ponente->getApellidos());
                $insert->bindValue(':email', $ponente->getEmail());
                $insert->bindValue(':imagen', $ponente->getImagen());
                $insert->bindValue(':tags', $ponente->getTags());
                $insert->bindValue(':redes', $ponente->getRedes());
                $insert->execute();
            }
        } catch (PDOException $e) {
            $exito = false;
        } finally {
            if (isset($insert)) {
                $insert->closeCursor();
            }
            $this->db->cierraConexion();
            return $exito;
        }
    }


    public function editaPonente(Ponente $ponente)
    {
        try{
            $exito=true;
            $insert=$this->db->prepara("UPDATE ponentes SET nombre=:nombre, apellidos=:apellidos, email=:email, imagen=:imagen, tags=:tags, redes=:redes WHERE id=:id");
            $insert->bindValue(':id',$ponente->getId());
            $insert->bindValue(':nombre',$ponente->getNombre());
            $insert->bindValue(':apellidos',$ponente->getApellidos());
            $insert->bindValue(':email',$ponente->getEmail());
            $insert->bindValue(':imagen',$ponente->getImagen());
            $insert->bindValue(':tags',$ponente->getTags());
            $insert->bindValue(':redes',$ponente->getRedes());
            $insert->execute();
            if ($insert->rowCount()==0){
                $exito=false;
            }
        }catch (PDOException $e){
            $exito=false;
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $exito;
        }
    }

    public function borraPonente(Ponente $ponente)
    {
        try{
            $exito=true;
            $insert=$this->db->prepara("DELETE FROM ponentes WHERE id=:id");
            $insert->bindValue(':id',$ponente->getId());
            $insert->execute();
            if ($insert->rowCount()==0){
                $exito=false;
            }
        }catch (PDOException $e){
            $exito=false;
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $exito;
        }
    }

    public function listaPonentesAll()
    {
        try{
            $insert=$this->db->prepara("SELECT * FROM ponentes");
            $insert->execute();
            $ponentes=$insert->fetchAll();
            if ($insert->rowCount()==0){
                $ponentes=null;
            }
        }catch (PDOException $e){
            $ponentes=null;
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $ponentes;
        }
    }

}