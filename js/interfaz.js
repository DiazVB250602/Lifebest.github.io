// Selección de elementos
const buttons = document.querySelectorAll('.button');
const image = document.querySelector('.image-container img');

// Efecto de escala en los botones al pasar el mouse
buttons.forEach(button => {
    button.addEventListener('mouseover', () => {
        button.style.transform = 'scale(1.1)';
        button.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.3)';
        button.style.transition = 'all 0.3s ease';
    });

    button.addEventListener('mouseout', () => {
        button.style.transform = 'scale(1)';
        button.style.boxShadow = 'none';
    });
});

// Efecto "zoom in" para la imagen al pasar el mouse
image.addEventListener('mouseover', () => {
    image.style.transform = 'scale(1.05)';
    image.style.transition = 'transform 0.5s ease';
});

image.addEventListener('mouseout', () => {
    image.style.transform = 'scale(1)';
});

// Agregar un pequeño efecto de movimiento a los botones al hacer clic
buttons.forEach(button => {
    button.addEventListener('click', () => {
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
    });
});

// Animación de aparición (fade-in) al cargar la página
window.addEventListener('load', () => {
    document.querySelector('.header').style.opacity = '1';
    document.querySelector('.main-container').style.opacity = '1';
    document.querySelector('.header').style.transition = 'opacity 1s ease-in-out';
    document.querySelector('.main-container').style.transition = 'opacity 1s ease-in-out';
});
