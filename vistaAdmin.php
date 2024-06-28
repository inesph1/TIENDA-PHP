<?php
session_start();
$usuarioActivo = $_SESSION['usuario_valido'];
$user = $_SESSION['user'];

echo $usuarioActivo;

if (isset ($usuarioActivo)) {

//comprobar que es un admin y si no poner que no tiene permisos
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
            <h1>ESTA ES LA VISTA DE ADMINISTRADOR</h1>
            <form action="gestionBotones.php" method="POST">
                <input type="submit" value="Dar de alta/baja productos" name="boton">
                <input type="submit" value="Pantalla de envios pendientes" name="boton">
                <input type="submit" value="Ver vista usuario" name="boton">

                <input type="submit" value="Inicia Sesión" name="boton">
            </form>
            <?php
            ?>
        </body>
    </html>

    <?php
}else{
    echo 'Acceso no autorizado';
}
    ?>
