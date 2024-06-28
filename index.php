<?php
session_start();
@$usuarioActivo = $_SESSION['usuario_valido'];
@$id_carrito = $_SESSION['id_carrito'];
//echo $usuarioActivo;
//CONEXION CON LA BASE DE DATOS
include 'conexionBaseDatos.php';

//if ($_SESSION['usuario_valido']) {
if (isset($usuarioActivo)) {
    $nombre = $_SESSION['nombre'];
    print '<h3 style="text-align: center;">¡BIENVENIDO/A DE VUELTA '.strtoupper($nombre).'!</h3>';
    //echo $usuarioActivo;
    $user = $_SESSION['user'];
    $rol = $_SESSION['rol'];
} else {
    echo '<h3 style="text-align: center;"> ¡REGISTRATE <a href="./registro.php"> AQUI </a> PARA COMENZAR A COMPRAR PRODUCTOS!';
}

?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tienda de Ropa</title>
    </head>
    <body>
        <h1 style="text-align: center;">TIENDA DE ROPA</h1>
        <nav>
            <form action="gestionBotones.php" method="POST">
                <input type="submit" value="Inicio" name="boton">
                <?php
                if (isset($rol) && $rol == 1) {
                    print '<input type="submit" value="Ver vista administrador" name="boton">';
                }
                ?>
                <input type="submit" value="Inicia Sesión" name="boton">
                <?php
                if (isset($usuarioActivo)) {
                //if (isset($usuarioActivo) && !empty($user)) {
                    print '<input type="submit" value="Cerrar Sesión" name="boton" >';
                }
                ?>
            </form>

        </nav>
        <?php
        $consulta = "select * from productos where alta=1";
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
        $productos_mostrados = 0;

        echo '<table style="text-align: center;">';

        if ($resultado->num_rows > 0) {
            // Recorrer los resultados y los muestra en pantalla
            while ($fila = $resultado->fetch_assoc()) {
                if ($productos_mostrados % 5 == 0) {
                    echo '<tr>';
                }
                // echo "Imagen: " . $fila['foto'] . "<br>";
                //se codifica en base 64 el bob para transformarlo en imagen
                echo '<td>';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($fila['foto']) . '" alt="Imagen del producto"><br>';
                echo $fila['nombre'] . "<br>";
                echo "<b> " . $fila['precio'] . "€</b><br>";
                echo "<br>";
                print '<form action="gestionBotones.php" method="POST">';
                echo '<input type="hidden" name="id" value="' . $fila['cod_producto'] . '">';
                print '<input type="submit" value="Añadir al carro" name="boton">';
                print '</form>';
                echo '</td>';
                if (($productos_mostrados + 1) % 5 == 0) {
                    echo '</tr>';
                }

                // Incrementar el contador de productos mostrados
                $productos_mostrados++;
            }
            //si los productos son impares se cierra la fila
            if ($productos_mostrados % 5 != 0) {
                echo '</tr>';
            }

            echo '</table>'; // Cierra la tabla
        } else {
            echo "NO HAY PRODUCTOS DISPONIBLES EN LA TIENDA.";
        }
        ?>
    </body>
</html>
<?php
//SE CIERRA CONEXION
mysqli_close($conexion);
?>