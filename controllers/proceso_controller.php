<?php
include '../models/db.php'; // Conexión a la base de datos


// Operaciones CRUD
if (isset($_POST['create'])) {
    $codigoJ = $_POST['CodigoJ'];
    $fechaRealizado = $_POST['FechaRealizado'];
    $abogado = $_POST['Abogado'];
    $demandante = $_POST['Demandante'];
    $demandado = $_POST['Demandado'];
    $matInm = $_POST['Mat_Inm'];

    $sql = "INSERT INTO proceso (CodigoJ, FechaRealizado, Abogado, Demandante, Demandado, Mat_Inm) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $codigoJ, $fechaRealizado, $abogado, $demandante, $demandado, $matInm);
    $stmt->execute();
} elseif (isset($_GET['delete'])) {
    $codigoP = $_GET['delete'];
    $sql = "DELETE FROM proceso WHERE CodigoP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $codigoP);
    $stmt->execute();
} elseif (isset($_POST['update'])) {
    $codigoP = $_POST['CodigoP'];
    $codigoJ = $_POST['CodigoJ'];
    $fechaRealizado = $_POST['FechaRealizado'];
    $abogado = $_POST['Abogado'];
    $demandante = $_POST['Demandante'];
    $demandado = $_POST['Demandado'];
    $matInm = $_POST['Mat_Inm'];

    $sql = "UPDATE proceso SET CodigoJ=?, FechaRealizado=?, Abogado=?, Demandante=?, Demandado=?, Mat_Inm=? WHERE CodigoP=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $codigoJ, $fechaRealizado, $abogado, $demandante, $demandado, $matInm, $codigoP);
    $stmt->execute();
}

// Manejo de búsqueda
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $conn->real_escape_string($_POST['searchQuery']);
}

$sql = "SELECT p.CodigoP, j.Juzgado, p.FechaRealizado, p.Abogado, p.Demandante, p.Demandado, i.Direccion AS Inmueble 
        FROM proceso p 
        JOIN juzgado j ON p.CodigoJ = j.CodigoJ 
        JOIN inmueble i ON p.Mat_Inm = i.Matricula";
if ($searchQuery) {
    $sql .= " WHERE j.Juzgado LIKE '%$searchQuery%' OR p.Abogado LIKE '%$searchQuery%' OR p.Demandante LIKE '%$searchQuery%'";
}
$result = $conn->query($sql);

// Pasar los datos a la vista
$juzgados = $conn->query("SELECT CodigoJ, Juzgado FROM juzgado");
$inmuebles = $conn->query("SELECT Matricula, Direccion FROM inmueble");


include '../views/proceso_view.php'; // Vista de la gestión de procesos
