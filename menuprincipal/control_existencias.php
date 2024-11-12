<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "nems");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para obtener el total diario
$sql = "SELECT DATE(fecha) AS fecha, SUM(total) AS total_diario
        FROM facturas
        GROUP BY DATE(fecha)
        ORDER BY DATE(fecha) DESC";

$resultado = $conexion->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Diario - NEMS</title>
    <!-- Fuente Poppins desde Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #0E82b0;
        }
        .navbar a {
            color: white !important;
        }
        .table {
            margin-top: 30px;
        }
        .table th, .table td {
            text-align: center;
        }
        .container {
            margin-top: 50px;
        }
        .total-diario {
            font-size: 1.2rem;
            font-weight: 600;
        }
        .footer-text {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/PROYECTOSENANEMS/menuprincipal/home.php">NEMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center my-4">Total Diario de Ventas</h1>

        <!-- Mostrar los resultados -->
        <?php
        if ($resultado->num_rows > 0) {
            echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Total Diario</th>
                        </tr>
                    </thead>
                    <tbody>";

            while($row = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["fecha"] . "</td>
                        <td class='total-diario'>" . number_format($row["total_diario"], 2) . "</td>
                      </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p class='text-center'>No hay ventas registradas.</p>";
        }

        // Cerrar la conexión
        $conexion->close();
        ?>

    </div>

    <!-- Footer -->
    <footer class="footer text-center py-3">
        <p class="footer-text">NEMS - MATHIUS RUIZ</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
