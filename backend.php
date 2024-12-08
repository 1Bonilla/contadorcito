<?php
include('db_connection.php'); //conectar a la base ded datos
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$action = $_POST['action'] ?? '';

//Aquí se agrega la empresa
if ($action == 'agregar_empresa') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    //Verificar los datos
    $nombre = $conn->real_escape_string($nombre);
    $tipo = $conn->real_escape_string($tipo);
    $direccion = $conn->real_escape_string($direccion);
    $telefono = $conn->real_escape_string($telefono);
    $correo = $conn->real_escape_string($correo);

    //Guardar la empresa en la BD
    $sql = "INSERT INTO empresas (nombre, tipo, direccion, telefono, correo) 
            VALUES ('$nombre', '$tipo', '$direccion', '$telefono', '$correo')";

    
    if ($conn->query($sql) === TRUE) {
       
        echo "Empresa agregada correctamente.";
    } else {
      
        echo "Error al agregar la empresa: " . $conn->error;
    }
}

//Para ver la lista de empresas
if (isset($_GET['action']) && $_GET['action'] == 'listar_empresas') {
    $sql = "SELECT * FROM empresas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['tipo']}</td>
                    <td>{$row['direccion']}</td>
                    <td>{$row['telefono']}</td>
                    <td>{$row['correo']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No hay empresas registradas.</td></tr>";
    }
}


$conn = new mysqli('localhost', 'root', '', 'contadorcito_db'); // Cambia los parámetros si es necesario

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Agregar una venta
if (isset($_POST['action']) && $_POST['action'] == 'agregar_venta') {
    $empresa = $_POST['empresa'];
    $tipo = $_POST['tipo'];
    $numero = $_POST['numero'];
    $fecha = $_POST['fecha'];
    $monto = $_POST['monto'];
    $cliente = $_POST['cliente'];

    $sql = "INSERT INTO comprobantes_ventas (id_empresa, tipo, numero_comprobante, fecha, monto, cliente) 
            VALUES ('$empresa', '$tipo', '$numero', '$fecha', '$monto', '$cliente')";

    if ($conn->query($sql) === TRUE) {
        echo "Comprobante agregado correctamente.";
    } else {
        echo "Error al agregar el comprobante: " . $conn->error;
    }

}


//generar reporte
if ($action == 'generar_reporte') {
    $empresa = $_POST['empresa'];
    $tipo_comprobante = $_POST['tipo_comprobante'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

   
    $tabla = $tipo_comprobante == 'compras' ? 'comprobantes_compras' : 'comprobantes_ventas';


    $sql = "SELECT * FROM $tabla WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    if (!empty($empresa)) {
        $sql .= " AND id_empresa = '$empresa'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Empresa</th><th>Tipo</th><th>Número</th><th>Fecha</th><th>Monto</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_empresa']}</td>
                    <td>{$row['tipo']}</td>
                    <td>{$row['numero_comprobante']}</td>
                    <td>{$row['fecha']}</td>
                    <td>{$row['monto']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron datos para el reporte.</p>";
    }
}

$conn->close();
