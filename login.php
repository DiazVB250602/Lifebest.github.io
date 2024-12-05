<?php
session_start();
$conn = new mysqli("localhost", "root", "250602", "lifebest");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $result = $conn->query("SELECT * FROM usuarios WHERE email = '$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: muro.php"); // Redirige al muro del usuario
        exit();
    } else {
        // Redirige con el parámetro de error
        header("Location: login.html?error=invalid_credentials");
        exit();
    }
}
?>
