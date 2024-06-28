<?php
$conexion = mysqli_connect("localhost", "root", "") or die("No se puede conecatr con el servidor");
mysqli_select_db($conexion, "tienda_virtual_1") or die("No se puede seleccionar la base de datos");
?>


