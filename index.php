<?php
require_once './app/config/conexion.php'; // Asegúrate de que esto esté correcto

// Inicia la sesión y verifica si el usuario está logueado


$query = "SELECT * FROM t_producto";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suits - Gestión de Productos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="./public/css/dataTable.css">
</head>
<body>
    <!-- Bienvenida y Botones de Editar Usuario y Cerrar Sesión -->
    <div class="container mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5>Bienvenido usuario</h5>
            <div>
                 <button class="btn btn-warning" onclick="window.location.href='editar_usuario.php'">
                         Editar Usuario <i class="fas fa-user-edit"></i>
                </button>
                <button class="btn btn-danger" id="cerrarSesionBtn">
                    Cerrar sesión <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2>Agregar productos</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-2">
                    <div class="card-body bg-light text-dark">
                        <form id="formAgregarProducto">
                            <div class="form-group">
                                <label for="nombre_producto">Nombre del Producto:</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="precio_producto">Precio:</label>
                                <input type="number" class="form-control" id="precio_producto" name="precio_producto" required step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="cantidad_producto">Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad_producto" name="cantidad_producto" required>
                            </div>
                            <button type="submit" class="btn btn-success">Agregar <i class="fas fa-plus"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <h3 class="mt-8">Lista de Productos</h3>
                <table id = "myTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo $row['id_producto']; ?></td>
                                <td><?php echo $row['producto']; ?></td>
                                <td><?php echo $row['precio']; ?></td>
                                <td><?php echo $row['cantidad']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-icon" onclick="abrirModalActualizar(<?php echo $row['id_producto']; ?>, '<?php echo $row['producto']; ?>', <?php echo $row['precio']; ?>, <?php echo $row['cantidad']; ?>)">
                                        <i class="fas fa-edit"></i> Actualizar
                                    </button>
                                    <button class="btn btn-danger btn-icon" onclick="eliminarProducto(<?php echo $row['id_producto']; ?>)">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Actualización de Producto -->
    <div class="modal fade" id="modalActualizar" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalActualizarLabel">Actualizar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formActualizarProducto">
                        <input type="hidden" id="id_producto" name="id_producto">
                        <div class="form-group">
                            <label for="nombre_producto_modal">Nombre del Producto:</label>
                            <input type="text" class="form-control" id="nombre_producto_modal" name="nombre_producto_modal" required>
                        </div>
                        <div class="form-group">
                            <label for="precio_producto_modal">Precio:</label>
                            <input type="number" class="form-control" id="precio_producto_modal" name="precio_producto_modal" required step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="cantidad_producto_modal">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad_producto_modal" name="cantidad_producto_modal" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar Producto <i class="fas fa-save"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./public/js/jquery.js"></script>
    <script src="./public/js/dataTable.js"></script>
    <script src="./public/js/popper.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./public/js/main.js"></script>
    
    
</body>
</html>

