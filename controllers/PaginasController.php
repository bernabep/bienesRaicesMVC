<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController
{
    public static function index(Router $router)
    {
        $propiedades = Propiedad::get(3);
        $inicio = true;
        $router->render("paginas/index", [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros(Router $router)
    {
        $router->render('paginas/nosotros');
        echo "Desde nosotros";
    }

    public static function propiedades(Router $router)
    {
        $propiedades=Propiedad::all();
        $router->render('paginas/propiedades',['propiedades'=>$propiedades]);
    }

    public static function propiedad(Router $router){
        $id = validarORedireccionar('/propiedades');
    
        $propiedad = Propiedad::find($id);
        $router->render('paginas/propiedad',['propiedad'=>$propiedad]);
    }

    public static function blog(Router $router)
    {
        $router->render('paginas/blog',[]);
    }
    
    public static function entrada(Router $router)
    {
        $router->render('paginas/entrada',[]);
    }

    public static function contacto(Router $router)
    {
        $mensaje = null;
        if($_SERVER['REQUEST_METHOD']==='POST'){
            // Crear una instancia de PHPMailer
            $respuestas = $_POST['contacto'];
            
            // debuguear($contacto);
            $mail = new PHPMailer();

            //Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;             
            $mail->Username   = '6e25219db0710b';
            $mail->Password   = 'd4fab631faec9e';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 2525; 

            //Configurar el contenido del mail
            $mail->setFrom('admin@bienesraices.com');
            $mail->addAddress('bernabepando@gmail.com','BienesRaices.com');
            $mail->Subject = 'Tienes un Nuevo Mensaje';

            //Habilitar HTML
            $mail->isHTML(true); 
            $mail->CharSet = 'UTF-8';

            //Definir el contenido
            $contenido = '<html>';
            $contenido.= '<p>Tienes un nuevo mensaje</p>';
            $contenido.= '<p>Nombre: '.$respuestas['nombre'] .'</p>';
            //Enviar de forma condcional algunos campos de email o telefono
            if($respuestas['contacto']==='telefono'){
                $contenido.= '<p>Eligió ser contactado por teléfono</p>';
                $contenido.= '<p>Teléfono: '.$respuestas['telefono'] .'</p>';
                $contenido.= '<p>Fecha Contacto: '.$respuestas['nombre'] .'</p>';
                $contenido.= '<p>Hora: '.$respuestas['nombre'] .'</p>';
                
            }else{
                //Es email, entonces agregamos el campo email
                $contenido.= '<p>Eligió ser contactado por email</p>';
                $contenido.= '<p>Email: '.$respuestas['email'] .'</p>';
            }
            $contenido.= '<p>Mensaje: '.$respuestas['mensaje'] .'</p>';
            $contenido.= '<p>Vende o Compra: '.$respuestas['tipo'] .'</p>';
            $contenido.= '<p>Precio o Presupuesto: €'.$respuestas['precio'] .'</p>';
            $contenido.= '<p>Prefiere ser contactado por: '.$respuestas['contacto'] .'</p>';
            $contenido.= '</html>';
            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            //Enviar el email
            if($mail->send()) {
                $mensaje = "Mensaje enviado Correctamente";
            }else{
                $mensaje = "El mensaje no se pudo enviar...";
            }
        }
        $router->render('/paginas/contacto',[
            'mensaje'=>$mensaje
        ]);
    }
}
