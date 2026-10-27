<?php
<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibir datos del formulario
    $nombre    = trim($_POST['nombre']);
    $apellido  = trim($_POST['apellido']);
    $correo    = trim($_POST['correo']);
    $telefono  = trim($_POST['numero']);
    $contrasena = $_POST['password'];
    $confirmar = $_POST['confirmar'];
    $sexo      = $_POST['sexo'];
    $edad      = $_POST['edad'];

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($telefono) || empty($contrasena) || empty($sexo) || empty($edad)) {
        echo json_encode(["estado" => "error", "mensaje" => "⚠️ Todos los campos con * son obligatorios"]);
        exit;
    }

    // Validar correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["estado" => "error", "mensaje" => "⚠️ El correo no es válido"]);
        exit;
    }

    // Verificar que las contraseñas coincidan
    if ($contrasena !== $confirmar) {
        echo json_encode(["estado" => "error", "mensaje" => "⚠️ Las contraseñas no coinciden"]);
        exit;
    }

    // Validar longitud de contraseña
    if (strlen($contrasena) < 5) {
        echo json_encode(["estado" => "error", "mensaje" => "⚠️ La contraseña debe tener al menos 8 caracteres"]);
        exit;
    }

    // Verificar si el correo ya existe
    $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["estado" => "error", "mensaje" => "⚠️ Este correo ya está registrado"]);
        $stmt->close();
        exit;
    }

    $stmt->close();

    // Cifrar contraseña     
    $hash_contraseña = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, correo, telefono, contrasena, sexo, edad) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $nombre, $apellido, $correo, $telefono, $hash_contraseña, $sexo, $edad);

    if ($stmt->execute()) {
        echo json_encode(["estado" => "exito", "mensaje" => "✅ Cuenta creada con éxito. ¡Bienvenido!"]);
    } else {
        echo json_encode(["estado" => "error", "mensaje" => "❌ Error al crear la cuenta: " . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
}
?>