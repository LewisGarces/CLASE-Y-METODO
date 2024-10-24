<?php
require_once '../config/conexion.php';

class Usuarios {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function iniciar_sesion($usuario, $password) {
        $query = "SELECT * FROM t_usuario WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Iniciar sesión
                session_start();
                $_SESSION['user_id'] = $user['id_usuario']; // Guardar el ID del usuario en la sesión
                return json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso.']);
            } else {
                return json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'El usuario no existe.']);
        }
    }
    

    public function registrar_usuario($usuario, $password) {
        // Verificar si el usuario ya existe
        $checkQuery = "SELECT * FROM t_usuario WHERE usuario = :usuario";
        $stmt = $this->conexion->prepare($checkQuery);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return json_encode(['success' => false, 'message' => 'El usuario ya existe.']);
        } else {
            // Encriptar la contraseña
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insertQuery = "INSERT INTO t_usuario (usuario, password) VALUES (:usuario, :password)";
            $stmt = $this->conexion->prepare($insertQuery);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':password', $hashedPassword); // Almacenar la contraseña encriptada

            if ($stmt->execute()) {
                return json_encode(['success' => true, 'message' => 'Registrado correctamente.']);
            } else {
                return json_encode(['success' => false, 'message' => 'Error al registrar.']);
            }
        }
    }

    public function actualizar_usuario($nuevo_usuario, $nueva_password) {
        // Lógica para actualizar el usuario
        $updateQuery = "UPDATE t_usuario SET usuario = :nuevo_usuario, password = :password WHERE id_usuario = :id";
        $stmt = $this->conexion->prepare($updateQuery);
        
        // Asumiendo que el ID del usuario se guarda en la sesión
        $id = $_SESSION['user_id'];
        $hashedPassword = password_hash($nueva_password, PASSWORD_DEFAULT); // Encriptar la nueva contraseña
        
        $stmt->bindParam(':nuevo_usuario', $nuevo_usuario);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return json_encode(['success' => true, 'message' => 'Datos actualizados correctamente.']);
        } else {
            return json_encode(['success' => false, 'message' => 'Error al actualizar los datos.']);
        }
    }
}

// Metodo
$usuarios = new Usuarios($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':
                $usuario = $_POST['usuario'];
                $password = $_POST['password'];
                echo $usuarios->iniciar_sesion($usuario, $password);
                break;
            case 'register':
                $usuario = $_POST['usuario'];
                $password = $_POST['password'];
                echo $usuarios->registrar_usuario($usuario, $password);
                break;
            case 'update':
                $nuevo_usuario = $_POST['usuario'];
                $nueva_password = $_POST['password'];
                echo $usuarios->actualizar_usuario($nuevo_usuario, $nueva_password);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
