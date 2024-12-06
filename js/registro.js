// Agregar efecto de foco a los campos del formulario
const inputs = document.querySelectorAll('input, textarea');

inputs.forEach(input => {
    input.addEventListener('focus', () => {
        input.style.borderColor = '#9c27b0';
        input.style.boxShadow = '0 0 5px #9c27b0';
    });

    input.addEventListener('blur', () => {
        input.style.borderColor = '#ccc';
        input.style.boxShadow = 'none';
    });
});


// Función para comprobar la fuerza de la contraseña
function checkPasswordStrength() {
    var password = document.getElementById('password').value;
    var strengthText = document.getElementById('password-strength');
    var strength = "Baja";

    if (password.length >= 8) {
        strength = "Media";
        if (/[A-Z]/.test(password) && /[0-9]/.test(password)) {
            strength = "Alta";
        }
    }

    strengthText.innerText = "Fuerza de la contraseña: " + strength;
}

// Validar si se subieron las fotos de perfil y portada
function validarFormulario() {
    var fotoPerfil = document.getElementById('foto_perfil').files.length;
    var fotoPortada = document.getElementById('foto_portada').files.length;

    if (fotoPerfil === 0 || fotoPortada === 0) {
        alert("POR TU SEGURIDAD Y LA DE LOS DEMÁS USUARIOS, ES FORZOSO UTILIZAR UNA IMAGEN POR SU INTEGRIDAD Y LA DE LIFEBEST");
        return false;  // Evita el envío del formulario si no se seleccionaron fotos
    }

    // Validación de la contraseña
    var password = document.getElementById('password').value;
    var passwordValid = validatePassword(password);
    if (!passwordValid) {
        alert("La contraseña debe tener al menos 8 caracteres, una letra mayúscula y no contener números consecutivos.");
        return false;
    }
    return true;
}

// Validar la contraseña según las reglas
function validatePassword(password) {
    // Verificar longitud mínima de 8 caracteres
    if (password.length < 8) return false;

    // Verificar al menos una letra mayúscula
    if (!/[A-Z]/.test(password)) return false;

    // Verificar que no contenga números consecutivos
    var regex = /123|234|345|456|567|678|789|890|012|321|432|543|654|765|876|987/;
    if (regex.test(password)) return false;

    return true;
}
