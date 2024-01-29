<?php
namespace repositoris;

use lib\BaseDeDatos;
use models\Usuario,
    PDO,
    PDOException;

class UsuarioRepository
{
    private $db;
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }

    public function emailDisponible($email):bool
    {
        $resultado=false;
        try{
            $consultaEmailExistente=$this->db->prepara("SELECT COUNT(*) FROM usuarios WHERE email=:email");
            $consultaEmailExistente->bindValue(':email',$email);
            $consultaEmailExistente->execute();
            $cantidadEmails=$consultaEmailExistente->fetchColumn();
            $consultaEmailExistente->closeCursor();
            if ($cantidadEmails==0){
                $resultado=true;
            }
        }catch (PDOException $e){
            $resultado=false;
        }finally{
            $consultaEmailExistente->closeCursor();
            if (!$resultado){
                $this->db->cierraConexion();
            }
            return $resultado;
        }
    }

    public function borrarUsuarioPorToken($token):void
    {
        try {
            $delete = $this->db->prepara("DELETE FROM usuarios WHERE token=:token");
            $delete->bindValue(':token', $token);
            $delete->execute();
        } catch (PDOException $e) {
        } finally {
            $delete->closeCursor();
            $this->db->cierraConexion();
        }
    }

    public function insertaUsuario(Usuario $usuario)
    {
        try {
            $insert = $this->db->prepara("INSERT INTO usuarios (nombre, apellidos, email, password, rol, confirmado, token, token_exp) VALUES (:nombre, :apellidos, :email, :password, :rol, :confirmado, :token, :token_exp)");
            $insert->bindValue(':nombre', $usuario->getNombre());
            $insert->bindValue(':apellidos', $usuario->getApellidos());
            $insert->bindValue(':email', $usuario->getEmail());
            $insert->bindValue(':password', $usuario->getPassword());
            $insert->bindValue(':rol', $usuario->getRol());
            $insert->bindValue(':confirmado', $usuario->isConfirmado());
            $insert->bindValue(':token', $usuario->getToken());
            $insert->bindValue(':token_exp', $usuario->getTokenExp());
            $insert->execute();
            $resultado=true;
        } catch (PDOException $e) {
            $resultado=false;
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $resultado;
        }
    }

    public function confirmaUsuario($token)
    {
        try {
            $update = $this->db->prepara("UPDATE usuarios SET confirmado=1 WHERE token=:token");
            $update->bindValue(':token', $token);
            $update->execute();
            $resultado=true;
            if ($update->rowCount()==0){
                $resultado=false;
            }
        } catch (PDOException $e) {
            $resultado=false;
        } finally {
            $update->closeCursor();
            $this->db->cierraConexion();
            return $resultado;
        }
    }

    public function getUsuarioPorEmail($email)
    {
        try {
            $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
            $select->bindValue(':email', $email);
            $select->execute();
            $resultado=$select->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $resultado=false;
        } finally {
            $select->closeCursor();
            if (!$resultado){
                $this->db->cierraConexion();
            }
            return $resultado;
        }
    }

    public function actualizaToken($userId,$token,$token_exp,$tokenId)
    {
        try {
            $update = $this->db->prepara("UPDATE usuarios SET token=:token, token_exp=:token_exp, token_id=:token_id WHERE id=:id");
            $update->bindValue(':token', $token);
            $update->bindValue(':token_exp', $token_exp);
            $update->bindValue(':token_id', $tokenId);
            $update->bindValue(':id', $userId);
            $update->execute();
            $resultado=true;
        } catch (PDOException $e) {
            $resultado=false;
        } finally {
            $update->closeCursor();
            $this->db->cierraConexion();
            return $resultado;
        }
    }


}