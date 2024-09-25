<?php
include 'db.php';

// Operaciones CRUD
if (isset($_POST['create'])) {
    $codigoJ = $_POST['CodigoJ'];
    $fechaRealizado = $_POST['FechaRealizado'];
    $abogado = $_POST['Abogado'];
    $demandante = $_POST['Demandante'];
    $demandado = $_POST['Demandado'];
    $matInm = $_POST['Mat_Inm'];

    $sql = "INSERT INTO proceso (CodigoJ, FechaRealizado, Abogado, Demandante, Demandado, Mat_Inm) VALUES ('$codigoJ', '$fechaRealizado', '$abogado', '$demandante', '$demandado', '$matInm')";
    $conn->query($sql);
} elseif (isset($_GET['delete'])) {
    $codigoP = $_GET['delete'];
    $sql = "DELETE FROM proceso WHERE CodigoP = $codigoP";
    $conn->query($sql);
} elseif (isset($_POST['update'])) {
    $codigoP = $_POST['CodigoP'];
    $codigoJ = $_POST['CodigoJ'];
    $fechaRealizado = $_POST['FechaRealizado'];
    $abogado = $_POST['Abogado'];
    $demandante = $_POST['Demandante'];
    $demandado = $_POST['Demandado'];
    $matInm = $_POST['Mat_Inm'];

    $sql = "UPDATE proceso SET CodigoJ='$codigoJ', FechaRealizado='$fechaRealizado', Abogado='$abogado', Demandante='$demandante', Demandado='$demandado', Mat_Inm='$matInm' WHERE CodigoP=$codigoP";
    $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Proceso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">CRUD Proceso</h1>

    <!-- Formulario para Crear/Actualizar -->
    <?php
    if (isset($_GET['edit'])) {
        $codigoP = $_GET['edit'];
        $result = $conn->query("SELECT * FROM proceso WHERE CodigoP = $codigoP");
        $row = $result->fetch_assoc();
    }
    ?>
    <form method="post" action="proceso.php">
        <input type="hidden" name="CodigoP" value="<?php echo isset($row['CodigoP']) ? $row['CodigoP'] : ''; ?>">
        <div class="form-group">
            <label for="CodigoJ">Juzgado</label>
            <select class="form-control" id="CodigoJ" name="CodigoJ" required>
                <?php
                $juzgados = $conn->query("SELECT CodigoJ, Juzgado FROM juzgado");
                while ($juzgado = $juzgados->fetch_assoc()) {
                    echo "<option value='{$juzgado['CodigoJ']}'" . (isset($row['CodigoJ']) && $row['CodigoJ'] == $juzgado['CodigoJ'] ? ' selected' : '') . ">{$juzgado['Juzgado']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="FechaRealizado">Fecha Realizado</label>
            <input type="date" class="form-control" id="FechaRealizado" name="FechaRealizado" value="<?php echo isset($row['FechaRealizado']) ? $row['FechaRealizado'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Abogado">Abogado</label>
            <input type="text" class="form-control" id="Abogado" name="Abogado" value="<?php echo isset($row['Abogado']) ? $row['Abogado'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Demandante">Demandante</label>
            <input type="text" class="form-control" id="Demandante" name="Demandante" value="<?php echo isset($row['Demandante']) ? $row['Demandante'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Demandado">Demandado</label>
            <input type="text" class="form-control" id="Demandado" name="Demandado" value="<?php echo isset($row['Demandado']) ? $row['Demandado'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Mat_Inm">Inmueble</label>
            <select class="form-control" id="Mat_Inm" name="Mat_Inm" required>
                <?php
                $inmuebles = $conn->query("SELECT Matricula, Direccion FROM inmueble");
                while ($inmueble = $inmuebles->fetch_assoc()) {
                    echo "<option value='{$inmueble['Matricula']}'" . (isset($row['Mat_Inm']) && $row['Mat_Inm'] == $inmueble['Matricula'] ? ' selected' : '') . ">{$inmueble['Direccion']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="<?php echo isset($row['CodigoP']) ? 'update' : 'create'; ?>">
            <?php echo isset($row['CodigoP']) ? 'Actualizar' : 'Crear'; ?>
        </button>
    </form>

    <h2 class="mt-5">Lista de Procesos</h2>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>CodigoP</th>
                <th>Juzgado</th>
                <th>Fecha Realizado</th>
                <th>Abogado</th>
                <th>Demandante</th>
                <th>Demandado</th>
                <th>Inmueble</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("
                SELECT p.CodigoP, j.Juzgado, p.FechaRealizado, p.Abogado, p.Demandante, p.Demandado, i.Direccion AS Inmueble 
                FROM proceso p 
                JOIN juzgado j ON p.CodigoJ = j.CodigoJ 
                JOIN inmueble i ON p.Mat_Inm = i.Matricula
            ");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['CodigoP']}</td>
                    <td>{$row['Juzgado']}</td>
                    <td>{$row['FechaRealizado']}</td>
                    <td>{$row['Abogado']}</td>
                    <td>{$row['Demandante']}</td>
                    <td>{$row['Demandado']}</td>
                    <td>{$row['Inmueble']}</td>
                    <td>
                        <a href='proceso.php?edit={$row['CodigoP']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='proceso.php?delete={$row['CodigoP']}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?');\">Eliminar</a>
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
