document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // No recargar página
        
        let formData = new FormData(form);
        
        fetch('registro.php', {
            method: 'POST',
            body: formData
        })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.estado === 'exito') {
                alert(datos.mensaje);
                form.reset();
                window.location.href = 'login.html'; // Ir al login
            } else {
                alert(datos.mensaje);
            }
        })
        .catch(error => {
            alert("Error en la conexión con el servidor");
            console.error(error);
        });
    });
});