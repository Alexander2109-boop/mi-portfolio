<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold mb-0"><i class="bi bi-people-fill me-2 text-primary"></i>Listado de Alumnos</h2>
            <p class="text-muted mb-0">Gestión y visualización de registros escolares</p>
        </div>
        <span class="badge bg-primary rounded-pill px-3 py-2">
            <i class="bi bi-database me-1"></i> Registros: <?php echo $cantidad; ?>
        </span>
    </div>

    <div class="card shadow-sm mb-4 border-0 bg-body-tertiary">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-2 align-items-center">
                <input type="hidden" name="accion" value="reporte">

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i
                                class="bi bi-filter"></i></span>
                        <select name="tipo_busqueda" class="form-select border-start-0 ps-0">
                            <option value="" <?= ($_GET['tipo_busqueda'] ?? '') == '' ? 'selected' : '' ?>>Todos</option>
                            <option value="nombre" <?= ($_GET['tipo_busqueda'] ?? '') == 'nombre' ? 'selected' : '' ?>>
                                Nombre</option>
                            <option value="apellido" <?= ($_GET['tipo_busqueda'] ?? '') == 'apellido' ? 'selected' : '' ?>>
                                Apellido</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="valor_busqueda" class="form-control"
                            placeholder="Escribe para buscar..."
                            value="<?= htmlspecialchars($_GET['valor_busqueda'] ?? '') ?>">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>

                <div class="col-md-3 text-end">
                    <?php if (!empty($_GET['valor_busqueda'])): ?>
                        <a href="index.php?accion=reporte" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i> Limpiar Filtros
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3" style="width: 60px;">ID</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th class="text-center">F. Nacimiento</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cantidad > 0): ?>
                        <?php foreach ($alumnos as $fila): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?php echo htmlspecialchars($fila['Id']); ?></td>
                                <td><span
                                        class="badge bg-light text-dark border"><?php echo htmlspecialchars($fila['Cedula']); ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        <?php echo htmlspecialchars($fila['Nombres'] . ' ' . $fila['Apellidos']); ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo $fila['Correo']; ?>" class="text-decoration-none small">
                                        <i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($fila['Correo']); ?>
                                    </a>
                                </td>
                                <td class="small text-muted">
                                    <i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($fila['Telefono']); ?>
                                </td>
                                <td class="text-center small">
                                    <?php echo date("d/m/Y", strtotime($fila['FechaNacimiento'])); ?>
                                </td>
                                <td class="text-center">
                                    <a href="index.php?accion=verAlumno&id=<?php echo $fila['Id']; ?>"
                                        class="btn btn-sm btn-outline-info" title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="index.php?accion=editarAlumno&id=<?php echo $fila['Id']; ?>"
                                        class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="index.php?accion=eliminarAlumno&id=<?php echo $fila['Id']; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este registro?')" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <i class="bi bi-exclamation-circle text-warning display-4"></i>
                                <p class="mt-2 fw-semibold">No se encontraron resultados para tu búsqueda.</p>
                                <a href="index.php?accion=reporte" class="btn btn-sm btn-secondary">Ver todos los
                                    alumnos</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>