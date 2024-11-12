<?php
// Conexión a la base de datos
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

$mensaje = '';
$clientes = [];
$clienteSeleccionado = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['accion'] == 'buscar_cliente') {
        $documento = $_POST['documento'];
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE documento LIKE :documento");
        $stmt->bindValue(':documento', '%' . $documento . '%');
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clientes)) {
            $mensaje = "No se encontraron clientes con ese número de documento.";
        }
    } elseif ($_POST['accion'] == 'seleccionar_cliente') {
        $id_cliente = $_POST['id_cliente'];
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = :id_cliente");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        $clienteSeleccionado = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($_POST['accion'] == 'crear_factura') {
        $id_cliente = $_POST['id_cliente'];
        $descuento = $_POST['descuento'] ?: 0;
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidades'];

        $stmt = $conexion->prepare("INSERT INTO facturas (id_cliente, fecha, total) VALUES (:id_cliente, NOW(), 0)");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        $id_factura = $conexion->lastInsertId();

        $total = 0;
        for ($i = 0; $i < count($productos); $i++) {
            $id_producto = $productos[$i];
            $cantidad = $cantidades[$i];

            $stmt = $conexion->prepare("SELECT precio FROM productos WHERE id_producto = :id_producto");
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->execute();
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            $subtotal = $producto['precio'] * $cantidad;
            $total += $subtotal;

            $stmt = $conexion->prepare("INSERT INTO fact_det_product (id_factura, id_producto, fact_det_cant) VALUES (:id_factura, :id_producto, :cantidad)");
            $stmt->bindParam(':id_factura', $id_factura);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->execute();
        }

        $total -= ($total * ($descuento / 100));

        $stmt = $conexion->prepare("UPDATE facturas SET total = :total WHERE id_factura = :id_factura");
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':id_factura', $id_factura);
        $stmt->execute();

        $mensaje = "Factura creada con éxito.";
        $clienteSeleccionado = null;
    }
}

// Obteniendo lista de productos para usarlos en JavaScript
$productosOpciones = [];
$stmt = $conexion->query("SELECT id_producto, nombre FROM productos");
while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $productosOpciones[] = $producto;
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generar Factura - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .navbar { background-color: #0E82b0 !important; }
        .navbar-brand, .navbar-dark .navbar-nav .nav-link { color: white !important; }
        .container { margin-top: 50px; }
        .list-group-item:hover { background-color: #0E82b0; }
        .form-control, .btn-primary { border-color: #0E82b0; }
        .btn-primary { background-color: #0E82b0; }
        .alert { margin-top: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">NEMS</a>
    </nav>

    <div class="container">
        <h2>Generar Factura</h2>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="accion" value="buscar_cliente">
            <div class="mb-3">
                <label for="documento" class="form-label">Número de Documento del Cliente</label>
                <input type="text" class="form-control" id="documento" name="documento" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Cliente</button>
        </form>

        <?php if (!empty($clientes)): ?>
            <h4 class="mt-4">Clientes Encontrados:</h4>
            <div class="list-group">
                <?php foreach ($clientes as $cliente): ?>
                    <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#modalCliente<?php echo $cliente['id']; ?>">
                        <?php echo htmlspecialchars($cliente['nombre']); ?> - Documento: <?php echo htmlspecialchars($cliente['documento']); ?>
                    </a>

                    <div class="modal fade" id="modalCliente<?php echo $cliente['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Datos del Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($cliente['nombre']); ?></p>
                                    <p><strong>Documento:</strong> <?php echo htmlspecialchars($cliente['documento']); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <form action="" method="post">
                                        <input type="hidden" name="accion" value="seleccionar_cliente">
                                        <input type="hidden" name="id_cliente" value="<?php echo $cliente['id']; ?>">
                                        <button type="submit" class="btn btn-primary">Seleccionar Cliente</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($clienteSeleccionado): ?>
            <h4 class="mt-4">Cliente Seleccionado:</h4>
            <p>Nombre: <?php echo htmlspecialchars($clienteSeleccionado['nombre']); ?></p>

            <form action="" method="post">
                <input type="hidden" name="accion" value="crear_factura">
                <input type="hidden" name="id_cliente" value="<?php echo $clienteSeleccionado['id']; ?>">
                
                <div id="productos-container">
                    <div class="product-item mb-3">
                        <label>Producto:</label>
                        <select class="form-control" name="productos[]" required>
                            <?php foreach ($productosOpciones as $producto): ?>
                                <option value="<?php echo $producto['id_producto']; ?>"><?php echo htmlspecialchars($producto['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Cantidad:</label>
                        <input type="number" class="form-control" name="cantidades[]" min="1" required>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mb-3" onclick="agregarProducto()">Agregar Producto</button>

                <div class="mb-3">
                    <label>Descuento (%):</label>
                    <input type="number" class="form-control" name="descuento" min="0" max="100">
                </div>

                <button type="submit" class="btn btn-primary">Crear Factura</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const productosOpciones = `<?php foreach ($productosOpciones as $producto): ?><option value="<?php echo $producto['id_producto']; ?>"><?php echo htmlspecialchars($producto['nombre']); ?></option><?php endforeach; ?>`;

        function agregarProducto() {
            const container = document.getElementById('productos-container');
            const productItem = document.createElement('div');
            productItem.className = 'product-item mb-3';

            productItem.innerHTML = `
                <label>Producto:</label>
                <select class="form-control" name="productos[]" required>${productosOpciones}</select>
                <label>Cantidad:</label>
                <input type="number" class="form-control" name="cantidades[]" min="1" required>
                <button type="button" class="btn btn-danger mt-2" onclick="eliminarProducto(this)">Eliminar Producto</button>
            `;
            container.appendChild(productItem);
        }

        function eliminarProducto(button) {
            const container = document.getElementById('productos-container');
            if (container.children.length > 1) {
                button.closest('.product-item').remove();
            } else {
                alert("Debe haber al menos un producto.");
            }
        }
    </script>
</body>
</html>
