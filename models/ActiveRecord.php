<?php
namespace Model;

class ActiveRecord {
    // Base de Datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Errores
    protected static $errores = [];


    public static function getDB($database)
    {
        self::$db = $database;
    }


    public function guardar()
    {
        // debuguear($this);
        
        if (!is_null($this->id)) {
            //Actualizando
            
            $this->actualizar();
        } else {
            
            $this->crear();
        }
    }

    public function crear()
    {

        //Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(", ", array_keys($atributos));
        $query .= ") VALUE('";
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        //Insertar en la base de datos
        $resultado = self::$db->query($query);
        if ($resultado) {

            //Se redirecciona al usuario en lugar de pasar un mensaje Ok
            header("Location: /admin?resultado=1");
          }
    }

    public function eliminar(){
        
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if ($resultado) {
            $this->borrarImagen();
            //Se redirecciona al usuario en lugar de pasar un mensaje Ok
            header("Location: /admin?resultado=3");
          }
    }

    public function actualizar()
    {
        //Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key=>$value){
            $valores[] = "{$key}='{$value}'";
        }
        

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ',$valores);
        $query .= " WHERE id = ' " . self::$db->escape_string($this->id) . "'";
        $query .= " LIMIT 1";
        $query .= ";";

        //Insertar en la base de datos
        $resultado = self::$db->query($query);
        if ($resultado) {

            //Se redirecciona al usuario en lugar de pasar un mensaje Ok
            header("Location: /admin?resultado=2");
          }
    }

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    //Subida de archivos
    public function setImagen($imagen)
    {
        
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }
        //Asignar al atributo de imagen el nombre de la imagen
        
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }



    public function borrarImagen(){
            //Comprobar si existe el archivo
            $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
            if ($existeArchivo) {
                unlink(CARPETA_IMAGENES . $this->imagen);
            }
        }


    //Validación
    public static function getErrores()
    {
        return static::$errores;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }

    //Obtiene determinado número de registros
    public static function get($cantidad){
        
            $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
            $resultado = self::consultarSQL($query);
            
            return $resultado;
        
    }

    //Busca un registro por su id
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        $resultado = array_shift($resultado);
        return $resultado;
    }

    public static function consultarSQL($query)
    {
        //Consultar la base de datos
        
        $resultado = self::$db->query($query);

        //Iterar los resultado
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        //Liberar la memoria
        $resultado->free();

        //retornar los resultados
        return $array;
    }

    public static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    //Sincroniza el objeto en momoria con los cambios realizados por el usuario
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

}