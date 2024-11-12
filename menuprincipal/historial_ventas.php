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

$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$registros_por_pagina = isset($_GET['registros_por_pagina']) ? intval($_GET['registros_por_pagina']) : 10;

$offset = ($pagina - 1) * $registros_por_pagina;

$sql = "SELECT f.id_factura, u.nombre AS cliente, f.fecha, f.total 
        FROM facturas f 
        JOIN usuarios u ON f.id_cliente = u.id 
        ORDER BY f.fecha DESC
        LIMIT :limit OFFSET :offset";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':limit', $registros_por_pagina, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_total = "SELECT COUNT(*) FROM facturas";
$stmt_total = $conexion->query($sql_total);
$total_facturas = $stmt_total->fetchColumn();
$total_paginas = ceil($total_facturas / $registros_por_pagina);

if (isset($_GET['factura_id'])) {
    $factura_id = intval($_GET['factura_id']);

    $sql_factura = "SELECT f.id_factura, u.nombre AS cliente, f.fecha, f.total 
                    FROM facturas f 
                    JOIN usuarios u ON f.id_cliente = u.id 
                    WHERE f.id_factura = :id_factura";
    $stmt_factura = $conexion->prepare($sql_factura);
    $stmt_factura->bindParam(':id_factura', $factura_id, PDO::PARAM_INT);
    $stmt_factura->execute();
    $factura = $stmt_factura->fetch(PDO::FETCH_ASSOC);

    $sql_detalles = "SELECT p.nombre, fd.fact_det_cant AS cantidad, p.precio 
                     FROM fact_det_product fd 
                     JOIN productos p ON fd.id_producto = p.id_producto 
                     WHERE fd.id_factura = :id_factura";
    $stmt_detalles = $conexion->prepare($sql_detalles);
    $stmt_detalles->bindParam(':id_factura', $factura_id, PDO::PARAM_INT);
    $stmt_detalles->execute();
    $productos = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'id_factura' => $factura['id_factura'],
        'cliente' => $factura['cliente'],
        'fecha' => $factura['fecha'],
        'total' => (float) $factura['total'],
        'productos' => $productos
    ]);
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial de Ventas - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; font-weight: bold; }
        .navbar { background-color: #0E82b0 !important; }
        .navbar .navbar-brand, .navbar .nav-link { color: white !important; }
        .btn-primary { background-color: #0E82b0; border: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="http://localhost/PROYECTOSENANEMS/menuprincipal/home.php">NEMS</a>
    </nav>
    <div class="container mt-5">
        <h2>Historial de Ventas</h2>
        <table class="table table-striped">
            <thead>
                <tr><th>ID Factura</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?= htmlspecialchars($factura['id_factura']); ?></td>
                        <td><?= htmlspecialchars($factura['cliente']); ?></td>
                        <td><?= htmlspecialchars($factura['fecha']); ?></td>
                        <td>$<?= number_format($factura['total'], 2); ?></td>
                        <td><button class="btn btn-primary" onclick="generarPDF(<?= $factura['id_factura']; ?>)">Generar PDF</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation"><ul class="pagination">
            <li class="page-item <?php if ($pagina <= 1) echo 'disabled'; ?>"><a class="page-link" href="?pagina=<?= max(1, $pagina - 1); ?>&registros_por_pagina=<?= $registros_por_pagina; ?>">Anterior</a></li>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?><li class="page-item <?php if ($pagina == $i) echo 'active'; ?>"><a class="page-link" href="?pagina=<?= $i; ?>&registros_por_pagina=<?= $registros_por_pagina; ?>"><?= $i; ?></a></li><?php endfor; ?>
            <li class="page-item <?php if ($pagina >= $total_paginas) echo 'disabled'; ?>"><a class="page-link" href="?pagina=<?= min($total_paginas, $pagina + 1); ?>&registros_por_pagina=<?= $registros_por_pagina; ?>">Siguiente</a></li>
        </ul></nav>
        <label for="registrosPorPagina" class="form-label">Registros por página:</label>
        <select id="registrosPorPagina" class="form-select" onchange="cambiarRegistrosPorPagina(this.value)">
            <option value="10" <?php if ($registros_por_pagina == 10) echo 'selected'; ?>>10</option>
            <option value="20" <?php if ($registros_por_pagina == 20) echo 'selected'; ?>>20</option>
            <option value="30" <?php if ($registros_por_pagina == 30) echo 'selected'; ?>>30</option>
            <option value="40" <?php if ($registros_por_pagina == 40) echo 'selected'; ?>>40</option>
        </select>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
    async function generarPDF(idFactura) {
        try {
            const response = await fetch(`historial_ventas.php?factura_id=${idFactura}`);
            const data = await response.json();

            if (!data) throw new Error("No data returned from server.");

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            
            doc.setFillColor(14, 130, 176);
            doc.rect(0, 0, 210, 25, 'F');
            doc.setTextColor(255, 255, 255);
            doc.text(`Factura ID: ${data.id_factura}`, 10, 22);
            doc.setTextColor(0, 0, 0);
            
            let yPosition = 40;
            let subtotal = 0;

            doc.text(`Cliente: ${data.cliente}`, 10, yPosition);
            yPosition += 10;
            doc.text(`Fecha: ${data.fecha}`, 10, yPosition);
            yPosition += 10;
            doc.text(`Total: $${data.total.toFixed(2)}`, 10, yPosition);
            yPosition += 20;
            doc.text('Detalles:', 10, yPosition);
            yPosition += 10;

            data.productos.forEach((producto, index) => {
                const cantidadTotal = producto.cantidad * producto.precio;
                doc.text(`${index + 1}. ${producto.nombre} - ${producto.cantidad} x $${producto.precio} = $${cantidadTotal.toFixed(2)}`, 10, yPosition);
                subtotal += cantidadTotal;
                yPosition += 10;
            });

            doc.text(`Subtotal: $${subtotal.toFixed(2)}`, 10, yPosition);
            yPosition += 10;
            const servicio = subtotal * 0.20;
            doc.text(`servicio (20%): $${servicio.toFixed(2)}`, 10, yPosition);
            yPosition += 10;
            const totalConServicio = subtotal + servicio;
            doc.text(`Total con servicio: $${totalConServicio.toFixed(2)}`, 10, yPosition);
            yPosition += 20;
            
            doc.text('NEMS-3219975671', 10, yPosition);
            doc.save(`Factura_${data.id_factura}.pdf`);
        } catch (error) {
            console.error("Error generating PDF:", error);
            alert("Error generating PDF. Please check the console for details.");
        }
    }

    function cambiarRegistrosPorPagina(value) {
        const url = new URL(window.location.href);
        url.searchParams.set("registros_por_pagina", value);
        window.location.href = url.href;
    }
    </script>
</body>
</html>
