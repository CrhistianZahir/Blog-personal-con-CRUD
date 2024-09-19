<?php include("template/cabecera.php"); ?>

<?php 
include("administrador/config/bd.php");
$sentenciaSQL = $conexion->prepare("SELECT * FROM publi");
$sentenciaSQL->execute();
$listaPublicaciones = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
<?php foreach ($listaPublicaciones as $publicacion) { ?>
    <div class="col-md-4">
        <div class="card">
            <!-- Mostrar la imagen del archivo -->
            <img class="card-img-top" src="/sitio_Web/img/<?php echo $publicacion['archivo']; ?>" alt="Imagen">
            <div class="card-body">
                <h4 class="card-title"><?php echo $publicacion['nombre']; ?></h4>
                <!-- Enlace al documento PDF -->
                <a name="" id="" class="btn btn-primary" href="/sitio_Web/doc/<?php echo $publicacion['documento']; ?>" role="button">Ver más</a>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<?php include("template/pie.php"); ?>
