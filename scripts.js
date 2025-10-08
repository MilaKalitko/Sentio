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
            burgerButton.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }

    const munecoDiario = document.getElementById('muneco-diario');
    const opcionesEmociones = document.querySelectorAll('.opciones_emociones img');
    // 1. NUEVA LÍNEA: Seleccionamos el título que vamos a cambiar.
    const tituloEmocion = document.querySelector('.emocion_diaria h1');

    if (munecoDiario && opcionesEmociones.length > 0 && tituloEmocion) {
        
        opcionesEmociones.forEach(carita => {
            
            carita.addEventListener('click', () => {
                
                const emocionSeleccionada = carita.dataset.emocion;
                const nuevaImagenSrc = `/assets/munequito-${emocionSeleccionada}.png`;
                
                munecoDiario.src = nuevaImagenSrc;

                tituloEmocion.textContent = `Hoy te sentiste ${emocionSeleccionada}`;
            });
        });
    }
    
    const formLogin = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');

    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault(); 
            formLogin.classList.add('form-oculto');
            formRegister.classList.remove('form-oculto');
        });
    }

    if (showLoginLink) {
        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault(); 
            formRegister.classList.add('form-oculto');
            formLogin.classList.remove('form-oculto');
        });
    }

});

