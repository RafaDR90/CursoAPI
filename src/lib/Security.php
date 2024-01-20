<?php
namespace lib;

use Firebase\JWT\JWT,
    Firebase\JWT\Key,
    PDOException;

class Security
{
    final public static function encryptPassword(string $password):string
    {
        return password_hash($password, PASSWORD_DEFAULT);
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
            "exp" => $time + (3600),
            "data" => $datos
        );
        return JWT::encode($token, $key,'HS256');
    }

    final public static function getTokenData(){
        $headers=apache_request_headers();
        if (isset($headers['Authorization'])){
            return $response['message']=json_decode(ResponseHttp::statusMessage(403,"Acceso denegado"));
        }
        try {
            $authorizationArr = explode(' ', $headers['Authorization']);
            $token = $authorizationArr[1];
            return $decodedToken = JWT::decode($token, new Key(Security::claveSecreta(), 'HS256'));
        } catch (PDOException) {
            return $response['message']=json_decode(ResponseHttp::statusMessage(401,"Token expirado o invalido"));
        }
    }

}