<?php
require_once '../config/conexion.php';

class Productos {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para actualizar productos
    public function actualizar_producto($id_producto, $nombre_producto, $precio_producto, $cantidad_producto) {
        $actualizacion = $this->conexion->prepare(
            "UPDATE t_producto SET producto = :producto, precio = :precio, cantidad = :cantidad WHERE id_producto = :id"
        );
        $actualizacion->bindParam(':producto', $nombre_producto);
        $actualizacion->bindParam(':precio', $precio_producto);
        $actualizacion->bindParam(':cantidad', $cantidad_producto);
        $actualizacion->bindParam(':id', $id_producto);

        if ($actualizacion->execute()) {
            return json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
        } else {
            return json_encode(['success' => false, 'message' => 'Error al actualizar el producto.']);
        }
    }

    // Método para obtener todos los productos
    public function obtener_productos() {
        $query = "SELECT * FROM t_producto";
        $stmt = $this->conexion->prepare($query);

        if ($stmt->execute()) {
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode(['success' => true, 'data' => $productos]);
        } else {
            return json_encode(['success' => false, 'message' => 'Error al consultar los productos.']);
        }
    }

    // Método para eliminar un producto
    public function eliminar_producto($id_producto) {
        $eliminacion = $this->conexion->prepare("DELETE FROM t_producto WHERE id_producto = :id_producto");
        $eliminacion->bindParam(':id_producto', $id_producto);

        if ($eliminacion->execute()) {
            return json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
        } else {
            return json_encode(['success' => false, 'message' => 'Error al eliminar el producto.']);
        }
    }

    // Método para agregar un nuevo producto
    public function agregar_producto($nombre_producto, $precio_producto, $cantidad_producto) {
        $insercion = $this->conexion->prepare(
            "INSERT INTO t_producto (producto, precio, cantidad) VALUES (:producto, :precio, :cantidad)"
        );
        $insercion->bindParam(':producto', $nombre_producto);
        $insercion->bindParam(':precio', $precio_producto);
        $insercion->bindParam(':cantidad', $cantidad_producto);

        if ($insercion->execute()) {
            return json_encode(['success' => true, 'message' => 'Producto agregado correctamente.']);
        } else {
            return json_encode(['success' => false, 'message' => 'Error al agregar el producto.']);
        }
    }
}

// Crear una instancia de la clase Productos y manejar las solicitudes HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = new Productos($conexion);

    if (isset($_POST['id_producto'])) {
        // Actualizar producto
        $id_producto = $_POST['id_producto'];
        $nombre_producto = $_POST['nombre_producto_modal'];
        $precio_producto = $_POST['precio_producto_modal'];
        $cantidad_producto = $_POST['cantidad_producto_modal'];
        echo $productos->actualizar_producto($id_producto, $nombre_producto, $precio_producto, $cantidad_producto);
    } elseif (isset($_POST['nombre_producto'])) {
        // Agregar nuevo producto
        $nombre_producto = $_POST['nombre_producto'];
        $precio_producto = $_POST['precio_producto'];
        $cantidad_producto = $_POST['cantidad_producto'];
        echo $productos->agregar_producto($nombre_producto, $precio_producto, $cantidad_producto);
    } else {
        // Eliminar producto
        $data = json_decode(file_get_contents("php://input"));
        $id_producto = $data->id_producto;
        echo $productos->eliminar_producto($id_producto);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productos = new Productos($conexion);
    // Obtener todos los productos
    echo $productos->obtener_productos();
}
?>

