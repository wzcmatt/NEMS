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
        color: #ffffff;
        font-weight: 500; 
      }
      .navbar .nav-link {
        color: white !important;
      }
      .navbar .nav-link.active {
        color: #e5b80b !important;
      }
      .container {
        margin-top: 50px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="INDEX.php">
          <img src="menuprincipal/USUARIO.png" alt="Foto de perfil">
          <span>NEMS</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="http://localhost/PROYECTOSENANEMS/menuprincipal/home.php"><i class="fas fa-house-user"></i></a> <!-- Icono de casa -->
            </li>
            <li class="nav-item">
              <a class="nav-link" href="salir.php"><i class="fas fa-sign-out-alt"></i></a> <!-- Icono de cerrar sesión -->
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <!-- Aquí va el contenido de la página -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
