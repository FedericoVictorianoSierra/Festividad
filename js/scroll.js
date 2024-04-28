
    // función que recibe el id de la categoría y enfoca hacia ella con una animación suave
    function enfocarCategoria(idcategoria) {
        // obtiene el elemento HTML de la categoría por su id
        var el = document.getElementById("categoria-" + idcategoria);
        // aplica el efecto de animación de desplazamiento suave al elemento HTML
        el.scrollIntoView({
            behavior: 'smooth',
            block: 'start' // define la posición del elemento de referencia (el inicio del elemento enfocado) en relación con la ventana de visualización
        });
    }

    // Función para guardar la posición del scroll
    function guardarPosicionScroll() {
        localStorage.setItem('posicionScroll', window.pageYOffset);
    }

    // Función para restaurar la posición del scroll
    function restaurarPosicionScroll() {
        var posicionScroll = localStorage.getItem('posicionScroll');
        if (posicionScroll) {
            window.scrollTo(0, posicionScroll);
            localStorage.removeItem('posicionScroll'); // Elimina la posición guardada para que no se restaure cada vez
        }
    }

    // Guardar la posición del scroll cuando la página se esté cerrando o al realizar un evento de scroll
    window.addEventListener('beforeunload', guardarPosicionScroll);
    window.addEventListener('scroll', guardarPosicionScroll);

    // Restaurar la posición del scroll cuando la página se carga
    window.addEventListener('load', restaurarPosicionScroll);
