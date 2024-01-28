<?php
namespace utils;
use Models\Usuario;
use PHPMailer\PHPMailer\PHPMailer;
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

    /**
     * Envía un correo con el html indicado
     * @param $htmlContent string html con el contenido del correo
     * @return string[] Mensaje de éxito o error
     */
    public static function enviarCorreoConfirmacion(string $htmlContent, string $email): array {
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rafapruebasdaw@gmail.com';
            $mail->Password = 'qvhl kmae gxgc vyik';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitentes y destinatarios
            $mail->setFrom('rafapruebasdaw@gmail.com', 'CursoApi');
            $mail->addAddress($email);


            $mail->isHTML(true);
            $mail->Subject = 'Confirmacion de cuenta';
            $mail->Body = $htmlContent;

            // Envío del correo
            $mail->send();

            // Mensaje de éxito
            return ['tipo' => 'exito', 'mensaje' => 'Revise su email para confirmar su cuenta'];

        } catch (Exception $e) {
            // Mensaje de error
            return ['tipo' => 'error', 'mensaje' => "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}"];
        }
    }

    public static function creaHtmlContentConfirmacion(Usuario $usuario): string {
        $htmlContent =
            '<html><body>
        <h1>Confirmación de cuenta</h1>
        <p>Para confirmar su cuenta pulse en el siguiente enlace: </p>
        <a href="http://localhost/CursoAPI/confirmar-cuenta/'.$usuario->getToken().'/'.$usuario->getEmail().'">Confirmar cuenta</a>
        </body></html>';
        return $htmlContent;
    }

}
