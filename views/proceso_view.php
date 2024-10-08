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
    <?php
    if (isset($_GET['edit'])) {
        $codigoP = $_GET['edit'];
        $result2 = $conn->query("SELECT * FROM proceso WHERE CodigoP = $codigoP");
        $row = $result2->fetch_assoc();
    }
    ?>

    <h1 class="mb-4">CRUD Proceso</h1>

    <div class="d-flex justify-content-between mb-3">
        <form method="post" class="form-inline" action="../controllers/proceso_controller.php">
            <?php
            $searchQuery = $searchQuery ?? '';
            ?>
            <input type="text" class="form-control mr-2" name="searchQuery" placeholder="Buscar por Juzgado, Abogado o Demandante" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="btn btn-primary" name="search">Buscar</button>
        </form>
        <a href="index.php" class="btn btn-custom">Regresar</a>
    </div>

    <form method="post" action="../controllers/proceso_controller.php">
        <input type="hidden" name="CodigoP" value="<?php echo isset($row['CodigoP']) ? $row['CodigoP'] : ''; ?>">
        <div class="form-group">
            <label for="CodigoJ">Juzgado</label>
            <select class="form-control" id="CodigoJ" name="CodigoJ" required>
                
                <?php if ($juzgados && $juzgados->num_rows > 0): ?>
                    <?php while ($juzgado = $juzgados->fetch_assoc()): ?>
                        <option value="<?= $juzgado['CodigoJ']; ?>" <?= isset($row['CodigoJ']) && $row['CodigoJ'] == $juzgado['CodigoJ'] ? 'selected' : ''; ?>>
                            <?= $juzgado['Juzgado']; ?>
                        </option>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <option>No se encontraron juzgados</option>
                    <?php endif; ?>
            </select>
        </div>
        <!-- Otros campos -->
        <div class="form-group">
            <label for="FechaRealizado">Fecha Realizado</label>
            <input type="date" class="form-control" id="FechaRealizado" name="FechaRealizado" value="<?php echo isset($row['FechaRealizado']) ? substr($row['FechaRealizado'], 0, 10) : ''; ?>" required>
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
        <button type="submit" class="btn btn-primary" name="<?= isset($row['CodigoP']) ? 'update' : 'create'; ?>">
            <?= isset($row['CodigoP']) ? 'Actualizar' : 'Crear'; ?>
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CodigoP']; ?></td>
                    <td><?= $row['Juzgado']; ?></td>
                    <td><?= $row['FechaRealizado']; ?></td>
                    <td><?= $row['Abogado']; ?></td>
                    <td><?= $row['Demandante']; ?></td>     
                    <td><?= $row['Demandado']; ?></td>
                    <td><?= $row['Inmueble']; ?></td>
                    <td>
                        <a href="proceso_controller.php?edit=<?= $row['CodigoP']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="proceso_controller.php?delete=<?= $row['CodigoP']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
