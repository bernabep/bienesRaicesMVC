<?php

namespace Controllers;

use MVC\Router;
use Model\Vendedor;

class VendedorController
{
    public static function index(Router $router)
    {
        $vendedor = new Vendedor();
        $errores = $vendedor->getErrores();

        $router->render('vendedores/crear', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function crear(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vendedor = new Vendedor($_POST['vendedor']);
            $errores = $vendedor->validar();
            if (empty($errores)) {
                $vendedor->guardar();
            }

            $router->render('vendedores/crear', [
                'errores' => $errores,
                'vendedor' => $vendedor
            ]);
        }
    }

    public static function actualizar(Router $router)
    {
        // Validar id valido
        $id = validarORedireccionar('/admin');
        $vendedor = Vendedor::find($id);
        $errores = $vendedor->getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['vendedor'];
            $vendedor->sincronizar($args);
            $errores = $vendedor->validar();
            if (empty($errores)) {
                $resultado = $vendedor->guardar();
            }
        }

        $router->render('/vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function eliminar(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $tipo = $_POST['tipo'];

            if (validarTipoContenido($tipo)) {
                $vendedor = Vendedor::find($id);
                $resultado = $vendedor->eliminar();
            }
        }
    }
}
