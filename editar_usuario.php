<?php
require_once './app/config/conexion.php'; // Conexión a la base de datos
session_start();

class User {
    private $conexion;
    private $id_usuario;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->id_usuario = $_SESSION['usuario'] ?? null;

        // Verifica si el usuario está logueado
        if (!$this->id_usuario) {
            header('Location: login.php');
            exit();
        }
    }

    public function getCurrentUser() {
        $sql = "SELECT usuario FROM t_usuario WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($nuevo_usuario, $nueva_password) {
        $sql_update = "UPDATE t_usuario SET usuario = :nuevo_usuario, password = :nueva_password WHERE id_usuario = :id_usuario";
        $stmt_update = $this->conexion->prepare($sql_update);
        $stmt_update->bindParam(':nuevo_usuario', $nuevo_usuario);
        $stmt_update->bindParam(':nueva_password', $nueva_password);
        $stmt_update->bindParam(':id_usuario', $this->id_usuario);

        return $stmt_update->execute();
    }
}

// Crear una instancia de la clase User
$user = new User($conexion);

// Obtener los datos actuales del usuario
$usuario_actual = $user->getCurrentUser();

// Verifica si se ha enviado la solicitud de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_usuario = $_POST['usuario'];
    $nueva_password = $_POST['password'];

    // Actualiza el usuario en la base de datos
    if ($user->updateUser($nuevo_usuario, $nueva_password)) {
        // Devolver respuesta JSON
        echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado correctamente.']);
        session_destroy(); // Destruye la sesión actual
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el usuario.']);
    }
    exit(); // Asegúrate de detener la ejecución después de la respuesta JSON
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <form id="editarUsuarioForm">
            <div class="form-group">
                <label for="usuario">Nuevo Usuario:</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario_actual['usuario']); ?>">
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
        <button class="btn btn-secondary mt-3" onclick="window.location.href='index.php'">Volver</button>
    </div>

    <script src="./public/js/editar.js"></script>
</body>
</html>

