<?php
namespace models;
class Ponente{
    private null|int $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $imagen;
    private string $tags;
    private string $redes;

    public function __construct(int|null $id=null, string $nombre="", string $apellidos="", string $email="", string $imagen="", string $tags="", string $redes="")
    {
        $this->id=$id;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
        $this->email=$email;
        $this->imagen=$imagen;
        $this->tags=$tags;
        $this->redes=$redes;
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

    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }

    public function getRedes(): string
    {
        return $this->redes;
    }

    public function setRedes(string $redes): void
    {
        $this->redes = $redes;
    }




}
