<?php
$conexion = null;
$servidor = "localhost";
$bd = "nems";
$user = "root";
$pass = "";

try {
    $conexion = new PDO('mysql:host='.$servidor.';dbname='.$bd, $user, $pass);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] == 'crear_producto') {
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $id_proveedor = $_POST['id_proveedor'];
            $stock = $_POST['stock']; // Nuevo campo de stock

            $sql = "INSERT INTO productos (nombre, precio, id_provee, stock) VALUES (:nombre, :precio, :id_provee, :stock)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':id_provee', $id_proveedor);
            $stmt->bindParam(':stock', $stock); // Vinculando el stock
            
            if ($stmt->execute()) {
                $mensaje = "Nuevo producto registrado con éxito";
            } else {
                $mensaje = "Error al registrar el producto: " . $stmt->errorInfo()[2];
            }
        }
    }
}

// Mensaje de confirmación
if (isset($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Segura - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> <!-- Fuente Poppins -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            height: 100vh;
            font-family: 'Poppins', sans-serif; /* Fuente Poppins */
        }
        .navbar {
            background-color: #0E82b0 !important; /* Color de la navbar */
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: white !important;
        }
        .container {
            margin-top: 50px;
        }
        .form-control {
            border: 1px solid #0E82b0; /* Borde azul */
            background-color: transparent; /* Sin fondo */
            color: black; /* Color del texto */
            height: 38px; /* Altura más pequeña */
        }
        .form-control::placeholder {
            color: #777; /* Color del placeholder */
        }
        .btn-primary {
            background-color: #0E82b0; /* Botón azul */
            border: none; /* Sin borde */
        }
        .btn-primary:hover {
            background-color: #0c6c99; /* Azul más oscuro en hover */
        }
        .btn-delete {
            background-color: transparent;
            border: none;
            color: #0E82b0; /* Color del icono de borrar */
        }
        .btn-delete:hover {
            color: #0c6c99; /* Azul más oscuro en hover */
        }
        .alert {
            border-radius: 15px; /* Bordes redondeados para alertas */
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
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="http://localhost/PROYECTOSENANEMS/menuprincipal/registro_productos.php">NEMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#registrar">Registrar Producto</a></li>
                <li class="nav-item"><a class="nav-link" href="#productos">Lista de Productos</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mt-4" id="registrar">Registrar Producto</h2>
    <?php if ($mensaje) { echo "<div class='alert alert-info'>$mensaje</div>"; } ?>
    <form method="post">
        <input type="hidden" name="accion" value="crear_producto">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="id_proveedor" class="form-label">Proveedor</label>
            <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                <?php
                $sql_proveedores = "SELECT id_provee, nomb_e FROM proveedores";
                $stmt = $conexion->query($sql_proveedores);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['id_provee']}'>{$row['nomb_e']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock Inicial</label>
            <input type="number" class="form-control" id="stock" name="stock" required> <!-- Campo para el stock -->
        </div>
        <button type="submit" class="btn btn-primary">Registrar Producto</button>
    </form>

    <h2 class="mt-4" id="productos">Lista de Productos</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Proveedor</th>
                <th>Stock</th> <!-- Añadido columna de stock -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_productos = "SELECT p.id_producto, p.nombre, p.precio, pr.nomb_e, pr.id_provee, p.stock FROM productos p 
                              INNER JOIN proveedores pr ON p.id_provee = pr.id_provee";
            $result_productos = $conexion->query($sql_productos);

            if ($result_productos->rowCount() > 0) {
                while($row = $result_productos->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['id_producto']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['precio']}</td>
                            <td>{$row['nomb_e']}</td>
                            <td>{$row['stock']}</td> <!-- Muestra el stock -->
                            <td>
                                <button class='btn-delete'><i class='fas fa-trash-alt'></i></button>
                            </td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QppDGmbxjqnpHdPxQpV8LQ09sRm5THan9fOVZtAEdAwBKTiVoJrPiAq6MGXvN5g8" crossorigin="anonymous"></script>
</body>
</html>
