<?php
include 'db.php';

// Operaciones CRUD
if (isset($_POST['create'])) {
    $tipo = $_POST['Tipo'];
    $direccion = $_POST['Direccion'];
    $ciudad = $_POST['Ciudad'];
    $telefono = $_POST['Telefono'];
    $email = $_POST['Email'];
    $estado = $_POST['Estado'];
    $observaciones = $_POST['Observaciones'];

    $sql = "INSERT INTO inmueble (Tipo, Direccion, Ciudad, TelefonoContacto, Email, Estado, Observaciones) VALUES ('$tipo', '$direccion', '$ciudad', '$telefono', '$email', '$estado', '$observaciones')";
    $conn->query($sql);
} elseif (isset($_GET['delete'])) {
    $matricula = $_GET['delete'];
    $sql = "DELETE FROM inmueble WHERE Matricula = $matricula";
    $conn->query($sql);
} elseif (isset($_POST['update'])) {
    $matricula = $_POST['Matricula'];
    $tipo = $_POST['Tipo'];
    $direccion = $_POST['Direccion'];
    $ciudad = $_POST['Ciudad'];
    $telefono = $_POST['Telefono'];
    $email = $_POST['Email'];
    $estado = $_POST['Estado'];
    $observaciones = $_POST['Observaciones'];

    $sql = "UPDATE inmueble SET Tipo='$tipo', Direccion='$direccion', Ciudad='$ciudad', TelefonoContacto='$telefono', Email='$email', Estado='$estado', Observaciones='$observaciones' WHERE Matricula=$matricula";
    $conn->query($sql);
}

// Manejo de búsqueda
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['searchQuery'];
    $searchQuery = $conn->real_escape_string($searchQuery); // Previene inyección SQL
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Inmueble</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Gestión de Inmueble</h1>

    <!-- Contenedor para el botón y la búsqueda -->
    <div class="d-flex justify-content-between mb-3">
        <form method="post" class="form-inline">
            <input type="text" class="form-control mr-2" name="searchQuery" placeholder="Buscar por Tipo, Direccion, Ciudad o Estado" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="btn btn-primary" name="search">Buscar</button>
        </form>
        <a href="index.php" class="btn btn-primary">Regresar</a>
    </div>

    <!-- Formulario para Crear/Actualizar -->
    <?php
    if (isset($_GET['edit'])) {
        $matricula = $_GET['edit'];
        $result = $conn->query("SELECT * FROM inmueble WHERE Matricula = $matricula");
        $row = $result->fetch_assoc();
    }
    ?>
    <form method="post" action="inmueble.php">
        <input type="hidden" name="Matricula" value="<?php echo isset($row['Matricula']) ? $row['Matricula'] : ''; ?>">
        <div class="form-group">
            <label for="Tipo">Tipo</label>
            <input type="text" class="form-control" id="Tipo" name="Tipo" value="<?php echo isset($row['Tipo']) ? $row['Tipo'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Direccion">Dirección</label>
            <input type="text" class="form-control" id="Direccion" name="Direccion" value="<?php echo isset($row['Direccion']) ? $row['Direccion'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Ciudad">Ciudad</label>
            <input type="text" class="form-control" id="Ciudad" name="Ciudad" value="<?php echo isset($row['Ciudad']) ? $row['Ciudad'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Telefono">Teléfono</label>
            <input type="text" class="form-control" id="Telefono" name="Telefono" value="<?php echo isset($row['Telefono']) ? $row['Telefono'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?php echo isset($row['Email']) ? $row['Email'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="Estado">Estado</label>
            <input type="text" class="form-control" id="Estado" name="Estado" value="<?php echo isset($row['Estado']) ? $row['Estado'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Observaciones">Observaciones</label>
            <input type="text" class="form-control" id="Observaciones" name="Observaciones" value="<?php echo isset($row['Observaciones']) ? $row['Observaciones'] : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="<?php echo isset($row['Matricula']) ? 'update' : 'create'; ?>">
            <?php echo isset($row['Matricula']) ? 'Actualizar' : 'Crear'; ?>
        </button>
    </form>

    <h2 class="mt-5">Lista de Inmuebles</h2>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Matricula</th>
                <th>Tipo</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta con filtro de búsqueda
            $sql = "SELECT * FROM inmueble";
            if ($searchQuery) {
                $sql .= " WHERE Tipo LIKE '%$searchQuery%' OR Direccion LIKE '%$searchQuery%' OR Ciudad LIKE '%$searchQuery%' OR Estado LIKE '%$searchQuery%'";
            }
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['Matricula']}</td>
                    <td>{$row['Tipo']}</td>
                    <td>{$row['Direccion']}</td>
                    <td>{$row['Ciudad']}</td>
                    <td>{$row['TelefonoContacto']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['Estado']}</td>
                    <td>{$row['Observaciones']}</td>
                    <td>
                        <a href='inmueble.php?edit={$row['Matricula']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='inmueble.php?delete={$row['Matricula']}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?');\">Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
