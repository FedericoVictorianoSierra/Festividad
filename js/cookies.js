document.addEventListener("DOMContentLoaded", function() {
    // Mostrar el banner con una animación
    setTimeout(function() {
        document.getElementById("cookie-banner").classList.remove("hidden");
    }, 1000);
});

function acceptCookies() {
    document.getElementById("cookie-banner").classList.add("hidden");
    // Establecer la cookie de consentimiento aquí
    document.cookie = "cookie_consent=true; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
}
