

function soloLetras(event) {
    var charCode = event.keyCode;
    if (charCode != 8 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode != 32) {
        event.preventDefault();
        return false;
    }
    return true;
}

function soloNumeros(event) {
    var charCode = event.keyCode;
    var inputField = event.target || event.srcElement;
    if (inputField.value.length >= inputField.maxLength || charCode < 48 || charCode > 57) {
        event.preventDefault();
        return false;
    }
    return true;
}

function validarFormulario() {
    var nombre = document.getElementsByName('nombre')[0].value;
    var colonia = document.getElementsByName('colonia')[0].value;
    var calle = document.getElementsByName('calle')[0].value;
    var telefono = document.getElementsByName('telefono')[0].value;
    var codigoPostal = document.getElementsByName('codigopostal')[0].value;
    var email = document.getElementsByName('email')[0].value;

    // Validar el nombre: al menos 3 palabras
    if (!nombre.match(/^\S+(\s+\S+){2,}$/)) {
        alert("Por favor, introduce un nombre con al menos 3 palabras.");
        return false;
    }

    // Validar la colonia: al menos 6 palabras
    if (!colonia.match(/^\S+(\s+\S+){5,}$/)) {
        alert("Por favor, introduce una colonia válida con al menos 6 palabras.");
        return false;
    }

    // Validar que la calle tenga al menos una palabra de 6 caracteres
    if (!calle.match(/^\S{6,}$/)) {
        alert("Por favor, introduce una calle válida con al menos una palabra de 6 caracteres.");
        return false;
    }

    // Validar el teléfono: exactamente 10 dígitos y solo números
    if (telefono.length !== 10 || isNaN(telefono)) {
        alert("El teléfono debe tener 10 dígitos y solo puede contener números.");
        return false;
    }

    // Validar el código postal: exactamente 5 dígitos y solo números
    if (codigoPostal.length !== 5 || isNaN(codigoPostal)) {
        alert("El código postal debe tener 5 dígitos y solo puede contener números.");
        return false;
    }

    // Validar el email: al menos un arroba
    if (!email.includes('@')) {
        alert("Por favor, introduce un correo electrónico válido con al menos un arroba (@).");
        return false;
    }

    return true;
}

