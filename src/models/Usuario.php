<?php
namespace models;
use utils\ValidationUtils;
class Usuario{
    private null|int $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;
    private bool $confirmado;
    private string $token;
    private string $token_exp;
    public function __construct(int|null $id=null, string $nombre="", string $apellidos="", string $email="", string $password="", string $rol='user', bool $confirmado=false, string $token="",string $token_exp="")
    {
        $this->id=$id;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
        $this->email=$email;
        $this->password=$password;
        $this->rol=$rol;
        $this->confirmado=$confirmado;
        $this->token=$token;
        $this->token_exp=$token_exp;
    }

    public static function fromArray($array){
        $usuarios=[];
        foreach ($array as $usuario){
            $usuarios[]=new Usuario(
                $usuario['id']??null,
                $usuario['nombre']??"",
                $usuario['apellidos']??"",
                $usuario['email']??"",
                $usuario['password']??"",
                $usuario['rol']??"user",
                $usuario['confirmado']??false,
                $usuario['token']??"",
                $usuario['token_exp']??"");
        }
        return $usuarios;
    }

    public function validaEmailYPassword(): bool|string{
        if(!ValidationUtils::noEstaVacio($this->getEmail())){
            return "El email no puede estar vacio";
        }
        if (!ValidationUtils::TextoNoEsMayorQue($this->getEmail(), 100)){
            return "El email no puede tener mas de 100 caracteres";
        }
        if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)){
            return "El email no es valido";
        }
        if (!ValidationUtils::validarContrasena($this->getPassword())){
            return "La contraseña no es valida";
        }
        return true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public function isConfirmado(): bool
    {
        return $this->confirmado;
    }

    public function setConfirmado(bool $confirmado): void
    {
        $this->confirmado = $confirmado;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getTokenExp(): string
    {
        return $this->token_exp;
    }

    public function setTokenExp(string $token_exp): void
    {
        $this->token_exp = $token_exp;
    }


}