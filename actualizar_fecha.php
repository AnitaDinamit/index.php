<?php
//Mantener la sesión del usuario iniciada
session_start();

include 'funciones.php';

if (!isset($_SESSION['usuario'])) {//Si el usuario no ha iniciado sesión enviarlo al index para 
    header('Location: index.php');//que acceda a su cuenta
}

if(!$data = obtenerUsuarioAct($conexion)){ ?>
    <!-- Error: Usuario Actividad no encontrado -->
    <p>Error bro</p>
    <a href='portal.php'>Volver</a>  
<?php } else { ?>
    <!-- Formulario para actualizar -->
    <h1>Cambiar Fecha</h1>

    <form action="" method="POST">
        <p>Fecha a cambiar</p>
        <input type="date" value="<?=$data['fecha']?>" name="fecha">
        <input type="hidden" value="<?=$data['idUsuarioActividad']?>" name="idUsuarioActividad">
        <button type="submit" name="cambiarfecha">Actualizar Fecha</button>
    </form>
    <a href="portal.php">Volver</a>
<?php } ?>