<?php
include 'db.php';

// Operaciones CRUD
if (isset($_POST['create'])) {
    $diaPago = $_POST['DiaPago'];
    $concepto = $_POST['Concepto'];
    $valor = $_POST['Valor'];
    $codigoProc = $_POST['CodigoProc'];
    $matInmu = $_POST['MatInmu'];
    
    $sql = "INSERT INTO arriendo (DiaPago, Concepto, Valor, CodigoProc, MatInmu) VALUES ('$diaPago', '$concepto', '$valor', '$codigoProc', '$matInmu')";
    $conn->query($sql);
} elseif (isset($_GET['delete'])) {
    $codigoA = $_GET['delete'];
    $sql = "DELETE FROM arriendo WHERE CodigoA = $codigoA";
    $conn->query($sql);
} elseif (isset($_POST['update'])) {
    $codigoA = $_POST['CodigoA'];
    $diaPago = $_POST['DiaPago'];
    $concepto = $_POST['Concepto'];
    $valor = $_POST['Valor'];
    $codigoProc = $_POST['CodigoProc'];
    $matInmu = $_POST['MatInmu'];

    $sql = "UPDATE arriendo SET DiaPago='$diaPago', Concepto='$concepto', Valor='$valor', CodigoProc='$codigoProc', MatInmu='$matInmu' WHERE CodigoA=$codigoA";
    $conn->query($sql); 

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Arriendos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Gestión de Arriendos</h1>

    <!-- Formulario para Crear/Actualizar -->
    <?php
    if (isset($_GET['edit'])) {
        $codigoA = $_GET['edit'];
        $result = $conn->query("SELECT * FROM arriendo WHERE codigoA = $codigoA");
        $row = $result->fetch_assoc();
    }

    ?>
    <form method="post" action="arriendos.php">
        <input type="hidden" name="CodigoA" value="<?php echo isset($row['codigoA']) ? $row['codigoA'] : ''; ?>">
        <div class="form-group">
            <label for="DiaPago">Día del pago</label>
            <input type="date" class="form-control" id="DiaPago" name="DiaPago" value="<?php echo isset($row['DiaPago']) ? $row['DiaPago'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Concepto">Concepto</label>
            <input type="text" class="form-control" id="Concepto" name="Concepto" value="<?php echo isset($row['concepto']) ? $row['concepto'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="Valor">Valor ($ COP)</label>
            <input type="number" step="0.01" class="form-control" id="Valor" name="Valor" value="<?php echo isset($row['valor']) ? $row['valor'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="CodigoP">Código del proceso</label>
            <select class="form-control" id="CodigoProc" name="CodigoProc" required>
                <?php
                $procesos = $conn->query("SELECT CodigoP, Demandante, Demandado FROM proceso");
                while ($proceso = $procesos->fetch_assoc()) {
                    echo "<option value='{$proceso['CodigoP']}'" . 
                    (isset($row['codigoProc']) && $row['codigoProc'] == $proceso['CodigoP'] ? ' selected' : '') . 
                    ">{$proceso['CodigoP']} - {$proceso['Demandante']} demanda a {$proceso['Demandado']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="CodigoP">Matricula del inmueble - Dirección</label>
            <select class="form-control" id="MatInmu" name="MatInmu" required>
                <?php
                $inmuebles = $conn->query("SELECT Matricula, Direccion FROM inmueble");
                while ($inmueble = $inmuebles->fetch_assoc()) {
                    echo "<option value='{$inmueble['Matricula']}'" . 
                    (isset($row['matInmu']) && $row['matInmu'] == $inmueble['Matricula'] ? ' selected' : '') . 
                    ">{$inmueble['Matricula']} - {$inmueble['Direccion']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="<?php echo isset($row['codigoA']) ? 'update' : 'create'; ?>">
            <?php echo isset($row['codigoA']) ? 'Actualizar' : 'Crear'; ?>
        </button>
    </form>

    <h2 class="mt-5">Lista de Arriendos</h2>
    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Codigo Arriendo</th>
                <th>Día de pago</th>
                <th>Concepto</th>
                <th>Valor</th>
                <th>Código del proceso</th>
                <th>Matrícula del inmueble</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("
                SELECT a.codigoA, a.diaPago, a.concepto, CONCAT('$', a.valor) as valor, 
                CONCAT(p.CodigoP , ' - ', p.Demandante, ' demanda a ', p.Demandado) AS CodigoP, 
                CONCAT(i.Matricula, ' - ', i.Direccion)  AS Inmueble 
                FROM arriendo a 
                JOIN proceso p ON a.CodigoProc = p.CodigoP 
                JOIN inmueble i ON a.MatInmu = i.Matricula
            ");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['codigoA']}</td>
                    <td>{$row['diaPago']}</td>
                    <td>{$row['concepto']}</td>
                    <td>{$row['valor']}</td>
                    <td>{$row['CodigoP']}</td>
                    <td>{$row['Inmueble']}</td>
                    <td>
                        <a href='arriendos.php?edit={$row['codigoA']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='arriendos.php?delete={$row['codigoA']}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?');\">Eliminar</a>
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
