<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Segura - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> <!-- Fuente Poppins -->
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
            <a class="navbar-brand" href="http://localhost/PROYECTOSENANEMS/menuprincipal">NEMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                </ul>
            </div>
        </div>
    </nav>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6">
                <div class="module">
                    <h2 class="mb-3">Bienvenido a NEMS</h2>
                    <p class="mb-4">Crea la factura para tus clientes.</p>
                    <a href="http://localhost/PROYECTOSENANEMS/menuprincipal/facturas/consulfact.php" class="btn btn-custom">
                        <i class="bi bi-person-plus"></i> Crear Factura
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGz5xqQ5vTq6Wzx3f3nkC4ujQSCztzrA5z8B3d9ctfpbx" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-9ndCyUa1llRkU6QzF2paK5czZ2nxSbOG+kF0d5lXaA5/0E5PyXC0KAqvn5KvtQpy" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
</body>
