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
    $tipo = $_POST['Tipo'];
    $direccion = $_POST['Direccion'];
    $ciudad = $_POST['Ciudad'];
    $telefono = $_POST['Telefono'];
    $email = $_POST['Email'];
    $estado = $_POST['Estado'];
    $observaciones = $_POST['Observaciones'];


    $sql = "UPDATE inmueble SET Tipo='$tipo', Direccion='$direccion', Ciudad='$ciudad', Telefono='$telefono', Email='$email', Estado='$estado', Observacion='$observaciones' WHERE Matricula=$matricula";
    $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Juzgado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Gestión de inmueble</h1>

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
            <label for="Direccion">Direccion</label>
            <input type="text" class="form-control" id="Direccion" name="Direccion" value="<?php echo isset($row['Direccion']) ? $row['Direccion'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Ciudad">Ciudad</label>
            <input type="text" class="form-control" id="Ciudad" name="Ciudad" value="<?php echo isset($row['Ciudad']) ? $row['Ciudad'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Telefono">Telefono</label>
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

    <h2 class="mt-5">Lista de Inmueble</h2>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Matricula</th>
                <th>Tipo</th>
                <th>Direccion</th>
                <th>Ciudad</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Observaciones</th>
                <th>Acciones</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM inmueble");
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

    <!-- Botón Regresar -->
    <div class="mt-4 text-right">
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
