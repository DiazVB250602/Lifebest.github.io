<?php
session_start();
$conn = new mysqli("localhost", "root", "250602", "lifebest");

// Verificar si la conexión a la base de datos es exitosa
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario de la sesión
$user_id = $conn->real_escape_string($_SESSION['user_id']);

// Obtener datos del usuario
$query_user = "SELECT * FROM usuarios WHERE id = '$user_id'";
$result_user = $conn->query($query_user);
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "No se encontraron datos del usuario.";
    exit();
}

// Obtener configuración del usuario
$query_config = "SELECT * FROM configuracion WHERE user_id = '$user_id'";
$result_config = $conn->query($query_config);
if ($result_config->num_rows > 0) {
    $config = $result_config->fetch_assoc();
} else {
    echo "No se encontró la configuración del usuario.";
    exit();
}

// Asignar variables de configuración con validación
$foto_portada = !empty($config['foto_portada']) ? htmlspecialchars($config['foto_portada']) : 'imagenes/default-portada.jpg';
$foto_perfil = !empty($config['foto_perfil']) ? htmlspecialchars($config['foto_perfil']) : 'imagenes/default-perfil.jpg';
$username = htmlspecialchars($user['username']);
$biografia = !empty($config['biografia']) ? htmlspecialchars($config['biografia']) : "Aún no hay biografía.";
$estado_sentimental = !empty($config['estado_sentimental']) ? htmlspecialchars($config['estado_sentimental']) : "Sin especificar";
$email = htmlspecialchars($user['email']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muro - Lifebest</title>
    <link rel="stylesheet" href="css/muro.css">
</head>
<body>
    <div class="muro-container">
        <!-- Portada -->
        <div class="portada">
            <img src="<?php echo $foto_portada; ?>" alt="Foto de portada" class="foto-portada">
        </div>

        <!-- Perfil -->
        <div class="perfil">
            <img src="<?php echo $foto_perfil; ?>" alt="Foto de perfil" class="foto-perfil">
            <h1><?php echo $username; ?></h1>
        </div>

        <!-- Información del usuario -->
        <div class="informacion-usuario">
            <p><strong>Correo electrónico:</strong> <?php echo $email; ?></p>
            <p><strong>Biografía:</strong> <?php echo $biografia; ?></p>
            <p><strong>Estado sentimental:</strong> <?php echo $estado_sentimental; ?></p>
        </div>

        <!-- Opciones -->
        <div class="opciones">
            <a href="editar_configuracion.php" class="btn">Editar configuración</a>
            <a href="interfaz.html" class="btn">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
