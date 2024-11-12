<?php
session_start();

if ($_POST) {
    try {
        require('CONEXION.PHP');
        $u = $_POST['t1'];
        $p = $_POST['t2'];

        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $conexion->prepare("SELECT * FROM login WHERE usuarios = :u AND password = :p");
        $query->bindParam(":u", $u);
        $query->bindParam(":p", $p);
        $query->execute();
        $usuario = $query->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['usuarios'] = $usuario["usuarios"];
            header('Location: Pagina_segura.php');
            exit(); // Asegúrate de detener la ejecución después de la redirección
        } else {
            echo "<script>alert('Usuario o contraseña inválidos');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - NEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
      body {
        background-image: url('fondo.jpg');
        background-size: cover; 
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center center; /* Centers the image */
        color: white;
        margin: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
      }
      .navbar {
        background-color: #0E82b0 !important; 
        width: 100%;
      }
      .navbar .navbar-brand,
      .navbar .nav-link {
        color: white !important;
      }
      .navbar .nav-link.disabled {
        color: rgba(255, 255, 255, 0.5) !important;
      }
      .login-container {
        position: relative;
        padding: 20px;
        width: 300px;
        margin-top: auto;
        margin-bottom: auto;
        text-align: center;
        background: transparent; /* Remove background color */
      }
      .profile-pic {
        width: 120px; /* Increased size */
        height: 120px; /* Increased size */
        border-radius: 50%;
        border: 5px solid #0E82b0; /* Updated border color */
        background-size: cover;
        background-position: center;
        background-image: url('https://e7.pngegg.com/pngimages/81/570/png-clipart-profile-logo-computer-icons-user-user-blue-heroes-thumbnail.png'); /* Replace with your image URL */
        position: absolute;
        top: -105px; /* Half of the profile picture's height */
        left: 50%;
        transform: translateX(-50%);
      }
      .login-container .form-label, 
      .login-container .form-control {
        color: white;
      }
      .login-container .form-control {
        background-color: #0E82b0; /* Updated background color */
        border: none; /* Remove default border */
        border-radius: 20px; /* Oval shape */
        padding: 10px 20px; /* Add space for placeholder text */
        margin-bottom: 15px; /* Space between fields */
      }
      .login-container .btn {
        background-color: #0E82b0; /* Updated background color */
        border: none;
        color: white;
        border-radius: 20px; /* Make button circular */
        padding: 10px 20px; /* Adjust padding to make the button look circular */
        font-size: 16px; /* Font size */
        margin-top: 15px; /* Space between fields and button */
        width: 100%; /* Full width of container */
      }
      .login-container input {
        border-radius: 20px; /* Oval shape for inputs */
      }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="INDEX.php">NEMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                </ul>
            </div>
        </div>
    </nav>
    <div class="login-container">
        <div class="profile-pic"></div>
        <form method="POST" action="LOGIN.php">
            <div class="mb-3">
                <input type="text" class="form-control" name="t1" placeholder="Usuario">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="t2" placeholder="Contraseña">
            </div>
            <input type="submit" class="btn" value="Ingresar">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
