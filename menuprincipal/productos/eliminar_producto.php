<?php
$conexion = null;
$servidor = "localhost";
$bd = "nems";
$user = "root";
$pass = "";            

try {
    $conexion = new PDO('mysql:host='.$servidor.';dbname='.$bd, $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];

        try {
            $conexion->beginTransaction();

            // Eliminar las referencias en fact_det_product
            $sql = "DELETE FROM fact_det_product WHERE id_producto = :id_producto";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->execute();

            // Eliminar el producto
            $sql = "DELETE FROM productos WHERE id_producto = :id_producto";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->execute();

            $conexion->commit();

            // Redirigir usando JavaScript después de eliminar el producto
            echo "<script>
                    alert('Producto eliminado con éxito!');
                    window.location.href = 'consultarprod.php';
                  </script>";
            exit;
        } catch (PDOException $e) {
            $conexion->rollBack();
            echo "<script>
                    alert('Error al eliminar el producto: " . $e->getMessage() . "');
                    window.location.href = 'consultarprod.php?mensaje=Error al eliminar el producto';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('ID de producto no proporcionado');
                window.location.href = 'consultarprod.php?mensaje=ID de producto no proporcionado';
              </script>";
        exit;
    }
} else {
    echo "<script>
            alert('Solicitud no válida');
            window.location.href = 'consultarprod.php?mensaje=Solicitud no válida';
          </script>";
    exit;
}
?>
