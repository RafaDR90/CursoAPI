<?php
namespace lib;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT,
    Firebase\JWT\Key,
    PDOException;

class Security
{
    final public static function encryptPassword(string $password):string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost'=>4]);
    }

    final public static function validatePassword(string $password, string $passwordHash):bool
    {
        return password_verify($password, $passwordHash);
    }

    final public static function claveSecreta():string{
        return $_ENV['SECRET_KEY'];
    }

    final public static function createToken(string $key, array $datos):string
    {
        $time = strtotime("now");
        $token=array(
            "iat" => $time,
            "exp" => $time + (1800),
            "data" => $datos
        );
        return JWT::encode($token, $key,'HS256');
    }

    final public static function getTokenData(){
        $headers=apache_request_headers();
        if (!isset($headers['Authorization'])){
            return ResponseHttp::statusMessage(401, "No autorizado");
        }
        try {
            $authorizationArr = explode(' ', $headers['Authorization']);
            $token = $authorizationArr[0];
            return JWT::decode($token, new Key(Security::claveSecreta(), 'HS256'));
        } catch (PDOException) {
            return ResponseHttp::statusMessage(401, "No autorizado");
        } catch (ExpiredException) {
            return ResponseHttp::statusMessage(401, "Token expirado");
        }
    }
    final public static function getTokenDataOf($token){
        try {
            return JWT::decode($token, new Key(Security::claveSecreta(), 'HS256'));
        } catch (ExpiredException) {
            return false;
        }catch (PDOException) {
            return false;
        }
    }

    public static function generarTokenId() {
        $numeroAleatorio = mt_rand(1, PHP_INT_MAX);
        $numeroAleatorio = abs($numeroAleatorio);
        $numeroAleatorio = $numeroAleatorio % 100000000000000000000; // Asegurar que esté dentro del rango bigint(20)

        return $numeroAleatorio;
    }




}