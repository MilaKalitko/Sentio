document.addEventListener("DOMContentLoaded", () => {

    const postsGrid = document.querySelector(".posts-grid");

    if (postsGrid && typeof postsData !== 'undefined') {
        postsData.slice(0, 3).forEach(post => {
            const card = document.createElement("article");
            card.className = "post-card";
            card.innerHTML = `
                <img src="${post.image}" alt="Imagen de post" class="post-image">
                <div class="post-content">
                    <p class="post-date">${post.date}</p>
                    <p class="post-description">${post.description}</p>
                    <a href="${post.link}" class="btn-ver-mas">Ver más</a>
                </div>
            `;
            postsGrid.appendChild(card);
        });
    }

    const postsSection = document.getElementById("posts");

    if (postsSection) {
        window.addEventListener('scroll', () => {
            const sectionTop = postsSection.getBoundingClientRect().top;
            const triggerPoint = window.innerHeight * 0.8; 

            if (sectionTop < triggerPoint) {
                postsSection.classList.add('scrolled');
            } else {
                postsSection.classList.remove('scrolled');
            }
        });
    }
    
    const burgerButton = document.getElementById('burger-button');
    const navMenu = document.getElementById('nav-menu');

    if (burgerButton && navMenu) {
        burgerButton.addEventListener('click', () => {
            // Alterna la clase 'active' en el botón (para la animación de X)
            // y en el menú (para mostrarlo u ocultarlo)
            burgerButton.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
});