<?php
//SE mantiene la sesión iniciada
session_start();
include 'funciones.php';
if (!isset($_SESSION['usuario'])) {//Si el usuario no ha iniciado sesión vuelve a la página inicial para logearse.
    header('Location: index.php');
}

?>
<style>
    .opinion {
        background: grey;
        color: pink;
        border: 1px solid black;
        margin-top: 10px;
    }
</style>

<?php //Si la información está correcta, la opinión se guarda en la bbdd
if (isset($_GET['registro']) && $_GET['registro'] === 'success') {
    echo "<p>Opinión creada satisfactoriamente</p>";
}
if (isset($_GET['error']) && $_GET['error'] === 'bd') {
    echo "<p>Error en la base de datos</p>";
}
?>

<h1>Opiniones</h1>

<a href="portal.php">Volver</a><!-- TE permite volver al tu cuenta --> 

<div class="caja">

    <?php //Se muestran todas las opiniones de los demás usuarios
        $opiniones = mostrarOpiniones($conexion);
        foreach($opiniones as $opinion){
            echo'
            
            <div class="opinion">
            <p>'.$opinion['nombre_usuario'].'</p>
            <p>'.$opinion['mensaje'].'</p>
        </div>
            ';
        }
    ?>
</div>

<?php //En caso de que el mensaje vaya vacío se muestra:
if (isset($_GET['error']) && $_GET['error'] === 'vacio') {
    echo "<p>El mensaje no puede ir vacio</p>";
}
?>

<form action="" method="POST"> <!-- formulario para que se guarde la opinión insertada --> 
    <p>Escribe tu opinión aquí:</p>
    <textarea name="opinion" id="" cols="30" rows="10" required="required"></textarea>
    <button type="submit" name="opinar" >Enviar</button>
</form>