<?php
//llamamos a la función "session_start()" para que el usuario pueda iniciar sesión
session_start();

if (isset($_SESSION['usuario'])) { //Si los datos intrucidos corresponden a los registrados en la bbdd
    header('Location: portal.php');//el usuario es redirigido a la página portal.php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a Performance!</title> 
</head>
<body>
    <h1>¡Bienvenido a Performance club! </h1>
    <h3> Accede a tu cuenta </h3>

    <?php
    //para mostrar los posibles errores a la hora de inicar sesión
    include 'funciones.php'; //incluimos este archivo para poder conectar las funciones creadas 
    // y mostrar en esta página los mensajes de los posibles errores y accionque que realice el usuario
    if(isset($_GET['error']) && $_GET['error'] === 'bd'){
        echo "<p>Error en la base de datos</p>"; 
    }
    if(isset($_GET['error']) && $_GET['error'] === 'login'){//Si al rellena los campos del formulario de registro no
        // existen la contraseña o usuario insertado:
        echo "<p>No existe ese usuario o esa contraseña</p>";
    }
    if(isset($_GET['error']) && $_GET['error'] === 'vacio'){ //Si hay falta un campo o campos imcompletos, en algún formulario
        // se lanzará este mensaje de error:
        echo "<p>Hay campos vacios</p>";
    }

    if(isset($_GET['logout']) && $_GET['logout'] === 'true'){//Para función de deslogeo (logout), 
        // si se ha salido correctamente de su se muestra:
        echo "<p>Te has deslogeado correctamente</p>";
    }
    ?>
    <!-- Formulario para pedir al usuario que entre con sus credenciales, email y contraseña--> 
    <form action="" method="POST">
        <label for="">Email</label>
        <input type="text" placeholder="email" name="email">
        <label for="">Contraseña</label>
        <input type="password" placeholder="contraseña" name="contrasena" >
        <button type="submit" name="login">Login</button>
        <p>¿No tienes una cuenta?</p>
        <a href="registro.php">¡Regístrate!</a><!-- Link redirige al formulario de registro
        en caso de que la persona no tenga un usuario creado --> 
    </form>
    
</body>

</html>

