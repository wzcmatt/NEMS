<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$mensaje = "";

// Bloque para eliminar proveedor y sus productos relacionados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    $id = $_POST['id'];

    try {
        $conexion->beginTransaction();

        $sql = "DELETE FROM productos WHERE id_provee = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $sql = "DELETE FROM proveedores WHERE id_provee = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $conexion->commit();
        $mensaje = "Proveedor y productos relacionados eliminados con éxito";
    } catch (Exception $e) {
        $conexion->rollBack();
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Bloque para registrar un nuevo proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    try {
        $sql = "INSERT INTO proveedores (nomb_e, email, tel, direc_e) VALUES (:nombre, :email, :telefono, :direccion)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->execute();

        $mensaje = "Proveedor registrado con éxito";
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Bloque para actualizar un proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    try {
        $sql = "UPDATE proveedores SET nomb_e = :nombre, email = :email, tel = :telefono, direc_e = :direccion WHERE id_provee = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $mensaje = "Proveedor actualizado con éxito";
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultar Proveedores - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="http://localhost/PROYECTOSENANEMS/menuprincipal/registro_proveedores.php">NEMS</a>
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
    
    <h2 class="mt-4" id="consultar">CONSULTAR PROVEEDORES</h2>
    <?php
    $sql = "SELECT * FROM proveedores";
    $stmt = $conexion->query($sql);
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proveedores as $proveedor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($proveedor['id_provee']); ?></td>
                    <td><?php echo htmlspecialchars($proveedor['nomb_e']); ?></td>
                    <td><?php echo htmlspecialchars($proveedor['email']); ?></td>
                    <td><?php echo htmlspecialchars($proveedor['tel']); ?></td>
                    <td><?php echo htmlspecialchars($proveedor['direc_e']); ?></td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($proveedor['id_provee']); ?>">
                            <button type="submit" name="accion" value="eliminar" class="btn btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-update" data-bs-toggle="modal" data-bs-target="#modalActualizar" 
                            data-id="<?php echo htmlspecialchars($proveedor['id_provee']); ?>" 
                            data-nombre="<?php echo htmlspecialchars($proveedor['nomb_e']); ?>" 
                            data-email="<?php echo htmlspecialchars($proveedor['email']); ?>" 
                            data-telefono="<?php echo htmlspecialchars($proveedor['tel']); ?>" 
                            data-direccion="<?php echo htmlspecialchars($proveedor['direc_e']); ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
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
                <h5 class="modal-title" id="modalRegistrarLabel">Registrar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="accion" value="crear">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
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
                <h5 class="modal-title" id="modalActualizarLabel">Actualizar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" id="idActualizar" name="id">
                    <div class="mb-3">
                        <label for="nombreActualizar" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreActualizar" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailActualizar" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailActualizar" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoActualizar" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefonoActualizar" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccionActualizar" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccionActualizar" name="direccion" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var modalActualizar = document.getElementById('modalActualizar');
    modalActualizar.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var email = button.getAttribute('data-email');
        var telefono = button.getAttribute('data-telefono');
        var direccion = button.getAttribute('data-direccion');

        var modal = modalActualizar.querySelector('form');
        modal.querySelector('#idActualizar').value = id;
        modal.querySelector('#nombreActualizar').value = nombre;
        modal.querySelector('#emailActualizar').value = email;
        modal.querySelector('#telefonoActualizar').value = telefono;
        modal.querySelector('#direccionActualizar').value = direccion;
    });
</script>
</body>
</html>