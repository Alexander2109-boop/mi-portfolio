<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-person-badge me-2"></i>Detalle del Alumno</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <i class="bi bi-person-circle display-1 text-secondary"></i>
                        </div>
                        <div class="col-md-8">
                            <h3 class="fw-bold text-primary"><?php echo $alumno['Nombres'] . ' ' . $alumno['Apellidos']; ?></h3>
                            <hr>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 40%;">Cédula:</th>
                                    <td><?php echo $alumno['Cedula']; ?></td>
                                </tr>
                                <tr>
                                    <th>Correo:</th>
                                    <td><?php echo $alumno['Correo']; ?></td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td><?php echo $alumno['Telefono']; ?></td>
                                </tr>
                                <tr>
                                    <th>F. Nacimiento:</th>
                                    <td><?php echo date("d/m/Y", strtotime($alumno['FechaNacimiento'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-end py-3">
                    <a href="index.php?accion=reporte" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver al Listado
                    </a>
                    <a href="index.php?accion=editarAlumno&id=<?php echo $alumno['Id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Editar Datos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>