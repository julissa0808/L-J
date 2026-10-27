<?php
include 'conexionbd.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["estado" => "error", "mensaje" => "Debes iniciar sesión primero"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $tipo_pago = $_POST['tipo_pago'];
    $banco = !empty($_POST['banco']) ? trim($_POST['banco']) : null;
    $pin = !empty($_POST['pin']) ? trim($_POST['pin']) : null;
    $total = $_POST['total'];
    $productos = $_POST['productos'];

    if (empty($nombre) || empty($correo) || empty($direccion) || empty($tipo_pago)) {
        echo json_encode(["estado" => "error", "mensaje" => "Completa todos los campos obligatorios"]);
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO pedidos 
        (usuario_id, nombre_cliente, correo, telefono, direccion, tipo_pago, banco, pin_tarjeta, total, productos)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("issssssssd", $usuario_id, $nombre, $correo, $telefono, $direccion, $tipo_pago, $banco, $pin, $total, $productos);

    if ($stmt->execute()) {
        echo json_encode(["estado" => "exito", "mensaje" => "Pedido guardado con éxito", "id_pedido" => $conexion->insert_id]);
    } else {
        echo json_encode(["estado" => "error", "mensaje" => "Error al guardar: " . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
}
?>