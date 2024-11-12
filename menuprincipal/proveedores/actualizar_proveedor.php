<?php
header('Content-Type: application/json'); // Indicar que la respuesta es JSON

$conexion = null;
$servidor = "localhost";
$bd = "nems";
$user = "root";
$pass = "";

try {
    $conexion = new PDO('mysql:host='.$servidor.';dbname='.$bd, $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

// Mensaje
$response = ['success' => false, 'message' => ''];

// Obtener los datos del proveedor para editar (solo si es GET)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_proveedor = intval($_GET['id']);
    $proveedor = null;

    if ($id_proveedor > 0) {
        $sql = "SELECT * FROM proveedores WHERE id_provee = :id_proveedor";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_proveedor', $id_proveedor);
        $stmt->execute();
        $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($proveedor) {
        // Mostrar los datos en formato JSON
        echo json_encode(['success' => true, 'proveedor' => $proveedor]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Proveedor no encontrado']);
    }
    exit;
}

// Actualizar proveedor (solo si es POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $id_provee = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "UPDATE proveedores SET nomb_e = :nombre, email = :email, tel = :telefono, direc_e = :direccion WHERE id_provee = :id_provee";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_provee', $id_provee);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    
    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Proveedor actualizado con éxito'];
    } else {
        $response = ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    echo json_encode($response);
    exit;
}
?>
