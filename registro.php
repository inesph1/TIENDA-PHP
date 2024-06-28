<?php
include 'conexionBaseDatos.php';
@$registro = $_REQUEST['enviar'];
//print $registro;

if (isset($registro) && $registro == 'Registrar') {
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];
    $nombre = $_REQUEST['nombre'];

    if (isset($user) && isset($pass) && isset($nombre) && !empty($user) && !empty($pass) && !empty($nombre)) {
        $consulta = 'select count(*) from usuarios where correoelectronico="' . $user . '"';
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");

        if ($resultado) {
            $fila = mysqli_fetch_assoc($resultado);
            @$total = $fila['total'];
            if ($total == 0) {
                $consulta = 'insert into usuarios(correoelectronico, contraseña, nombre, rol, carrito) values("' . $user . '" , "' . $pass . '" , "' . $nombre . '", 2 , 1 );';
                $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");
                mysqli_close($conexion);
                print 'Usuario registrado con exito. Confirma tu correo electrónico y loggeate. <a href="./login.php"> Volver </a>';
            } else {
                print 'El nombre de usuario no esta disponible. Pruebe con otro. <a href="./registro.php"> Volver </a>';
            }
        } else {
            print 'Se ha producido un error en la consulta de comprobaciond e nombres';
        }
    } else {
        print 'Rellene todos los campos <a href="./registro.php"> Volver </a>';
    }
} else {
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
            <h1>ESTAS EN REGISTRO</h1>
            <form action="registro.php" method="POST">
                Correo Electrónico: <input type="text" name="user">
                Contraseña: <input type="password" name= "pass">
                Nombre: <input type="text" name= "nombre">
                <input type="submit" value="Registrar" name="enviar">
                <br><a href="./index.php"> Volver </a>
            </form>
            <?php
        }
        ?>
    </body>
</html>
