// login.js

// Función que se ejecuta al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error === 'invalid_credentials') {
        // Mostrar el mensaje de error
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');
        errorText.innerHTML = 'Correo o contraseña incorrectos. <a href="restablecer_contraseña.php">Restablecer Contraseña</a>';
        errorMessage.style.display = 'block';
    }
});
