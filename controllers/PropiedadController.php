<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController
{
    public static function index(Router $router)
    {
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();

        //Muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;
        
        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router)
    {
        $propiedad = new Propiedad();
        $vendedores = Vendedor::all();
        //Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();

        //Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {

            //Crea una nueva instancia
            $propiedad = new Propiedad($_POST['propiedad']);

            //**Subida de archivos*//
            //Generar un nombre único a la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            //Setear la imagen
            //Realiza un resize a la imagen con intervention
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $imagen = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }


            //Validar
            $errores = $propiedad->validar();

            //Revisar que el array de errores este vacio

            if (empty($errores)) {
                //Crea la carpeta para subir imagenes
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }


                //Guardar en la base de datos
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);
                $resultado = $propiedad->guardar();
            }
        }


        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router)
    {
        // Validar id valido
        $id = validarORedireccionar('/admin');

        //Consultar propiedad
        $propiedad = Propiedad::find($id);

        //Consultar vendedores
        $vendedores = Vendedor::all();

        //Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();



        //Ejecutar el código después de que el usuario envia el formulario
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {

            //Asignar los atributos
            $args = $_POST['propiedad'];

            $propiedad->sincronizar($args);

            //Validación
            $errores = $propiedad->validar();

            //Generar un nombre único a la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            //Subida de archivos

            if ($_FILES['propiedad']['tmp_name']['imagen']) {

                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }
            //Validación
            $errores = $propiedad->validar();

            //Revisar que el array de errores este vacio
            if (empty($errores)) {
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    //Almacenar la imagen
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
            }
            // echo $query;
        }


        $router->render(
            '/propiedades/actualizar',
            [
                'propiedad' => $propiedad,
                'vendedores' => $vendedores,
                'errores' => $errores
            ]
        );
    }

    public static function eliminar(Router $router){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $tipo = $_POST['tipo'];
          
            if (validarTipoContenido($tipo)) {
                $propiedad = Propiedad::find($id);
                $resultado = $propiedad->eliminar();

              }
            }
          
    }
}
