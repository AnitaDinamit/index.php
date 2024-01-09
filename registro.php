<h1>Registro</h1>
<?php
session_start(); //Si ya hay un usuario iniciado redirigir portal.php 
if (isset($_SESSION['usuario'])) {
    header('Location: portal.php');
}
    include 'funciones.php'; //inclumos las funciones para que se puedan mostrar los mensajes que se requieren

    if(isset($_GET['error']) && $_GET['error'] === 'bd'){//Si no se ha podido procesar la consulta
        //  que se le ha hecho a la bbd se indica:
        echo "<p>Error en la base de datos. Intentelo más tarde</p>";
    }

    if(isset($_GET['error']) && $_GET['error'] === 'vacio'){ //Si hay falta un campo o campos imcompletos, en algún formulario
        // se lanzará este mensaje de error:
        echo "<p>Hay campos vacios</p>";
    }

    if(isset($_GET['error']) && $_GET['error'] === 'contrasena'){//Si en el formulario los campos "contrasena" y "contrasenarep"
        // no son inguales se muestra:
        echo "<p>Las contraseñas deben coincidir</p>";
    }

    if(isset($_GET['registro']) && $_GET['registro'] === 'success'){//Si el usuario completa el formulario, para crear un usauario,
        // correctamente se muestra:
        echo "<p>¡Acabas de crear tu usuario! Clica en Volver y accede a tu cuenta.</p>";
    }


//Poner la lógica de los datos en html
//que el dato sea obligatorio que sea de máximo 9 numeros el tlf etc
?>
<form action="" method="POST">
    <p>Nombre</p>
    <input type="text" name="nombre" required="required"><!-- nombre es un campo obligario --> 
    <p>Apellido</p>
    <input type="text" name="apellido" required="required"><!-- apellido es un campo obligario --> 
    <p>Email</p>
    <input type="email" name="email" required="required"><!-- email es un campo obligario --> 
    <p>Teléfono</p>
    <input type="number" name="telefono" min="9" required="required"><!-- teléfono es un campo obligario que tiene que tener mínimos 9 números --> 
    <p>Contraseña</p>
    <input type="password" name="contrasena" min="3" required="required"><!-- contrasena es un campo obligario, mínimo 3 caracteres --> 
    <p>Repetir contraseña</p>
    <input type="password" name="contrasenarep" min="3" required="required"> <!-- contrasena es un campo obligario, mínimo 3 caracteres --> 
<button type="submit" name="registro">Registrar</button> <!--botón submit para guardar la información introducida y ser recibida por la función
resgistro, que se encuentra en el archivo funciones.php -->
</form>

<a href="index.php">Volver</a>
<!-- Una vez hecho el registro volver al portal para iniciar sesión --> 




