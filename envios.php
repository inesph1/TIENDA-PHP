<?php
session_start();
include 'conexionBaseDatos.php';
$usuarioActivo = $_SESSION['usuario_valido'];

if (isset($usuarioActivo)) {
    @$boton = $_REQUEST['btn'];
    if (isset($boton) && $boton == "Aceptar Envio") {
        foreach ($_POST['envios'] as $id_envio) {
            // Ejecutar la consulta para actualizar el estado de alta del producto actual
            $consulta = "UPDATE pedidos SET estado = 4 WHERE id_pedido =" . $id_envio;
            $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la insercción");

            $consulta = "insert into envios (id_pedido, fecha_envio, estado) values(".$id_envio.", NOW(), 4)";
            $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la insercción");
        }
        
        echo 'Pedidos enviados';
    } 
        ?>
        <html>
            <head>
                <meta charset="UTF-8">
                <title></title>
            </head>
            <body>
                <h1>Pedidos pendientes</h1>
                <form action="envios.php" method="POST">
                    <?php
                    $consulta = 'select * from pedidos where estado=3';
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

                    if (mysqli_num_rows($resultado) > 0) {
                        print '<table>';
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            echo '<tr>';
                            echo '<td>' . $fila['id_pedido'] . '</td>';
                            echo '<td>' . $fila['cliente'] . '</td>';
                            echo '<td>' . $fila['fecha'] . '</td>';
                            print '<td><input type="checkbox" value="' . $fila['id_pedido'] . '" name="envios[]"> </input></td>';
                          
                            echo '</tr>';
                        }
                        print '</table>';
                          print '<input type="submit" value="Aceptar Envio" name="btn"> </input>';
                    }else{
                         print '<p>No hay envios pendientes </p>';
                        
                    }
                    ?>

                </form>
            </body>
        </html>
        <?php
    
} else {
    echo 'Permiso no autorizado';
}
?>