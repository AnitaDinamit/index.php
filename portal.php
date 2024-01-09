<?php
//Mantener la sesión del usuario iniciada
session_start();

include 'funciones.php';

if (!isset($_SESSION['usuario'])) {//Si el usuario no ha iniciado sesión enviarlo al index para 
    header('Location: index.php');//que acceda a su cuenta
} else {
    contadorActividades($conexion); //Se inicia la cookie, para contar las veces número de registros en la tabla "usuario_actividad"
}
?>

<h1>Tu cuenta</h1>

<div>
    <h2>¡Hola <?php echo $_SESSION['usuario']; ?>!</h2>
    <a href="opiniones.php">Opiniones</a> <!-- link redirige a la pagina de opniones sobre las actividades --> 
</div>

<form action="" method="POST"><!-- formulario para deslogearse --> 
    <button type="submit" name="logout">Salir de tu usuario</button>
</form>

<?php //condicionales para manejar los errores
if (isset($_GET['error']) && $_GET['error'] === 'vacio') {
    echo "<p>Hay campos vacios</p>";
}
if (isset($_GET['error']) && $_GET['error'] === 'db') {
    echo "<p>Error en la base de datos</p>";
}
if (isset($_GET['baja']) && $_GET['baja'] === 'true') {//El usuario se ha dado de baja en una actividad 
    echo "<p>¡Ya no participas en esa actividad!</p>";
}
if (isset($_GET['registro']) && $_GET['registro'] === 'true') {//El usuario se ha apuntado a una actividad
    echo "<p>¡Te has apuntado a la actividad correctamente!</p>";
}
?>

<style>
    .contenido{
        display: flex;
        flex-direction: row;
        gap: 1rem;
        align-items: center;
        justify-content: space-evenly;
    }

    .acciones{
        margin-top: 10px;
        margin-left: 10px;
    }

    .acciones button{
        margin:0px 5px 0px 5px;
    }
</style>
<div class="contenido">
    <div>
        <h3>¡Apúntate a una actividad!</h3>
        <form action="" method="POST" style="border: 1px solid #ccc; padding: 10px;">
            <p>Actividad:</p><!--  Formulario para apuntarse a una actividad --> 
            <select name="actividad">
                <option value="" selected disabled>-Selecciona una Actividad-</option><!-- Seleccionar con option la actividad a la que el usuario se quiere apuntar --> 
                <?php
                $actividades = mostrarActividades($conexion);//llamamos a la función mostrarActividades
                foreach ($actividades as $actividad) {//Se muestran todas la actividades a las que el usuario se puede apuntar
                    echo '<option value="' . $actividad["idActividad"] . '" >' . $actividad["nombre"] . ' (' . $actividad["lugar"] . ')</option>';
                }
                ?>
            </select>
            <p>Fecha:</p>
            <input type="date" name="fecha" required="required">
            <button type="submit" name="añadiractividad">¡Me apunto!</button><!-- Botón para enviar los datos --> 
        </form>
    </div>

    <div>
        <h3>Mis Actividades</h3> <!-- Por medio de las cookies se muestra cuantas actividades se ha apuntado el usuario --> 
        <p>Te has apuntado a <?= $_COOKIE['contador']; ?> actividades.</p>
        <table>
            <tr>
                <td>Actividad</td>
                <td>Fecha</td>
                <td>¿Qué quieres hacer?</td>
            </tr>
            <?php //se muestran las actividades donde se ha apuntado el usuario
            $misActividades = mostrarActividadesUsuario($conexion);
            foreach ($misActividades as $miactividad) {//Se muestran por medio de un foreach, con la opción para darse de baja de ellas o cammbiar la fecha
                echo '
            <tr>
            <td>' . $miactividad['nombre_actividad'] . '</td>
            <td>' . $miactividad['fecha'] . '</td>
            <td> 
            <form class="acciones" action="" method="POST">
            <button type="submit" name="baja">Darme de baja</button> 
            <button type="submit" name="actualizarFecha">Actualizar Fecha</button>
            <input type="hidden" name="idUsuarioActividad" value="' . $miactividad['idUsuarioActividad'] . '">
            </form>
            </td>
            </tr>
            ';
            }
            ?>
        </table>
    </div>
</div>

<!-- <form action="" method="POST">
    <button type="submit" name="baja">Dar de baja</button>
    <button type="submit" name="actualizarFecha">Actualizar Fecha</button>
    <input type="hidden" name="idUsuarioActividad" value="">
</form> -->