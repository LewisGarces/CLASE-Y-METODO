document.getElementById('editarUsuarioForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const usuario = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value.trim();

    // Validación de campos vacíos
    if (usuario === '' || password === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, completa todos los campos.'
        });
        return; // Detener el envío del formulario
    }

    const formData = new FormData(this);
    const response = await fetch('editar_usuario.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (result.status === 'success') {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: result.message
        }).then(() => {
            window.location.href = 'login.php'; // Redirige al login después de actualizar
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: result.message
        });
    }
});