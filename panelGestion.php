<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
include 'conexionBaseDatos.php';

$usuarioActivo = $_SESSION['usuario_valido'];
$user = $_SESSION['user'];
$rol = $_SESSION['rol'];
@$boton = $_REQUEST['boton'];

if ($boton == "Registrar Producto") {
    //meter en la bd y recoger datos
    @$nombre = $_POST['nombre'];
    @$color = $_POST['color'];
    @$unidades = $_POST['unidades'];
    @$talla = $_POST['tallas'];
    @$precio = floatval($_POST['precio']);
    @$descripcion = $_POST['descripcion'];
    @$alta = $_POST['alta'];

    //$sin_imagen = "0.jpeg";
        //echo var_dump($_FILES);
    if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] === UPLOAD_ERR_OK) {
        //si se sube foto se sobreescribe
        $foto = file_get_contents($_FILES['Foto']['tmp_name']);
       // $foto = addslashes($foto);
    }
    //echo '<br>'.var_dump($_FILES);
    //var_dump($foto);
    //$foto = file_get_contents($sin_imagen);
    //$contenido_codificado = base64_encode($foto);
    //echo 'FILES ' . $_FILES['Foto']['tmp_name'];
    //echo "Longitud del blob: " . strlen($foto); //devuelve el numero de caracteres de la cadena
    if (isset($alta)) {
        $alta = 1;
    } else {
        $alta = 0;
    }
    //echo $nombre . '", "' . $color . '", ' . $unidades . ', "' . $talla . '", ' . $precio . 'DESC ' . $descripcion . ' ALTA ' . $alta . ' FOTO ' . $foto;
    //echo $foto;
    $consulta = 'select count(*) from productos where nombre="' . $nombre . '"';
    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        @$total = $fila['total'];
        if ($total == 0) {
            //PREPARA LA CONSULTA
            $consulta = "INSERT INTO productos (nombre, color, unidades, talla, precio, descripción, alta, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            //echo $foto;
            //echo "Consulta SQL: " . $consulta;
            //LE PASA LOS VALORES
            $stmt->bind_param("ssisdsib", $nombre, $color, $unidades, $talla, $precio, $descripcion, $alta, $foto);
            // $consulta = 'INSERT INTO productos (nombre, color, unidades, talla, precio, descripción, alta, foto) VALUES ("' . $nombre . '", "' . $color . '", ' . $unidades . ', "' . $talla . '", ' . $precio . ', "' . $descripcion . '", ' . $alta . ' , ' . $foto . ')';
            //$resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");
            if ($stmt->execute()) {
               // echo var_dump($foto);
                echo "Producto insertado correctamente.";
               // echo $foto;
            } else {
                echo "Error al insertar el producto: " . $stmt->error;
            }

            //LA EJECUTA
            $stmt->close();
            //mysqli_close($conexion);
            //print 'Producto registrado con exito.';
            print'<br><a href="panelGestion.php">Volver</a>';
        } else {
            print 'Ya existe ese producto, pruebe a sumar unidades.';
            print'<br><a href="panelGestion.php">Volver</a>';
        }
    } else {
        print 'Se ha producido un error en la consulta de comprobacion de nombres';
    }
} else if ($boton == "Sumar Cantidades") {
    echo "sumar";
    if (isset($_POST['productos'])) {
        // Iterar sobre los datos recibidos del formulario
        foreach ($_POST['productos'] as $producto_id => $datos_producto) {
            $id_producto = $datos_producto['id'];
            $unidades_actuales = $datos_producto['unidadesActuales'];
            $cantidad_a_sumar = $datos_producto['cantidad'];

            $cantidad_a_sumar = !empty($datos_producto['cantidad']) ? $datos_producto['cantidad'] : 0;

            if ($cantidad_a_sumar != 0) {
                $total_unidades = $unidades_actuales + $cantidad_a_sumar;

                $consulta = "UPDATE productos SET unidades = $total_unidades WHERE cod_producto = " . $datos_producto['id'];
                $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");

                echo "Producto id " . $datos_producto['id'] . " cantidad sumada " . $cantidad_a_sumar . ". Total actual: " . $total_unidades;
            }
        }
    }
    //mysqli_close($conexion);
    print'<br><a href="panelGestion.php">Volver</a>';
} else if ($boton == "Dar de Alta" || $boton == "Dar de Baja") {
    if ($boton == "Dar de Alta") {
        $accion = 1;
        $mensaje = "Se han dado de alta los productos";
    } else {
        $accion = 0;
        $mensaje = "Se han dado de baja los productos";
    }
    if (isset($_POST['seleccionados'])) {
        // Recorrer los productos seleccionados
        foreach ($_POST['seleccionados'] as $id_producto) {
            // Ejecutar la consulta para actualizar el estado de alta del producto actual
            $consulta = "UPDATE productos SET alta = $accion WHERE cod_producto = $id_producto";
            $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la insercción");
        }
        // Mostrar mensaje de éxito
        echo $mensaje;
        print'<br><a href="panelGestion.php">Volver</a>';
    } else {
        // Mostrar mensaje si no se han seleccionado productos
        echo "No se han seleccionado productos para dar de alta o baja.";
    }
} else {
    if ($usuarioActivo) {
        ?>
        <form action="panelGestion.php" method="POST" enctype="multipart/form-data">
            <h1 style="display: inline;">REGISTRA UN NUEVO PRODUCTO</h1>
            <a href="vistaAdmin.php" style="display: inline;">Volver</a>
            <br><br><input type="checkbox" name="alta"> ALTA
            Nombre: <input type="text" name="nombre" required>
            Color: <input type="text" name= "color" required>
            Unidades: <input type="number" min="1" max="200" name= "unidades" required>
            <label for="tallas">Talla:</label>
            <select id="tallas" name="tallas" required>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
            Precio: <input type="text" name="precio" required>
            Descripcion: <input type="textarea" name="descripcion" value="" maxlength="160"> 
            <br><br>
            <input type="file" name="Foto"/>
            <input type="submit" value="Registrar Producto" name="boton">
        </form>

        <form action="panelGestion.php" method="POST">
            <h1>SUMA CANTIDADES</h1>
            <?php
            $consulta = 'select nombre,color,unidades,talla,cod_producto from productos where alta=1 order by nombre';
            $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");
            if (mysqli_num_rows($resultado) > 0) {
                // Inicio de la tabla HTML
                echo '<table border="1">';
                echo '<tr><th>Nombre</th><th>Color</th><th>Unidades</th><th>Talla</th><th>Cantidad a sumar</th></tr>';
                // Recorrer los resultados y generar filas de tabla para cada registro
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo '<tr>';
                    echo '<td>' . $fila['nombre'] . '</td>';
                    echo '<td>' . $fila['color'] . '</td>';
                    echo '<td>' . $fila['unidades'] . '</td>';
                    echo '<td>' . $fila['talla'] . '</td>';
                    echo '<td> Unidades: <input type="number" min="1" max="200" name="productos[' . $fila['cod_producto'] . '][cantidad]"> </td>';
                    echo '<input type="hidden" name="productos[' . $fila['cod_producto'] . '][id]" value="' . $fila['cod_producto'] . '">';
                    echo '<input type="hidden" name="productos[' . $fila['cod_producto'] . '][unidadesActuales]" value="' . $fila['unidades'] . '">';
                    echo '</tr>';
                }
                echo '</table>';
                echo '<input type="submit" value="Sumar Cantidades" name="boton"></form>';
            } else {
                // Si no hay resultados, imprimir un mensaje indicando que no se encontraron registros
                echo 'No hay productos dados de alta.<br><br>';
            }
            // Cerrar la conexión a la base de datos
            ?>
            <form action="panelGestion.php" method="POST">
                <input type="submit" name="btn" value="Ver Altas">
                <input type="submit" name="btn" value="Ver Bajas">
                <?php
                if (isset($_POST['btn'])) {
                    if ($_POST['btn'] == "Ver Altas") {
                        print '<h1>ALTAS</h1>';
                        $valor = 1;
                        $accion = 'Dar de Baja';
                    } else if ($_POST['btn'] == "Ver Bajas") {
                        print '<h1>BAJAS</h1>';
                        $valor = 0;
                        $accion = 'Dar de Alta';
                    }

                    $consulta = "SELECT * FROM productos WHERE alta = $valor";
                    $resultado = mysqli_query($conexion, $consulta) or die("Fallo en la inserccion");
                    if (mysqli_num_rows($resultado) > 0) {
                        // Inicio de la tabla HTML
                        echo ' <form action="panelGestion.php" method="POST">';
                        echo '<table border="1">';
                        echo '<tr><th>Nombre</th><th>Color</th><th>Unidades</th><th>Talla</th><th>' . $accion . '</th></tr>';
                        // Recorrer los resultados y generar filas de tabla para cada registro
                        while ($fila = mysqli_fetch_assoc($resultado)) {
                            echo '<tr>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['color'] . '</td>';
                            echo '<td>' . $fila['unidades'] . '</td>';
                            echo '<td>' . $fila['talla'] . '</td>';
                            echo '<td><input type="checkbox" name="seleccionados[]" value="' . $fila['cod_producto'] . '"> </td>';

                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '<input type="submit" name="boton" value="' . $accion . '">';
                    }
                    echo ' </form>';
                }
                ?>

            </form>
            <?php
        } else {
            echo 'Acceso no autorizado';
        }
    }
    mysqli_close($conexion);
    ?>
