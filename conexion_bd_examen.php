<?php
    /*Conexión entre el servidor ya la base de datos
    
    Necesitaremos las variablles:   */
    $host = 'localhost:3306'; //nombre del servidor al que nos queremos conectar, aquí he incluido el puerto.
    $nombrebd = 'examen'; //nombre de la bbdd a la que nos vamos a conectar
    $nombredeusuario = 'root'; //nombre del usuario para acceder al servidor
    $contrasena = ''; //Indicar si este usuario tiene contraseña o no.


    /* Voy a utilizar PDO ya que si necesito cambiar de sistema gestor de base de datos 
    bastará con indicar en los parámetros de conexión la base de datos que vayamos a utilizar. */
    try {
        //Se crea un nuevo objeto PDO para establecer la conexión
        $conexion = new PDO("mysql:host=$host;dbname=$nombrebd", $nombredeusuario, $contrasena);
        //Para lanzar las excepciones para el manejo de errores usaremos:
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //En el caso de la conexión haya sido un éxito:
        echo 'Conectado al servidor<br>';
    } catch (PDOException $e) {
        //Si se produce algún error durante la conexión, se captura la excepción PDOException 
        //y se imprime un mensaje de error que incluye el detalle del error ($e->getMessage()).
        echo "Error de conexión: " . $e->getMessage(); 
    }
    ?>