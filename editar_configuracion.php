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

// Obtener la configuración actual del usuario
$query_config = "SELECT * FROM configuracion WHERE user_id = '$user_id'";
$result_config = $conn->query($query_config);
$config = $result_config->num_rows > 0 ? $result_config->fetch_assoc() : [];

// Obtener el nombre de usuario desde la tabla usuarios
$query_user = "SELECT username FROM usuarios WHERE id = '$user_id'";
$result_user = $conn->query($query_user);
$user = $result_user->num_rows > 0 ? $result_user->fetch_assoc() : [];

// Manejar la edición de información personal en la tabla configuracion
if (isset($_POST['update_info'])) {
    $estado_sentimental = isset($_POST['estado_sentimental']) ? $conn->real_escape_string($_POST['estado_sentimental']) : '';
    $biografia = isset($_POST['biografia']) ? $conn->real_escape_string($_POST['biografia']) : '';
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $conn->real_escape_string($_POST['fecha_nacimiento']) : '';
    $nombre_usuario = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';

    // Actualizar el nombre de usuario en la tabla usuarios
    $update_user_query = "UPDATE usuarios SET username = '$nombre_usuario' WHERE id = '$user_id'";
    if ($conn->query($update_user_query)) {
        echo "Nombre de usuario actualizado con éxito.";
    } else {
        echo "Error al actualizar el nombre de usuario: " . $conn->error;
    }

    // Actualizar la configuración (estado sentimental, biografía y fecha de nacimiento)
    $update_info_query = "UPDATE configuracion SET estado_sentimental = '$estado_sentimental', biografia = '$biografia', fecha_nacimiento = '$fecha_nacimiento' WHERE user_id = '$user_id'";
    if ($conn->query($update_info_query)) {
        echo "Información personal actualizada con éxito.";
    } else {
        echo "Error al actualizar la información personal: " . $conn->error;
    }
}

// Manejar la carga de nuevas imágenes
if (isset($_FILES['foto_portada']) || isset($_FILES['foto_perfil'])) {
    $target_dir = "imagenes/";  // Ruta de la carpeta para subir las imágenes
    $foto_portada = $config['foto_portada'] ?? '';
    $foto_perfil = $config['foto_perfil'] ?? '';

    // Subir imagen de portada
    if (isset($_FILES['foto_portada']) && $_FILES['foto_portada']['error'] == 0) {
        $foto_portada = $target_dir . basename($_FILES['foto_portada']['name']);
        move_uploaded_file($_FILES['foto_portada']['tmp_name'], $foto_portada);
    }

    // Subir imagen de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_perfil = $target_dir . basename($_FILES['foto_perfil']['name']);
        move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);
    }

    $update_query = "UPDATE configuracion SET foto_portada = '$foto_portada', foto_perfil = '$foto_perfil' WHERE user_id = '$user_id'";
    if ($conn->query($update_query)) {
        echo "Imágenes actualizadas con éxito.";
        header("Location: muro.php");
        exit();
    } else {
        echo "Error al actualizar las imágenes: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Configuración - Lifebest</title>
    <link rel="stylesheet" href="css/editar_configuracion.css">

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <style>
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        #preview {
            width: 100%;
            height: 400px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f0f0f0;
            border: 1px solid #ccc;
            margin-top: 20px;
            border-radius: 10px;
        }

        #image_preview {
            max-width: 100%;
            max-height: 100%;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar configuración de tu perfil</h1>

    <!-- Formulario de edición de información -->
    <form method="POST">
        <h2>Información personal</h2>
        
        <!-- Nombre de usuario -->
        <div class="input-group">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
        </div>

        <div class="input-group">
            <label for="estado_sentimental">Estado sentimental</label>
            <input type="text" id="estado_sentimental" name="estado_sentimental" value="<?php echo htmlspecialchars($config['estado_sentimental'] ?? ''); ?>" >
        </div>
        <div class="input-group">
            <label for="biografia">Biografía</label>
            <textarea id="biografia" name="biografia"><?php echo htmlspecialchars($config['biografia'] ?? ''); ?></textarea>
        </div>
        <div class="input-group">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($config['fecha_nacimiento'] ?? ''); ?>" >
        </div>

        <button type="submit" name="update_info">Actualizar información</button>
    </form>

    <!-- Formulario para actualizar imágenes -->
    <form method="POST" enctype="multipart/form-data">
        <h2>Imágenes</h2>
        <div class="input-group">
            <label for="foto_portada">Imagen de portada</label>
            <input type="file" id="foto_portada" name="foto_portada">
            <?php if (!empty($config['foto_portada'])): ?>
                <img src="<?php echo htmlspecialchars($config['foto_portada']); ?>" alt="Imagen de portada actual" width="300">
            <?php endif; ?>
        </div>

        <div class="input-group">
            <label for="foto_perfil">Imagen de perfil</label>
            <input type="file" id="foto_perfil" name="foto_perfil">
            <?php if (!empty($config['foto_perfil'])): ?>
                <img src="<?php echo htmlspecialchars($config['foto_perfil']); ?>" alt="Imagen de perfil actual" width="150">
            <?php endif; ?>
        </div>
        <button type="submit">Actualizar imágenes</button>
    </form>
    <a href="muro.php" class="btn">Volver al muro</a>
</div>

<script>
    let cropper;
    const imagePreview = document.getElementById('image_preview');

    document.getElementById('foto_perfil').addEventListener('change', (e) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            imagePreview.src = event.target.result;
            imagePreview.style.display = 'block';

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(imagePreview, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.65
            });
        };
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
</body>
</html>
