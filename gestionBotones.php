<?php

session_start();
$usuarioActivo = $_SESSION['usuario_valido'];
$user = $_SESSION['user'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
$id_carro = $_SESSION['id_carrito'];

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$boton = $_REQUEST['boton'];
switch ($boton) {
    case $boton == 'Inicio' || $boton == 'Ver vista usuario':
        header("Location: ./index.php");
        exit();
        break;
    case $boton == 'Inicia Sesión':
        header("Location: ./login.php");
        exit();
        break;
    case ($boton == 'Añadir al carro'):
        if (isset($usuarioActivo) && !empty($user)) {
            $_SESSION['id_producto'] = $_REQUEST['id'];
            header("Location: ./detalleProducto.php");
            exit();
            break;
        } else {
            header("Location: ./registro.php");
            exit();
            break;
        }
        break;
    case $boton == 'Dar de alta/baja productos':
        header("Location: ./panelGestion.php");
        exit();
        break;
    case $boton == 'Pagar':
        header("Location: ./pasarelaPago.php");
        exit();
        break;
    case $boton == 'Ver Producto':
        header("Location: ./registro.php");
        exit();
        break;
    case $boton == 'Cerrar Sesión':
        session_destroy();
        header("Location: ./index.php");
        exit();
        break;
    case $boton == 'Ver vista administrador':
        header("Location: ./vistaAdmin.php");
        exit();
        break;
    case $boton == 'Pantalla de envios pendientes':
        header("Location: ./envios.php");
        exit();
        break;
   /* case $boton == 'Volver':
        header("Location: ./login.php");
        exit();
        break;*/
}
