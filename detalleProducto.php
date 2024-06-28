<?php

session_start();
include 'conexionBaseDatos.php';

$id = $_SESSION['id_producto'];
$usuarioActivo = $_SESSION['usuario_valido'];
$user = $_SESSION['user']; //es el id/correoelectronico del usuario no hay dos iguale

$consulta = 'SELECT id_pedido from pedidos where cliente="' . $user . '" AND estado=2';
$resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

if (mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $id_pedido = $fila['id_pedido'];
}
@$_SESSION['id_carrito'] = $id_pedido;

if (isset($usuarioActivo)) {
    @ $boton = $_REQUEST['btn'];
    if ($boton == 'Añadir') {
        $consulta = 'select carrito from usuarios where correoelectronico="' . $user . '"';
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
        if (mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado); // Obtener la primera fila del resultado como un array asociativo
            // Acceder al valor de 'carrito' en la fila obtenida
            $valor_carrito = $fila['carrito'];

            switch ($valor_carrito) {
                case 1:
                    echo 'EL CARRITO NO TIENE PRODUCTOS<br>';
                    //id_pedido	cliente	fecha	estado	
                    $consulta = 'INSERT INTO pedidos values(0,"' . $user . '", NOW(), 2)';
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

                    $consulta = 'SELECT id_pedido from pedidos where cliente="' . $user . '" AND estado=2';
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

                    if (mysqli_num_rows($resultado) > 0) {
                        $fila = mysqli_fetch_assoc($resultado);
                        $id_pedido = $fila['id_pedido'];
                    }

                    $cantidad = $_REQUEST['cantidad'];
                    $precio = floatval($_REQUEST['precio']);
                    $precio = $cantidad * $precio;
                    $consulta = 'INSERT INTO detalle_pedido values(0, ' . $id_pedido . ', ' . $id . ', ' . $cantidad . ', ' . $precio . ')';
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
                    //	cod_detalle	cod_pedido	cod_producto	cantidad	precio_segun_cantidad	

                    $consulta = 'UPDATE usuarios set carrito= 2 where correoelectronico="' . $user . '"';
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
                    echo "Los productos se han añadido";
                    $_SESSION['id_carrito'] = $id_pedido;

                    break;
                case 2:

                    echo "El carrito tiene productos<br>";
                    $consulta = 'SELECT * from detalle_pedido where cod_pedido=' . $id_pedido;
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
                    //echo $id;
                    echo "Producto añadido";
                    if (mysqli_num_rows($resultado) > 0) {
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            $id_producto = $fila['cod_producto'];
                            //echo 'ID: ' . $id . '<br>';
                            //echo 'ID P: ' . $id_producto . '<br>';

                            if ($id_producto == $id) {
                                echo 'ID igual a ID P<br>';
                                $encontrado = true;
                                $cantidadAntigua = $fila['cantidad'];
                            }
                        }
                    }

                    $cantidad = $_REQUEST['cantidad'];
                    $precio = floatval($_REQUEST['precio']);
                    //echo 'Encontrado ' . $encontrado;
                    if (isset($encontrado) && $encontrado) {
                        $nuevaCantidad = $cantidad + $cantidadAntigua;
                        $precioCalculo = $nuevaCantidad * $precio;
                        $consulta = 'UPDATE detalle_pedido SET cantidad = ' . $nuevaCantidad . ', precio_segun_cantidad = ' . $precioCalculo . ' WHERE cod_pedido = ' . $id_pedido . ' AND cod_producto = ' . $id;
                        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
                    } else {
                        $precioCalculo = $cantidad * $precio;
                        $consulta = 'INSERT INTO detalle_pedido values(0, ' . $id_pedido . ', ' . $id . ', ' . $cantidad . ', ' . $precioCalculo . ')';
                        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
                    }

                    break;
            }
        }
    } else if ($boton == 'Borrar Selección') {
        foreach (@$_POST['eliminar'] as $producto_eliminar) {
            $consulta = 'DELETE from detalle_pedido where cod_producto=' . $producto_eliminar . ' AND cod_pedido=' . $_SESSION['id_carrito'] . ''; //y que el codigo de pedido coimcida
            $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

            $consulta = 'select * from detalle_pedido where cod_pedido=' . $_SESSION['id_carrito'] . ''; //y que el codigo de pedido coimcida
            $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
            if (mysqli_num_rows($resultado) == 0) {
                $consulta = 'DELETE from pedidos where id_pedido=' . $_SESSION['id_carrito'] . ''; //y que el codigo de pedido coimcida
                $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

                $consulta = 'UPDATE usuarios set carrito= 1 where correoelectronico="' . $user . '"';
                $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
            }
        }
        print '<p>Se han eliminado los productos de tu cesta</p>';
    } else {
        //echo 'Detalle Producto';
        $consulta = "select * from productos where cod_producto=" . $id;
        $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");

        if ($resultado->num_rows > 0) {
            // Recorrer los resultados y los muestra en pantalla
            while ($fila = $resultado->fetch_assoc()) {
                // Aquí puedes acceder a cada columna de la fila
                //echo '<div style="text-align: center;">';
                echo '<div style="display: inline-block;">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($fila['foto']) . '"  alt="Imagen del producto"><br>';
                echo '</div>';

                echo '<div style="display: inline-block;">';
                print '<h1>' . $fila['nombre'] . '</h1>';
                echo "Imagen: " . $fila['color'] . "<br>";
                echo $fila['nombre'] . "<br>";
                echo "<b> " . $fila['precio'] . "€</b><br>";
                echo "<br>";
                print '<form action="detalleProducto.php" method="POST">';
                echo '<td> Unidades: <input type="number" min="1" max="'.$fila['unidades'] .'" name="cantidad" required> </td>';
                echo '<input type="hidden" name="id" value="' . $fila['cod_producto'] . '">';
                echo '<input type="hidden" name="precio" value="' . $fila['precio'] . '">';
                echo '<input type="hidden" name="nombre" value="' . $fila['nombre'] . '">';
                print '<input type="submit" value="Añadir" name="btn">';
                print '</form>';
                echo '</div>';

                // echo '</div>';
            }
            echo '<div style="display: inline-block;  margin-left: 300px;" >';
            print '<h1>Tu carrito</h1>';
            // $consulta = "select * from detalles_pedido where cod_pedido=" . $_SESSION['id_carrito'];
            // $idCarro = $_SESSION['id_carrito'];
            if (isset($id_pedido)) {
                $consulta = "SELECT productos.nombre, detalle_pedido.cantidad, detalle_pedido.precio_segun_cantidad, detalle_pedido.cod_producto FROM detalle_pedido
            JOIN productos ON detalle_pedido.cod_producto = productos.cod_producto  WHERE detalle_pedido.cod_pedido =$id_pedido";
                $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la consulta");
                $totalPagar = 0;
                if (mysqli_num_rows($resultado) > 0) {
                    print '<form action="detalleProducto.php" method="POST">';
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        print '<p><b>Producto: </b>' . $fila['nombre'] . '...';
                        print '<b>Cantidad: </b>' . $fila['cantidad'] . '... ';
                        print '<b>Total: </b>' . $fila['precio_segun_cantidad'];
                        print '<input type="checkbox" value="' . $fila['cod_producto'] . '" name="eliminar[]"> </input></p>';
                        $totalPagar += $fila['precio_segun_cantidad'];
                    }
                    print '<h3>TOTAL A PAGAR: .................................................. ' . $totalPagar . '€</h3>';
                    print '<input type=submit value="Borrar Selección" name="btn" </input>';
                    print '</form>';
                    print '<form action="gestionBotones.php" method="POST"><input type="submit" value="Pagar" name="boton" </input></form>';
                }
                echo '</div>';
            } else {
                print 'No hay productos en tu cesta';
                echo '</div>';
            }
        } else {
            echo 'Ha ocurrido un error cargando el producto';
        }
    }
} else {
    echo 'Acceso no autorizado';
}
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

