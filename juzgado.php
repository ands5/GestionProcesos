<?php
include 'db.php';

// Operaciones CRUD
if (isset($_POST['create'])) {
    $juzgado = $_POST['Juzgado'];
    $nombre = $_POST['Nombre'];
    $email = $_POST['Email'];
    $cuentaBancaria = $_POST['CuentaBancaria'];

    $sql = "INSERT INTO juzgado (Juzgado, Nombre, Email, CuentaBancaria) VALUES ('$juzgado', '$nombre', '$email', '$cuentaBancaria')";
    $conn->query($sql);
} elseif (isset($_GET['delete'])) {
    $codigoJ = $_GET['delete'];
    $sql = "DELETE FROM juzgado WHERE CodigoJ = $codigoJ";
    $conn->query($sql);
} elseif (isset($_POST['update'])) {
    $codigoJ = $_POST['CodigoJ'];
    $juzgado = $_POST['Juzgado'];
    $nombre = $_POST['Nombre'];
    $email = $_POST['Email'];
    $cuentaBancaria = $_POST['CuentaBancaria'];

    $sql = "UPDATE juzgado SET Juzgado='$juzgado', Nombre='$nombre', Email='$email', CuentaBancaria='$cuentaBancaria' WHERE CodigoJ=$codigoJ";
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
    <title>CRUD Juzgado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .btn-custom {
            border-radius: 20px; /* Bordes más redondeados */
            background-color: #007bff; /* Azul */
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Gestión de Juzgado</h1>

    <!-- Contenedor para el botón y la búsqueda -->
    <div class="d-flex justify-content-between mb-3">
        <form method="post" class="form-inline">
            <input type="text" class="form-control mr-2" name="searchQuery" placeholder="Buscar por Juzgado o Nombre" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="btn btn-primary" name="search">Buscar</button>
        </form>
        <a href="index.php" class="btn btn-custom">Regresar</a>
    </div>

    <!-- Formulario para Crear/Actualizar -->
    <?php
    if (isset($_GET['edit'])) {
        $codigoJ = $_GET['edit'];
        $result = $conn->query("SELECT * FROM juzgado WHERE CodigoJ = $codigoJ");
        $row = $result->fetch_assoc();
    }
    ?>
    <form method="post" action="juzgado.php">
        <input type="hidden" name="CodigoJ" value="<?php echo isset($row['CodigoJ']) ? $row['CodigoJ'] : ''; ?>">
        <div class="form-group">
            <label for="Juzgado">Juzgado</label>
            <input type="text" class="form-control" id="Juzgado" name="Juzgado" value="<?php echo isset($row['Juzgado']) ? $row['Juzgado'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control" id="Nombre" name="Nombre" value="<?php echo isset($row['Nombre']) ? $row['Nombre'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?php echo isset($row['Email']) ? $row['Email'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="CuentaBancaria">Cuenta Bancaria</label>
            <input type="text" class="form-control" id="CuentaBancaria" name="CuentaBancaria" value="<?php echo isset($row['CuentaBancaria']) ? $row['CuentaBancaria'] : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="<?php echo isset($row['CodigoJ']) ? 'update' : 'create'; ?>">
            <?php echo isset($row['CodigoJ']) ? 'Actualizar' : 'Crear'; ?>
        </button>
    </form>

    <h2 class="mt-5">Lista de Juzgados</h2>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>CodigoJ</th>
                <th>Juzgado</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Cuenta Bancaria</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta con filtro de búsqueda
            $sql = "SELECT * FROM juzgado";
            if ($searchQuery) {
                $sql .= " WHERE Juzgado LIKE '%$searchQuery%' OR Nombre LIKE '%$searchQuery%'";
            }
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['CodigoJ']}</td>
                    <td>{$row['Juzgado']}</td>
                    <td>{$row['Nombre']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['CuentaBancaria']}</td>
                    <td>
                        <a href='juzgado.php?edit={$row['CodigoJ']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='juzgado.php?delete={$row['CodigoJ']}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?');\">Eliminar</a>
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
