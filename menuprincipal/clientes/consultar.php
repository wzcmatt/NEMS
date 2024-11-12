<?php
// Conectar a la base de datos
$conexion = null;
$servidor = "localhost";
$bd = "nems";
$user = "root";
$pass = "";
try {
    $conexion = new PDO('mysql:host='.$servidor.';dbname='.$bd, $user, $pass);
} catch (PDOException $e) {
    echo "Error de conexión!";
    exit;
}

// Mensaje
$mensaje = "";

// Crear cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $documento = $_POST['documento'];

    $sql = "INSERT INTO usuarios (nombre, email, telefono, documento) VALUES (:nombre, :email, :telefono, :documento)";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':documento', $documento);

    if ($stmt->execute()) {
        $mensaje = "Nuevo cliente registrado con éxito";
    } else {
        $mensaje = "Error: " . $stmt->errorInfo()[2];
    }
}

// Actualizar cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $documento = $_POST['documento'];

    $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono, documento = :documento WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':documento', $documento);

    if ($stmt->execute()) {
        $mensaje = "Cliente actualizado con éxito";
    } else {
        $mensaje = "Error: " . $stmt->errorInfo()[2];
    }
}

// Eliminar cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    $id = (int)$_POST['id'];

    if ($id) {
        try {
            // Eliminar facturas asociadas al cliente
            $sql = "DELETE FROM facturas WHERE id_cliente = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Eliminar cliente
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $mensaje = "Cliente y sus facturas asociadas eliminados con éxito";
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar cliente: " . $e->getMessage();
        }
    } else {
        $mensaje = "ID de cliente no válido.";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultar Clientes - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
         body {
            background-color: #f8f9fa;
            margin: 0;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #0E82b0 !important;
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: white !important;
        }
        .container {
            margin-top: 50px;
        }
        .form-control {
            border: 1px solid #0E82b0;
            background-color: transparent;
            color: black;
            height: 38px;
        }
        .form-control::placeholder {
            color: #777;
        }
        .btn-primary {
            background-color: #0E82b0;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0c6c99;
        }
        .btn-delete, .btn-update {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-delete:hover {
            color: #c82333;
        }
        .btn-update:hover {
            color: #0c6c99;
        }
        .btn-delete:active, .btn-update:active {
            transform: scale(0.95);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .alert {
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .module {
            text-align: center;
            padding: 30px;
            border: 1px solid #0E82b0;
            border-radius: 20px;
            background-color: #0E82b0;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .module:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .btn-custom {
            background-color: white;
            color: #0E82b0;
            border: 1px solid #0E82b0;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: 600;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #b0d4f0;
            color: #0E82b0;
            border-color: #0E82b0;
        }
        .modal-content {
            border-radius: 15px;
            border: 1px solid #0E82b0;
        }
        .modal-header {
            background-color: #0E82b0;
            color: white;
            border-bottom: 1px solid #0E82b0;
        }
        .modal-footer {
            border-top: 1px solid #0E82b0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="http://localhost/PROYECTOSENANEMS/menuprincipal/gestion_clientes.php">NEMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#registrar" data-bs-toggle="modal" data-bs-target="#modalRegistrar">Registrar</a></li>
                <li class="nav-item"><a class="nav-link" href="#consultar">Consultar</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <?php if ($mensaje) { echo "<div class='alert alert-info'>$mensaje</div>"; } ?>
    
    <h2 class="mt-4" id="consultar">CONSULTAR CLIENTES</h2>
    <?php
    $sql = "SELECT * FROM usuarios";
    $stmt = $conexion->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Documento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['documento']); ?></td>
                    <td>
                        <button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#modalActualizar" data-id="<?php echo $usuario['id']; ?>" data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>" data-email="<?php echo htmlspecialchars($usuario['email']); ?>" data-telefono="<?php echo htmlspecialchars($usuario['telefono']); ?>" data-documento="<?php echo htmlspecialchars($usuario['documento']); ?>">Actualizar</button>
                        <button class="btn btn-danger btn-delete" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-id="<?php echo $usuario['id']; ?>">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Registrar -->
<div class="modal fade" id="modalRegistrar" tabindex="-1" aria-labelledby="modalRegistrarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarLabel">Registrar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required>
                    </div>
                    <div class="form-group">
                        <label for="documento">Documento</label>
                        <input type="text" name="documento" id="documento" class="form-control" placeholder="Documento" required>
                    </div>
                    <input type="hidden" name="accion" value="crear">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Actualizar -->
<div class="modal fade" id="modalActualizar" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarLabel">Actualizar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="documento">Documento</label>
                        <input type="text" name="documento" id="documento" class="form-control" required>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="accion" value="actualizar">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que quieres eliminar este cliente y sus facturas asociadas?
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" id="idEliminar">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.btn-update').forEach(function(button) {
        button.addEventListener('click', function() {
            var modal = document.getElementById('modalActualizar');
            modal.querySelector('#id').value = this.getAttribute('data-id');
            modal.querySelector('#nombre').value = this.getAttribute('data-nombre');
            modal.querySelector('#email').value = this.getAttribute('data-email');
            modal.querySelector('#telefono').value = this.getAttribute('data-telefono');
            modal.querySelector('#documento').value = this.getAttribute('data-documento');
        });
    });

    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
            var modal = document.getElementById('modalEliminar');
            modal.querySelector('#idEliminar').value = this.getAttribute('data-id');
        });
    });
</script>
</body>
</html>
