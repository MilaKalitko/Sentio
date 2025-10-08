document.addEventListener("DOMContentLoaded", () => {
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
    const tituloEmocion = document.querySelector('.emocion_diaria h1');
    if (munecoDiario && opcionesEmociones.length > 0 && tituloEmocion) {
        opcionesEmociones.forEach(carita => {
            carita.addEventListener('click', () => {
                const emocionSeleccionada = carita.dataset.emocion;
                const nuevaImagenSrc = `./assets/munequito-${emocionSeleccionada}.png`;
                
                // Capitalizar la primera letra de la emoción
                const emocionCapitalizada = emocionSeleccionada.charAt(0).toUpperCase() + emocionSeleccionada.slice(1);
                
                munecoDiario.src = nuevaImagenSrc;
                tituloEmocion.textContent = `Hoy te sentiste ${emocionCapitalizada}`;
            });
        });
    }

    const formLogin = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    if (formLogin && formRegister && showRegisterLink && showLoginLink) {
        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            formLogin.classList.add('form-oculto');
            formRegister.classList.remove('form-oculto');
        });
        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            formRegister.classList.add('form-oculto');
            formLogin.classList.remove('form-oculto');
        });
    }

    const postsGrid = document.querySelector(".posts-grid");
    if (postsGrid && typeof postsData !== 'undefined') {
        postsData.forEach(post => {
            const card = document.createElement("article");
            card.className = "post-card";
            card.innerHTML = `
                <img src="${post.image}" alt="Imagen de post" class="post-image">
                <div class="post-content">
                    <p class="post-date">${post.subtitle}</p>
                    <p class="post-description">${post.description}</p>
                    <a href="entrada_blog.html?id=${post.id}" class="btn-ver-mas">Ver más</a>
                </div>
            `;
            postsGrid.appendChild(card);
        });

        const postsSection = document.querySelector(".posts-section");
        if (postsSection) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        postsSection.classList.add('scrolled');
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(postsSection);
        }
    }

    const postTitle = document.getElementById('post-title');
    if (postTitle && typeof postsData !== 'undefined') {
        const urlParams = new URLSearchParams(window.location.search);
        const postId = urlParams.get('id');
        const post = postsData.find(p => p.id == postId);

        if (post) {
            document.title = `${post.title} - Sentio`;
            postTitle.textContent = post.title;
            document.getElementById('post-image').src = post.image;
            document.getElementById('post-image').alt = post.title;
            document.getElementById('post-subtitle').textContent = post.subtitle;
            document.getElementById('post-text').textContent = post.fullText;
        } else {
            postTitle.textContent = "Entrada no encontrada";
            document.querySelector('.blog-post-card').innerHTML += "<p>El artículo que buscas no existe.</p>";
        }
    }

});