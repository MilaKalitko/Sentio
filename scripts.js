// scripts.js (versión segura para múltiples páginas)

document.addEventListener("DOMContentLoaded", () => {
    
    // --- Lógica de Posts (solo se ejecuta si existe .posts-grid) ---
    const postsGrid = document.querySelector(".posts-grid");
    if (postsGrid) {
        // Asumiendo que 'postsData' viene de otro archivo de script
        postsData.slice(0, 3).forEach(post => {
            const card = document.createElement("article");
            card.className = "post-card";
            card.innerHTML = `...`; // Tu contenido de la tarjeta va aquí
            postsGrid.appendChild(card);
        });
    }
    
    // --- Lógica de Animación (solo se ejecuta si existe #posts) ---
    const postsSection = document.getElementById("posts");
    if (postsSection) {
        window.addEventListener('scroll', () => {
            // Tu código de animación de scroll
        });
    }

    // --- Funcionalidad para el menú hamburguesa (esto funcionará en todas las páginas) ---
    const burgerButton = document.getElementById('burger-button');
    const navMenu = document.getElementById('nav-menu');

    if (burgerButton && navMenu) {
        burgerButton.addEventListener('click', () => {
            burgerButton.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
});