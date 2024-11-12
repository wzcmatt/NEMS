<?php
session_start();

// Verificar si el usuario no está autenticado

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Segura - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      body {
        background-color: #f8f9fa;
        margin: 0;
        height: 100vh;
      }
      .navbar {
        background-color: #e5b80b !important;
      }
      .navbar .navbar-brand,
      .navbar .nav-link {
        color: white !important;
      }
      .container {
        margin-top: 50px;
      }
    </style>
</head>
<body>
    <?php
    include("menu.php");
    ?>

    <div class="container">
      <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuarios']); ?>!</h1>
      <p>Esta es una página segura. Solo los usuarios autenticados pueden verla.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
