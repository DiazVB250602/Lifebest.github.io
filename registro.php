<?php
$conn = new mysqli("localhost", "root", "250602", "lifebest");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);

    // Comprobar si el correo ya está registrado
    $result = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
    if ($result->num_rows > 0) {
        // Si el correo ya está registrado, mostrar alerta y redirigir
        echo "<script>
                alert('Este correo electrónico ya está registrado.');
                window.location.href = 'login.html';
              </script>";
        exit();
    }

    // Subir fotos
    $foto_perfil = "imagenes/" . basename($_FILES['foto_perfil']['name']);
    move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);

    $foto_portada = "imagenes/" . basename($_FILES['foto_portada']['name']);
    move_uploaded_file($_FILES['foto_portada']['tmp_name'], $foto_portada);

    $estado_sentimental = $conn->real_escape_string($_POST['estado_sentimental']);
    $biografia = $conn->real_escape_string($_POST['biografia']);
    $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);

    // Registrar usuario
    $conn->query("INSERT INTO usuarios (username, email, password) VALUES ('$username', '$email', '$password')");
    $user_id = $conn->insert_id;

    // Registrar configuración de perfil
    $conn->query("INSERT INTO configuracion (user_id, foto_perfil, foto_portada, estado_sentimental, biografia, fecha_nacimiento) 
                  VALUES ('$user_id', '$foto_perfil', '$foto_portada', '$estado_sentimental', '$biografia', '$fecha_nacimiento')");

    header("Location: login.html");
    exit();
}
?>
