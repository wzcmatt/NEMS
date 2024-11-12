<?php

$conexion = null;
$servidor = "localhost";
$bd = "nems";
$user = "root";
$pass = "";
try {
    $conexion = new PDO('mysql:host='.$servidor.';dbname='.$bd, $user, $pass);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

$id_factura = isset($_GET['id_factura']) ? intval($_GET['id_factura']) : 0;

if ($id_factura <= 0) {
    echo json_encode(["error" => "ID de factura no válido"]);
    exit;
}


$sql = "SELECT f.id_factura, u.nombre AS cliente, f.fecha, f.total, dp.id_producto, p.nombre AS producto, dp.fact_det_cant, p.precio 
        FROM facturas f 
        JOIN usuarios u ON f.id_cliente = u.id 
        JOIN fact_det_product dp ON f.id_factura = dp.id_factura 
        JOIN productos p ON dp.id_producto = p.id_producto
        WHERE f.id_factura = :id_factura";

try {
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_factura', $id_factura, PDO::PARAM_INT);
    $stmt->execute();
    $factura = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($factura)) {
        echo json_encode(["error" => "Factura no encontrada", "id_factura" => $id_factura]);
        exit;
    }

    $factura_data = [
        'id_factura' => $factura[0]['id_factura'],
        'cliente' => $factura[0]['cliente'],
        'fecha' => $factura[0]['fecha'],
        'total' => $factura[0]['total'],
        'detalles' => []
    ];

    foreach ($factura as $detalle) {
        $factura_data['detalles'][] = [
            'nombre' => $detalle['producto'],
            'cantidad' => $detalle['fact_det_cant'],
            'precio' => $detalle['precio']
        ];
    }

    echo json_encode($factura_data);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
}
?>

