<?php
session_start();
include 'conexionBaseDatos.php';
@$boton = $_REQUEST['boton'];
@$usuarioActivo = $_SESSION['usuario_valido'];
@$user = $_SESSION['user'];
@$pass = $_SESSION['pass'];

//si el usuario ya esta activo muestra sus datos de usuario y permite cerrar sesion
if (isset($usuarioActivo) && !empty($user)) {
    echo '<h1>PERFIL DE USUARIO '. strtoupper($_SESSION['nombre']).'</h1>';
    echo 'Correo Electrónico ' . $user;
    echo'<br><form action="gestionBotones.php" method="post"><input type="submit" name="boton" value="Cerrar Sesión"></form>';
    print '<h3>Historial de pedidos</h3>';

    $consulta = 'SELECT envios.*, pedidos.fecha AS fecha_pedido, estados_compra.tipo AS estado_envio FROM envios JOIN pedidos ON envios.id_pedido = pedidos.id_pedido JOIN estados_compra ON envios.estado = estados_compra.cod_estado WHERE pedidos.cliente = "' . $user . '" AND (envios.estado = 3 OR envios.estado = 4)';
    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");

    if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            print '<p>Codigo Envio: '. $fila['cod_envio'].' Fecha pedido: '. $fila['fecha_pedido'].' Estado envio: '.$fila['estado_envio'].' Fecha envio: '.$fila['fecha_envio'].'</p>';
        }
    } else {
        print '<p>Aun no hay envios realizados</p>';
    }
} else if (isset($boton) && $boton = 'Inicia Sesión') {
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];

    //echo $user;
    //echo $pass;

    if (isset($user) && isset($pass) && !empty($user) && !empty($pass)) {

        $consulta = 'select * from usuarios where correoelectronico="' . $user . '"';
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");

        if ($resultado) {
            $nfilas = mysqli_num_rows($resultado);

            if ($nfilas > 0) {
                //devuelve la fila de la consulta
                $fila = mysqli_fetch_assoc($resultado);

                //guarda en la sesion el nombre, el usuario y la clave de rol
                $_SESSION['user'] = $fila['correoelectronico'];
                $_SESSION['pass'] = $fila['contraseña'];
                $_SESSION['rol'] = $fila['rol'];
                $_SESSION['nombre'] = $fila['nombre'];

                //compurueba si contraseña y nombre coinciden
                if ($user == $_SESSION['user'] && $pass == $_SESSION['pass']) {
                    //el usuario valido es el nombre del usuario que ha inciiado sesion
                    $_SESSION['usuario_valido'] = '' . $user . '';
                }

                //si existe el usuario_valido
                if (isset($_SESSION['usuario_valido'])) {
                    //codigo para usuarios autorizados
                    echo 'ACCESO AUTORIZADO';
                    header("Location: ./index.php");
                    exit();
                } else {
                    echo 'Correo electrónico incorrecto <a href="./login.php"> Volver </a>';
                }
            } else {
                echo 'Usuario/contraseña incorrectos   <a href="./login.php"> Volver </a>';
            }
        } else {
            echo 'Fallo en la consulta de comprobación de usuario';
        }
    } else {
        echo 'Rellene todos los campos  <a href="./login.php"> Volver </a>';
    }
} else {
// COMPROBAR SI HAY CREDENCIALES DE INICIO SI YA HAS METIDO DATOS Y EL USUARIO EXISTE SE CREA E INICIA LA SESION
//Y TE ENVIA A TU PERFIL
//SI NO HAY DATOS TE MUESTRA EL FORMULARIO
    ?>

    <!DOCTYPE html>
    <!--
    Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
    Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
    -->
    <html>
        <head>
            <meta charset="UTF-8">
            <title></title>
        </head>
        <body>
            <h1>ESTAS EN LOGIN </h1>   
            <form action="login.php" method="POST">
                Correo Electrónico: <input type="text" name="user">
                Contraseña: <input type="text" name= "pass">
                <input type="submit" value="Inicia Sesión" name="boton">
            </form>
            <p> Inicia sesión con tu cuenta de correo electrónico o <a href="./registro.php"> Registrate aquí </a></p>
           
            
            <?php
        }
        ?>
    </body>
</html>
