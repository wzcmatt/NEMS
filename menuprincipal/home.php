<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Segura - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
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
      .navbar .navbar-brand {
        display: flex;
        align-items: center;
        margin-left: auto; 
      }
      .navbar .navbar-brand img {
        width: 40px;
        height: 40px;
        object-fit: cover; 
        border-radius: 50%; 
        margin-right: 10px; 
      }
      .navbar .navbar-brand span {
        color: white; 
        font-weight: 500; 
      }
      .navbar .nav-link {
        color: white !important;
      }
      .container {
        margin-top: 50px;
      }
      .card {
        background-color: #0E82b0;
        color: white;
        border: none;
        border-radius: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .card:hover {
        background-color: #0d6efd;
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      }
      .card-title {
        color: white;
        font-weight: 600;
      }
      .btn-light {
        background-color: white;
        color: #0E82b0;
        border: none;
        border-radius: 20px;
      }
      .btn-light:hover {
        background-color: #e3e3e3;
        color: #0E82b0;
      }
      .navbar-toggler {
        border-color: white;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="http://localhost/PROYECTOSENANEMS/Pagina_segura.php">
          <img src="USUARIO.png" alt="Foto de perfil">
          <span>NEMS</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="http://localhost/PROYECTOSENANEMS/salir.php"><i class="fas fa-sign-out-alt"></i></a>
            </li>
          
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <div class="col">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5 class="card-title">Generar Facturas</h5>
              <a href="generar_facturas.php" class="btn btn-light">Abrir</a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5 class="card-title">Registro de Productos</h5>
              <a href="registro_productos.php" class="btn btn-light">Abrir</a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5 class="card-title">Gestión de Clientes</h5>
              <a href="gestion_clientes.php" class="btn btn-light">Abrir</a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5 class="card-title">Registro de Proveedores</h5>
              <a href="registro_proveedores.php" class="btn btn-light">Abrir</a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5 class="card-title">Total Diario</h5>
              <a href="control_existencias.php" class="btn btn-light">Abrir</a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5 class="card-title">Historial de Ventas</h5>
              <a href="historial_ventas.php" class="btn btn-light">Abrir</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
