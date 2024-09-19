<?php include('C:/xampp/htdocs/sitio_Web/administrador/template/cabecera.php');?>

<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtArchivo=(isset($_FILES['txtArchivo']['name']))?$_FILES['txtArchivo']['name']:"";
$txtDocumento=(isset($_FILES['txtDocumento']['name']))?$_FILES['txtDocumento']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
/*
echo $txtID."<br/>";
echo $txtNombre."<br/>";
echo $txtArchivo."<br/>";
echo $accion."<br/>";
*/
//establecemos la conexión a la base de datos
include('C:/xampp/htdocs/sitio_Web/administrador/config/bd.php');

switch ($accion) {
    case 'Agregar':
        //INSERT INTO `publicaciones` (`id`, `nombre`, `archivo`) VALUES (NULL, 'Articulo dos', 'uno.jpg');
        $sentenciaSQL=$conexion->prepare("INSERT INTO publi (nombre, archivo, documento) VALUES (:nombre,:archivo,:documento);");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        
        $fecha= new DateTime();
        $nombreArchivo=($txtArchivo!="")?$fecha->getTimestamp()."_".$_FILES["txtArchivo"]["name"]:"archivo.jpg";

        $tmpArchivo=$_FILES["txtArchivo"]["tmp_name"];

        if ($tmpArchivo!="") {
            move_uploaded_file($tmpArchivo,"C:/xampp/htdocs/sitio_Web/img/".$nombreArchivo);
        }


        //lo que agregue nuevo respecto al documento
        $nombreDocumento=($txtDocumento!="")?$fecha->getTimestamp()."_".$_FILES["txtDocumento"]["name"]:"documento.pdf";

        $tmpDocumento=$_FILES["txtDocumento"]["tmp_name"];

        if ($tmpDocumento!="") {
            move_uploaded_file($tmpDocumento,"C:/xampp/htdocs/sitio_Web/doc/".$nombreDocumento);
        }

        $sentenciaSQL->bindParam(':archivo',$nombreArchivo);
        $sentenciaSQL->bindParam(':documento',$nombreDocumento);
        $sentenciaSQL->execute();
        //echo "Presionado boton agregar";
        header("Location:productos.php");
        break;
    case 'Modificar':
        $sentenciaSQL=$conexion->prepare("UPDATE publi SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        
        if($txtArchivo!=""){
            $fecha= new DateTime();
            $nombreArchivo=($txtArchivo!="")?$fecha->getTimestamp()."_".$_FILES["txtArchivo"]["name"]:"archivo.jpg";

            $tmpArchivo=$_FILES["txtArchivo"]["tmp_name"];
            move_uploaded_file($tmpArchivo,"C:/xampp/htdocs/sitio_Web/img/".$nombreArchivo);

            $sentenciaSQL=$conexion->prepare("UPDATE publi SET archivo=:archivo WHERE id=:id");
            $sentenciaSQL->bindParam(':archivo',$txtArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            
            if (isset($publicaciones["archivo"]) &&($publicaciones["archivo"]!="archivo.jpg")) {
            
                if (file_exists("C:/xampp/htdocs/sitio_Web/img/".$publicaciones["archivo"])) {
                    unlink("C:/xampp/htdocs/sitio_Web/img/".$publicaciones["archivo"]);
                }
            }
            $sentenciaSQL=$conexion->prepare("UPDATE publi SET nombre=:nombre WHERE id=:id");
            $sentenciaSQL->bindParam(':archivo',$nombreArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
        }
        //echo "Presionado boton modificar";

        if($txtDocumento!=""){
            $fecha= new DateTime();
            $nombreDocumento=($txtDocumento!="")?$fecha->getTimestamp()."_".$_FILES["txtDocumento"]["name"]:"documento.pdf";

            $tmpDocumento=$_FILES["txtDocumento"]["tmp_name"];
            move_uploaded_file($tmpDocumento,"C:/xampp/htdocs/sitio_Web/doc/".$nombreDocumento);

            $sentenciaSQL=$conexion->prepare("UPDATE publi SET documento=:documento WHERE id=:id");
            $sentenciaSQL->bindParam(':documento',$txtDocumento);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            
            if (isset($publicaciones["documento"]) &&($publicaciones["documento"]!="documento.pdf")) {
            
                if (file_exists("C:/xampp/htdocs/sitio_Web/doc/".$publicaciones["documento"])) {
                    unlink("C:/xampp/htdocs/sitio_Web/doc/".$publicaciones["documento"]);
                }
            }
            $sentenciaSQL=$conexion->prepare("UPDATE publi SET nombre=:nombre WHERE id=:id");
            $sentenciaSQL->bindParam(':documento',$nombreDocumento);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
        }
        header("Location:productos.php");
        break;
    case 'Cancelar':
        header("Location:productos.php");
        break;
    case 'Seleccionar':
        $sentenciaSQL=$conexion->prepare("SELECT * FROM publi WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $publicaciones=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre=$publicaciones['nombre'];
        $txtArchivo=$publicaciones['archivo'];
        $txtDocumento=$publicaciones['documento'];
        //echo "Presionado boton seleccionar";
        break;
    case 'Borrar':
        $sentenciaSQL=$conexion->prepare("SELECT archivo FROM publi WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $publicaciones=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if (isset($publicaciones["archivo"]) &&($publicaciones["archivo"]!="archivo.jpg")) {
            
            if (file_exists("C:/xampp/htdocs/sitio_Web/img/".$publicaciones["archivo"])) {
                unlink("C:/xampp/htdocs/sitio_Web/img/".$publicaciones["archivo"]);
            }
        }
        $sentenciaSQL=$conexion->prepare("DELETE FROM publi WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        //echo "Presionado boton borrar";
        header("Location:productos.php");
        break;
}

$sentenciaSQL=$conexion->prepare("SELECT * FROM publi");
$sentenciaSQL->execute();
$listaPublicaciones=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-5">


    <div class="card">
        <div class="card-header">
            Datos de las publicaciones
        </div>
        <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

<div class = "form-group">
 <label for="txtID">ID:</label>
 <input type="text" Required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID"  placeholder="ID">
</div>

<div class = "form-group">
 <label for="txtNombre">Nombre:</label>
 <input type="text" Required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre"  placeholder="nombre">
</div>

<div class = "form-group">
 <label for="txtNombre">Archivo:</label>

  <br/>
  <?php if ($txtArchivo!="") { ?>

    <img class="img-thumbnail rounded" src="/sitio_Web/img/<?php echo $txtArchivo['archivo']; ?>" width="50" alt="" srcset="">

  <?php } ?>

 <input type="file" class="form-control" name="txtArchivo" id="txtArchivo"  placeholder="archivo">
</div>


<div class = "form-group">
 <label for="txtNombre">Documento:</label>
 <br/>
  <?php if ($txtDocumento!="") { ?>

    <a href="/sitio_Web/doc/<?php echo $txtDocumento['documento']; ?>" target="_blank">Ver documento PDF</a>

  <?php } ?>
 <input type="file" class="form-control" name="txtDocumento" id="txtDocumento"  placeholder="documento">
</div>


  <div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
  </div>
    
</form>
        </div>
        
    </div>

   
    
    
</div>

<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Archivo</th>
                <th>Documento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaPublicaciones as $publicaciones) {?>
            <tr>
                <td><?php echo $publicaciones['id']; ?></td>
                <td><?php echo $publicaciones['nombre']; ?></td>
                <td>
                    <img src="/sitio_Web/img/<?php echo $publicaciones['archivo']; ?>" width="50" alt="" srcset="">
                    
                </td>
                <td>
                <a href="/sitio_Web/doc/<?php echo $publicaciones['documento']; ?>" target="_blank">Ver documento PDF</a>

                    
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $publicaciones['id']; ?>"/>
                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('C:/xampp/htdocs/sitio_Web/administrador/template/pie.php');?>