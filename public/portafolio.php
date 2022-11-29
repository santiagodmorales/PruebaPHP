<?php include("cabecera.php"); ?>
<?php include("conexion.php"); ?>
<?php
if ($_POST) {
    print_r($_POST); //Permite ver la informacion que enviamos por formulario en el array

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    //$imagen=$_FILES['archivo']['name']; //Se agrega fecha al nobre de img para que no se reemplacen, new DateTime y luego cocatenacion con getTimestamp()
    $fecha = new DateTime();
    $imagen = $fecha->getTimestamp() . "_" . $_FILES['archivo']['name']; //Los archivos llegan por $_FILES, se va hasta el nombre, que es "archivo" en forms y luego se almcena el nombre en $imagen

    $imagen_temporal = $_FILES['archivo']['tmp_name'];
    move_uploaded_file($imagen_temporal, "imagenes/" . $imagen); //Mueve la imagen temp. //Para guardar en carpeta seria ej: ($im_tem..,"imagenes/".$imagen)

    $Conexion = new conexion();
    //$sql="INSERT INTO `proyectos` (`id`, `nombre`, `imagen`, `descripcion`) VALUES (NULL, 'Proyecto1', 'img.jpg', 'Prueba');";
    //se reemplazan los "Values" con las variables correspondientes para qie nos almacene bien los datos en tabla:
    $sql = "INSERT INTO `proyectos` (`id`, `nombre`, `imagen`, `descripcion`) VALUES (NULL, '$nombre', '$imagen', '$descripcion');";
    $Conexion->ejecutar($sql); //Accedemos al metodo de "ejecutar" de conexion y le pasamos un string ($sql"";) que lo vamos a recupersr de db

    header("location:portafolio.php");
}

if ($_GET) {
    //"DELETE FROM proyectos WHERE `proyectos`.`id` = 1"  //Se saca de "Borrar en db
    $Conexion = new conexion();

    $imagen = $Conexion->consultar("SELECT imagen FROM `proyectos`WHERE `proyectos`.`id` =" . $_GET['borrar']);
    //print_r($imagen); //con esto podemos ver la ubicacion de la imagen a borrar (Array[0]['imagen']) para indicar en unlink
    //print_r($imagen[0]['imagen']); //corroboramos que la ubicacion esa esa
    unlink("imagenes/" . $imagen[0]['imagen']); //Esto es para que borrar el registro tambien se elimine la imagen asociada

    $sql = "DELETE FROM proyectos WHERE `proyectos`.`id` =" . $_GET['borrar']; //borrar indicado en btn, asociado al id.
    $Conexion->ejecutar($sql); //Al igual que en $_POST ya que es para isertar/borrar/actualizar

    header("location:portafolio.php");
}

$Conexion = new conexion();
$resultado = $Conexion->consultar("SELECT * FROM `proyectos`");
//print_r($resultado);  //verifica que se traigan los datos de db en un array para conectarlos a la tabla del front

?>
<br>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Datos del proyecto
                </div>
                <div class="card-body">
                    <form action="portafolio.php" method="post" enctype="multipart/form-data">
                        <!--enctype permite recepcionar archivos (file)-->
                        Nombre del proyecto: <input required class="form-control" type="text" name="nombre">
                        <!--required pide que se envie algo si o si-->
                        <br>
                        Imagen del proyecto: <input required class="form-control" type="file" name="archivo">
                        <br>
                        Descripción:
                        <textarea required class="form-control" name="descripcion" id="" rows="3"></textarea>

                        <input class="btn btn-success" type="submit" value="Enviar proyecto">
                    </form>

                </div>

            </div>
        </div>
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Acciones</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $proyecto) { ?>
                    <tr>
                        <td>
                            <?php echo $proyecto['id']; ?>
                        </td>
                        <td>
                            <?php echo $proyecto['nombre']; ?>
                        </td>
                        <td>
                            <img width="100" src="imagenes/<?php echo $proyecto['imagen']; ?>" alt="">
                        </td>
                        <td>
                            <?php echo $proyecto['descripcion']; ?>
                        </td>
                        <td> <a name="" id="" class="btn btn-danger" href="?borrar=<?php echo $proyecto['id']; ?>"
                                role="button">Eliminar</a> </td>

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




























<?php include("pie.php"); ?>