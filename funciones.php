                                                    <!-- INCLUDE PDO CONEXIÓN BBDD -->

<?php
if (session_status() == PHP_SESSION_NONE) session_start();

/*"include" para utilizar la conexión que se ha hecho previamente con la bbdd
en el archivo 'conexion_bd_examen.php'*/
include 'conexion_bd_examen.php';

                                                 /* POST DEL FORMULARIO DE INICIAR SESIÓN*/

//Se comprueba si el método utilizado en el formulario de inicio de sesión es POST,
//y que exista el campo 'login' que envíe todo los datos que necesitamos. 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email']; //guarda el valor del campo 'email' en la variable $email.
    $contrasena = $_POST['contrasena']; //guarda el valor del campo 'contraseña' en la variable $contrasena.
    login($conexion, $email, $contrasena);//Se llama a la función login y se le pasa los aragumentos
    //$conexion, $email, $contrasena.
}

                                                 /* FUNCIÓN LOGIN - INICIO SESIÓN */                                            

/*Esta función se va a encargar de que se hayan recibido correctamente los datos necesarios para el inicio de sesión (email y contraseña)
y utiliza la conexión que hay con la base de datos para compararlos y verificar que existe un usuario con estos datos para que te permita
iniciar sesión*/
function login($conexion, $email, $contrasena){

    //Tomamos los datos del formulario de inicio de sesión y le quitamos los espacios innecesarios
    $email = trim($email);
    $contrasena = trim($contrasena);

    //Se verifica si el email y la contraseña no están vacíos.
    if (empty($email) || empty($contrasena)) {

    //En el caso de que uno o los dos estén vacíos, 
    //se llama a la funcion header para que muestre en la página "index.php", al introducir los datos
    // un mensaje al usuario en caso de que el indicndo que el email y/o la contraseña estén vacíos.
        header('Location: index.php?error=vacio');

    } else {  
        //Si los campos de email/contraseña están completos le pedimos a la BBDD que:

        try { //verifique, en la tabla de usuario, si el campo de email y contraseña coincide con los valores introducidos por el usuario
            $consulta = "SELECT * FROM usuario WHERE email = :email AND contrasena = :contrasena";
            $stm = $conexion->prepare($consulta);//Se utiliza una consulta preparada, escrita en la línea anterios (30), para evitar la inyección
            $stm->bindParam(':email', $email, PDO::PARAM_STR);//La función "bindParam" vincula el valor que ha introducido el usuario
            // en el campo email del formulario, con la columna 'email' de la tabla "usuario" de la BBDD.
            $stm->bindParam(':contrasena', $contrasena, PDO::PARAM_STR); //Se enlaza el campo 'contrasena' de la tabala usuario
            // con el valor introducido en el campo coontraseña del usuario
            $stm->execute();
            $usuario = $stm->fetch(PDO::FETCH_ASSOC); //se crea un array asociativo que guarda la consulta preparada en la variable $usuario
            //y recupera la primera fila del conjunto de resultados.
        

            if ($usuario) {//Si se encuentra un usuario en la tabla "usuarios" 
                

                $_SESSION['usuario'] = $usuario['nombre'];//con los valores introducidor por el usuario 
                $_SESSION['id'] = $usuario['idUsuario'];// y son correctos
                contadorActividades($conexion);
                header('Location: portal.php'); //Te lleva al archivo "portal.php" para acceder a tu cuenta.
            } else {
                header('Location: index.php?error=login');//Si los datos no son correctos, te quedas en la misma página 
                //y se muestra un mensaje de error con el usuario/contraseña.
            }
        } catch (PDOException $e) {//Si no se procesa la consulta 
            // se captura el error y se muestra un mensaje de error con la bbdd
            header('Location: index.php?error=bd');
        }
    }
};
                                           /* POST PARA FORMULARIO DE REGISTRO */

//Se comprueba si el método utilizado en el formulario de inicio de sesión es POST,
//y que exista el campo 'registro' envíe la información que ha guardado.

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registro'])) {
    //Se guarda en variables, los valores introducidos, necesarios para el registro del usuario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena']; //Se introduce la contraseña
    $contrasenarep = $_POST['contrasenarep'];//Se introduce de nuevo la msima contraseña para asegurarse de que está correcta
    registro($conexion, $nombre, $apellido, $email, $telefono, $contrasena, $contrasenarep); //Se llama a la función registro 
    //con todos los aragumentos que necesita, almacenados previamente en $_POST[].

}

                                                   /* FUNCIÓN REGISTRO */

//Se crea laa función "registro" y se le pasan los argumentos que vamos a necesitar
function registro($conexion, $nombre, $apellido, $email, $telefono, $contrasena, $contrasenarep)
{ //Se utiliza trim para quitar cualquier espacio que el usuario haya podido colocar en 
  //los datos introducidos, ya que los invalidaría
    $nombre = trim($nombre);
    $apellido = trim($apellido);
    $email = trim($email);
    $telefono = trim($telefono);
    $contrasena = trim($contrasena);
    $contrasenarep = trim($contrasenarep);

    
    //El usuario se encuentre en la página de registro de un nuevo usuario, donde tiene que rellenar un formulario
    //Utilizamos la función "empty" para verificar que cada campo ha sido rellenado en el formulario de registro
    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($contrasena) || empty($contrasenarep)) {
        //Si alguno está vacío, cuando el usuario clique en el botón "Registrar", en la página de registro se le muestra un mensaje de error.
        header('Location: registro.php?error=vacio');
        //Si el valor que se coloca en el campo 'contrasena' no es igual a 'contrasenarep'
    } elseif ($contrasena != $contrasenarep) {
        //Se muestra un mensaje de error, indicando que se debe colocar la misma en los dos campos
        header('Location: registro.php?error=contrasena');
    } else {
        try {//Se realiza una consulta SQL para que los valores que ha escrito el usuario en el formulario
            //se introduzcan en la tabla usuario, para registrar a un nuevo usuario.
            $consulta = "INSERT INTO usuario (nombre, apellido, email, telefono, contrasena) VALUES
            (:nombre, :apellido , :email, :telefono, :contrasena)";
            $stm = $conexion->prepare($consulta);//Se utiliza una consulta preparada para evitar la inyección
            //Se enlazan los campos de la tabla con el valor introducido en el formulario de la variable $nombre
            $stm->bindParam(':nombre', $nombre, PDO::PARAM_STR); 
            $stm->bindParam(':apellido', $apellido, PDO::PARAM_STR); 
            $stm->bindParam(':telefono', $telefono, PDO::PARAM_INT); 
            $stm->bindParam(':contrasena', $contrasena, PDO::PARAM_STR); 
            $stm->execute(); //Se ejecuta la consulta
            header('Location: registro.php?registro=success');//Si está correcto se muestra un mensaje de éxito
        } catch (PDOException $e) {
            header('Location: registro.php?error=bd'); //Si no procesa la consulta hay un error en la BBDD
            
        }
    }
}

                                              /* POST PARA FORMULARIO DE deslogeo */

//Se comprueba si el método utilizado en el formulario de logout, para que el usuario salga de su sesión, es POST,
//y que exista el campo 'logout' de lo que ha recibido del POST (PREGUNTAR)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy(); //Se destruye, para que el usuario se pueda deslogear
    header('Location: index.php?logout=true');
    //Te enseña un mensaje indicando que te has deslogeado correctamente
}

                                                   /* FUNCIÓN MOSTRAR ACTIVIDADES */

//Creamos esta función para acceder a la bbdd y mostrar las actividades, almacenadas en la tabal actividades
function mostrarActividades($conexion)
{
    try {
        $consulta = "SELECT * FROM actividades"; //SQL para mostrar todas las filas de la tabla "actividades"
        $stm = $conexion->prepare($consulta);//consulta preparada (línea 127) para evitar la inyección
        $stm->execute();//Ejecutar la consullta
        $actividades = $stm->fetchAll(PDO::FETCH_ASSOC);//se crea un array asociativo que guarda la consulta preparada en la variable $actividades
        //y recupera todo el conjunto de resultados.
        return $actividades; //devuelve el array asociativo que se ha generado en la líena anterior mostrando todas las actividadesde de la tabla
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

                                              /* CREACIÓN FUNCIÓN MOSTRAR ACTIVIDADES USUARIO */

//Se crea esta función para mostrar las actividades a las que cada usuario se ha apuntado
function mostrarActividadesUsuario($conexion)
{
    try {//Guardamos el id de la sesión 
         
        $idUsuario = $_SESSION['id'];
        /*Consulta de SQL que muestra las actividades de cada usuario al enlazar el registro(usuario_actividad FK) 
        que se hizo de la actividad que va a relizar con la fecha que se va hacer dicha actividad y la fecha. 
        Devolverá las columnas idUsuarioActividad, fecha, y nombre_actividad para aquellas filas donde 
        el idUsuario coincida con el valor proporcionado*/ 
        $consulta = "SELECT usuario_actividad.idUsuarioActividad, usuario_actividad.fecha, actividades.nombre AS nombre_actividad
        FROM usuario_actividad
        JOIN actividades ON usuario_actividad.idActividad = actividades.idActividad
        WHERE usuario_actividad.idUsuario = :idUsuario;";
        $stm = $conexion->prepare($consulta); //Se utiliza una consulta preparada (línea 163 a 166) para evitar la inyección
        $stm->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stm->execute();//Ejecutar consulta
        $resultados = $stm->fetchAll(PDO::FETCH_ASSOC);//Guardar los resultado en un array asociativo
        return $resultados;//y muestra la actividades a las que cada usuario se ha apuntado
    } catch (PDOException $e) {
    }
}

                                             /* POST PARA FORMULARIO AÑADIR UNA ACTIVDAD */

    //Se comprueba si el método utilizado en el formulario "añadiractividad" es POST,
    //y que exista el campo 'añadiractividad' en la info que se está recibiendo de POST 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['añadiractividad'])) {
    //Se guarda  en variables los siguientes valores del formulario:
    $actividad = $_POST['actividad'];
    $fecha = $_POST['fecha'];
    //Llamamos a la función para que se ejecute, pueda añadirse la actividad y fecha seleccionadas a la tabla "usuario_actividad"
    //como un nuevo registro (fila)
    añadiractividadUsuario($conexion, $actividad, $fecha);
}

                                              /* FUNCIÓN AÑADIR ACTIVIDAD AL USUARIO */

// ESta función se va a encargar de que introducirse una actividad y una fecha se cree una nueva fila en la tabla "usuario_actividad"
// indicando que el usuario se ha registrado para asistir a otra actividad.
function añadiractividadUsuario($conexion, $actividad, $fecha)

{//Cuando seleccione la actividad que quiere hacer y la fecha, se verifica si uno o los dos están vacíos.
    if (empty($actividad) || empty($fecha)) {

        //En el caso de que fale rellenar uno o los dos se comunicará el mensaje de error.
        header('Location: portal.php?error=vacio');
    } else {
        try {
            //Usamos primero "session_start" para acceder a la sesión del usuario que ya ha sido logeado.
            //de este modo tenemos una variable llamda $idUsuario correpondiente a la primary key del usuario que ya ha sido logueado.
            //Pudiendo usarse para poder crear una actividad, la cual estará registrada para ese usuario
             
            $idUsuario = $_SESSION['id'];
            // Se hará una consulta en la que se insertará en la tabla "usuario_actividad" una nueva fila que registra que el usuario
            // se ha apuntado a una nueva actividad. Para ello el usuario seleccionará una actividad(enviando este valor al campo "usuario_actividad")
            // y la fecha de cuando lo quiere hacer (enviando este valor al campo 'fecha').
            $consulta = "INSERT INTO usuario_actividad (idUsuario, idActividad, fecha) VALUES (:idUsuario, :idActividad, :fecha)";
            $stm = $conexion->prepare($consulta);
            $stm->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stm->bindParam(':idActividad', $actividad, PDO::PARAM_INT);
            $stm->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stm->execute();
            header('Location: portal.php?registro=true');
        } catch (PDOException $e) {
            header('Location: portal.php?error=db'); //En caso de haber error al ahora de que la BBDD reciba los datos se mostrará este error.
        }
    }
}

                                                /* POST PARA FORMULARIO DARSE DE BAJA */

//Se comprueba si el método utilizado en el formulario de baja es POST,
//y que exista el campo 'baja' de lo que ha recibido del POST 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['baja'])) {
    $idUsuarioActividad=$_POST['idUsuarioActividad'];
    //Llamamos a la función y le pasamos los argumentos
    bajaActividad($conexion, $idUsuarioActividad);
}
                                            /* FUNCIÓN PARA DARSE DE BAJA ACTIVIDAD */

//Usamos primero session start para acceder a la sesión del usuario que ya ha sido logeado.
//de este modo tenemos una variable llamda $idUsuario correpondiente a la primary key del usuario que ya ha sido logueado.
//Pudiendo usarse para poder eliminar una actividad la cual estará registrada para ese usuario
function bajaActividad($conexion,$idUsuarioActividad){
    try{
           //Se mantiene la sesión iniciada del usuario que se ha logeado previamente
        $idUsuario = $_SESSION['id'];
        // Realizamos una consulta para eliminar la actividad en la que el usuario quiere darse de baja-
        // Se eliminará, de la tabla "usuario_actividad", la fila donde están registrados nombre y la fecha
        // que el usuario ha seleccionado.
        $consulta="DELETE FROM usuario_actividad
        WHERE idUsuarioActividad = :idUsuarioActividad
        AND idUsuario = :idUsuario;";
        $stm = $conexion->prepare($consulta);
        $stm->bindParam(':idUsuarioActividad', $idUsuarioActividad, PDO::PARAM_INT);
        $stm->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stm->execute();
        header('Location: portal.php?baja=true');
    }catch(PDOException $e){ //Si hubiera algún problema para que bbdd reciba la consulta se mostrará un mensaje de error.
        header('Location: portal.php?error=db');
    }
}

                                                /* CREACIÓN DE LAS COOKIES */

//COOKIES - Se crea la función contador para configurar la cookie
function contadorActividades($conexion){
    try{
         
        $idUsuario = $_SESSION['id'];
        $consulta="SELECT COUNT(*) as contador FROM usuario_actividad WHERE idUsuario = :idUsuario";
        $stm = $conexion->prepare($consulta);
        $stm->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stm->execute();
        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        
        //La cookie dura 30 dias
        // "7" hace que sea accesible la cookie en toda nuestra aplicación
        $nombre_cookie = "contador";
        $valor_cookie = $resultado['contador'];
      
        // Establece la cookie
        setcookie($nombre_cookie, $valor_cookie, time() + (86400 * 30), "/"); // 86400 = 1 día

    }catch(PDOException $e){
        header('Location: portal.php?error=db');
    }
}

                                                      /* FUNCIÓN PARA MOSTRAR OPINIONES */

//fucnción para mostrar las opiniones que todos los usuarios han dejado sobre las actividades
function mostrarOpiniones($conexion){
    try {//Se utilizan las talbas usuaraio y opiniones para tomar el usuario que ha dejado la opinón y mostrarla
        // desde cualquier usuario, para que todo el mundo lo pueda ver.
        $consulta = "SELECT usuario.nombre AS nombre_usuario, opiniones.mensaje
        FROM opiniones
        JOIN usuario ON opiniones.idUsuario = usuario.idUsuario;";
        $stm = $conexion->prepare($consulta);
        $stm->execute();
        $resultados = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;
    } catch (PDOException $e) {
    }
}

                                                     /* POST FORMULARIO OPINAR */

//Se comprueba si el método utilizado en el formulario de "opinar" es POST,
//y que exista el campo 'opinar' de lo que ha recibido del POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['opinar'])) {
    $opinion=$_POST['opinion'];//Guardar lo que ha escrito el usuario
    opinar($conexion,$opinion);//llamar a la función opinar y pasarle los argumentos
}

                                                   /* FUNCIÓN OPINAR */
//Se utilizará para que el usuario pueda escribir una opinión y quede registrada en la tabla opiniones, para ser mostradas después
function opinar($conexion, $opinion){
    $opinion = trim($opinion);//quita espacios innecesarios de la opinión que haya escrito el usuario

    if (empty($opinion)) {//Si en el campo donde se debe escribir la opinión no hay nada escrito 
        header('Location: opiniones.php?error=vacio'); //mostrar mensaje de error indicando el error
    } else {
        try {
             //mantener la sesión del usuario iniciada
            $idUsuario = $_SESSION['id'];
            // Realizamos una consulta para que la opinión que ha introducido el usuario se registre en la tabla "opiniones"
            $consulta = "INSERT INTO opiniones (idUsuario, mensaje) VALUES
            (:idUsuario, :mensaje)";//inserta la opinión de la actividad en la tabla de opiniones
            $stm = $conexion->prepare($consulta);
            $stm->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
            $stm->bindParam(':mensaje', $opinion, PDO::PARAM_STR);
            $stm->execute();
            header('Location: opiniones.php?registro=success');
        } catch (PDOException $e) {
            //Si hay algún error al enviar la consulta, se muestra un mensaje.
            header('Location: opiniones.php?error=bd');
        }
    }
}





function obtenerUsuarioAct($conexion){
    // 1. Comprobar id
    if(!isset($_GET['idusuarioactividad'])){
        return false; // vamos al form
    } 

    // 2. Consultar la bd con id
    $id = $_GET['idusuarioactividad'];
    $consulta = "SELECT * FROM usuario_actividad WHERE idUsuarioActividad = :id";
    $stm = $conexion->prepare($consulta);
    $stm->bindParam(':id', $id, PDO::PARAM_STR);
    $stm->execute();
    $resultado = $stm->fetch(PDO::FETCH_ASSOC);

    // 3. Retornar valores necesarios
    return $resultado;
}




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiarfecha'])) {
    $idUsuarioActividad=$_POST['idUsuarioActividad'];//Guardar lo que ha escrito el usuario
    $fecha=$_POST['fecha'];
    actualizarFecha($conexion,$fecha,$idUsuarioActividad);
}

function actualizarFecha($conexion,$fecha,$idUsuarioActividad){
    try{
        // Comprobar datos que no esten vacios
    
        if(empty($fecha)||empty($idUsuarioActividad)){
            header('Location: portal.php?error=bd');
        }
    
        // Actualizar fecha por id de Usuario Actividad
        $consulta = "UPDATE usuario_actividad SET fecha = :fecha WHERE idUsuarioActividad = :idUsuarioActividad;";
        $stm = $conexion->prepare($consulta);
        $stm->bindParam(':idUsuarioActividad', $idUsuarioActividad, PDO::PARAM_STR);
        $stm->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    
        if(!$stm->execute()) header('Location: errordefechaxd.php');
    
        header('Location: portal.php?fecha=true');
    }catch(PDOException $e){ 
        //Si hay algún error al enviar la consulta, se muestra un mensaje.
        header('Location: portal.php?error=bd');
    }
}