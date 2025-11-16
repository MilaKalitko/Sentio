document.addEventListener("DOMContentLoaded", () => {
    // 1. Lógica del Menú Hamburguesa 
    const burgerButton = document.getElementById('burger-button');
    const navMenu = document.getElementById('nav-menu');
    if (burgerButton && navMenu) {
        burgerButton.addEventListener('click', () => {
            burgerButton.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }

    // 2. Lógica del Selector de Emociones DINÁMICA (Registro Diario)
    
    if (typeof usuarioLogueado !== "undefined") {
        const munecoDiario = document.getElementById('muneco-diario');
        const opcionesEmociones = document.querySelectorAll('.opciones_emociones img.carita-opcion'); 
        const tituloEmocion = document.querySelector('.emocion_diaria h1');
        const emocionSeleccionadaInput = document.getElementById('emocion-seleccionada-id');
        const btnRegistrar = document.getElementById('btn-registrar-emocion');
        const emocionDiariaBox = document.getElementById('emocion-diaria-box');
        if (munecoDiario && opcionesEmociones.length > 0 && tituloEmocion && btnRegistrar) {
            

            if (!usuarioLogueado) {
                btnRegistrar.disabled = true;
                btnRegistrar.textContent = 'Inicia sesión para registrar';
            }

            opcionesEmociones.forEach(carita => {
                carita.addEventListener('click', () => {
                    
                    const emocionId = carita.dataset.id;
                    const emocionNombre = carita.dataset.emocion;
                    const munecoSrc = carita.dataset.munecoSrc;
                    const emocionColor = carita.dataset.color;
                    
                    opcionesEmociones.forEach(img => img.classList.remove('selected'));
                    carita.classList.add('selected');
                    
                    munecoDiario.src = munecoSrc;
                    
                    const emocionCapitalizada = emocionNombre.charAt(0).toUpperCase() + emocionNombre.slice(1);
                    tituloEmocion.textContent = `Hoy te sientes ${emocionCapitalizada}`;
                    emocionDiariaBox.style.backgroundColor = emocionColor + '30';

                    emocionSeleccionadaInput.value = emocionId;
                    
                    if (usuarioLogueado) {
                        btnRegistrar.disabled = false;
                        btnRegistrar.textContent = 'Registrar emoción';
                    }
                });
            });
        }
    }


    // 3. Lógica de Cambio de Formularios 
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

    // 4. Lógica de Posts y Blogs 
    if (typeof postsData !== 'undefined') {
        
        const postsGrid = document.querySelector(".posts-grid");
        if (postsGrid) {
            
            // Generación de tarjetas en la página Herramientas
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
                            observer.unobserve(postsSection); 
                        }
                    });
                }, { threshold: 0.1 });
                observer.observe(postsSection);
            }
        }
        
        // Lógica de carga de contenido en la página de Entrada de Blog
        const postTitle = document.getElementById('post-title');
        if (postTitle) {
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
                const blogCard = document.querySelector('.blog-post-card');
                if(blogCard) blogCard.innerHTML += "<p>El artículo que buscas no existe.</p>";
            }
        }
    }
    // 5. Lógica del Modal de Mensajes
    if (typeof mensajePHP !== "undefined") {
        const modalMensaje = document.getElementById('modal-mensaje');
        const modalTitulo = document.getElementById('modal-titulo');
        const modalTexto = document.getElementById('modal-texto');
        const modalCerrarBtn = document.getElementById('modal-cerrar');
        const modalContenido = modalMensaje ? modalMensaje.querySelector('.modal-contenido') : null;

        if (modalMensaje && modalTitulo && modalTexto && modalCerrarBtn && modalContenido) {
            if (mensajePHP && mensajePHP.texto) {
                modalTitulo.textContent = (mensajePHP.tipo === 'success') ? '¡Éxito!' : '¡Error!';
                modalTexto.textContent = mensajePHP.texto;
                modalContenido.classList.add(mensajePHP.tipo);
                modalMensaje.classList.remove('modal-oculto');
                modalMensaje.classList.add('modal-visible');

                modalCerrarBtn.addEventListener('click', () => {
                    modalMensaje.classList.remove('modal-visible');
                    modalMensaje.classList.add('modal-oculto');
                    modalContenido.classList.remove('success', 'error');
                });

                window.addEventListener('click', (event) => {
                    if (event.target === modalMensaje) {
                        modalMensaje.classList.remove('modal-visible');
                        modalMensaje.classList.add('modal-oculto');
                        modalContenido.classList.remove('success', 'error');
                    }
                });
            }
        }
    }
});