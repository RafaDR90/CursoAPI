<?php
namespace utils;
class Utils{
    public static function deleteSession($nombreSession){
        if (isset($_SESSION[$nombreSession])){
            $_SESSION[$nombreSession]=null;
            unset($_SESSION[$nombreSession]);
        }
    }

    /**
     * Comprueba si el usuario está logueado
     * @return bool
     */
    public static function isLogued()
    {
        if (session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
        if (isset($_SESSION['usuario'])){
            return true;
        }else{
            return false;
        }
    }
    public static function isAdmin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol']=='admin'){
            return true;
        }else{
            return false;
        }
    }
}
