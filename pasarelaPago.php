<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();
include 'conexionBaseDatos.php';
$usuarioActivo = $_SESSION['usuario_valido'];
//echo $usuarioActivo;
if (isset($usuarioActivo)) {
    $pedido = $_SESSION['id_carrito'];
   // echo $pedido;
    @$boton = $_POST['btn'];
    if (isset($boton)) {
        //nuevo carrito para el usuario
        $consulta = 'UPDATE usuarios set carrito=1 where correoelectronico="' . $usuarioActivo . '"';
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
        
        //$consulta = 'UPDATE pedidos set estado= 3 where id_pedido='. $pedido;
        $consulta = 'UPDATE pedidos SET estado = 3, fecha = NOW() WHERE id_pedido = ' . $pedido;//CAMBIADO COMPROBAR
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
        print '<p>HEMOS TRAMITADO TU PEDIDO, UN MODERADOR DEBERÁ ACEPTARLO, PODRÁS VER EL ESTADO EN TU PERFIL DE USUARIO</p>';
        
    } else {
        ?>

        <html>
            <head>
                <meta charset="UTF-8">
                <title></title>
            </head>
            <body>
                <div style="text-align: center;">
                <h1>PASARELA DE PAGO</h1>
                <p>Por favor, rellene sus datos</p>
                <form action="pasarelaPago.php" method="POST">
                    <label for="dni">DNI:</label>
                    <input type="text" id="dni" name="dni" required><br><br>

                    <label for="tarjeta">Tarjeta:</label>
                    <input type="text" id="tarjeta" name="tarjeta" required><br><br>

                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required><br><br>

                    <label for="calle">Calle:</label>
                    <input type="text" id="calle" name="calle" required><br><br>

                    <input type="submit" value="Aceptar" name="btn">
                </form>
                </div>
            </body>
        </html>
        <?php
    }
} else {
    echo 'Acceso no autorizado';
}
?>