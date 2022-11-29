<?php

class conexion
{ //la clase es la que se conecta a la base de datos
    private $servidor = "localhost";
    private $usuario = "root";
    private $contraseña = "";
    private $conexion;

    public function __construct()
    { //cuando se instancia la clase (new conexion();) el constructor realiza la conexion

        try {
            $this->conexion = new PDO("mysql:host=$this->servidor;dbname=album", $this->usuario, $this->contraseña);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            echo "Falla en conexión" . $error;
        }
    }


    public function ejecutar($sql)
    { //El metodo "ejecutar es donde van las instrucciones
//insertar/delete/actualizar
        $this->conexion->exec($sql);
        return $this->conexion->lastInsertId(); //Regresa un ID de insercion de los cambios que hagamos
    }

    public function consultar($sql)
    {
        $sentencia = $this->conexion->prepare($sql); //se realiza la conexio con db, prepare($sql) almacena los datos en variable $Sentencia
        $sentencia->execute();
        return $sentencia->fetchAll(); //Retorna todos los registros leidos
    }

}

?>