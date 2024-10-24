<?php
class Conexion {
    private $user = "root";
    private $pass = "";
    private $server = "localhost";
    private $nameDB = "tiendita";
    private $conexion;

    // Método privado para crear la conexión
    private function establecerConexion() {
        try {
            $this->conexion = new PDO("mysql:host={$this->server};dbname={$this->nameDB}", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Conexión fallida: ' . $e->getMessage());
        }
    }

    // Método público para obtener la conexión
    public function obtenerConexion() {
        $this->establecerConexion(); // Establecer conexión al llamar a este método
        return $this->conexion;
    }

    // Método para cerrar la conexión
    public function cerrarConexion() {
        $this->conexion = null;
    }
}

$miConexion = new Conexion();
$conexion = $miConexion->obtenerConexion(); 

// Cerrar la conexión
$miConexion->cerrarConexion();
?>
