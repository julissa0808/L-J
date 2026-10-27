<?php
// ✅ 1. Iniciar sesión SIEMPRE al principio, antes de cualquier salida
session_start();

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // ✅ Validar que no estén vacíos
    if (empty($correo) || empty($contrasena)) {
        echo json_encode(["estado" => "error", "mensaje" => "Ingresa tu correo y contraseña"]);
        exit;
    }

    // ✅ Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["estado" => "error", "mensaje" => "El correo no tiene un formato válido"]);
        exit;
    }

    // ✅ Consulta preparada (está bien hecha, se mantiene)
    $stmt = $conexion->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nombre, $hash);
        $stmt->fetch();
        
        if (password_verify($contrasena, $hash)) {
            // ✅ Saneamos el nombre para evitar XSS
            $_SESSION['id'] = $id;
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_correo'] = $correo;
            
            echo json_encode([
                "estado" => "exito",
                "mensaje" => "Bienvenido, " . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . "!"
            ]);
        } else {
            echo json_encode(["estado" => "error", "mensaje" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["estado" => "error", "mensaje" => "No existe una cuenta con este correo"]);
    }

    $stmt->close();
    $conexion->close();
    exit; // ✅ Detenemos la ejecución tras la respuesta JSON
}
?>

<!-- ✅ Enlace fuera del bloque PHP y del procesamiento POST -->
<p><a href="login.html">Volver al formulario</a></p>