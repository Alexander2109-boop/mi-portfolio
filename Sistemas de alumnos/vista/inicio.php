<?php include __DIR__.'/layout/header.php'; ?> 

<div class="container mt-0">
    <div class="p-5 mb-4 bg-light rounded-3 text-center">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold">Bienvenido al Sistema de Gestión Académica</h1>
            <p class="col-md-8 fs-4 mx-auto">
                Gestión de alumnos, cursos y notas de forma eficiente y centralizada.
            </p>
        </div>

        <div id="carouselExampleCaptions" class="carousel slide border rounded shadow" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4"></button>
            </div>

            <div class="carousel-inner rounded">
                <div class="carousel-item active">
                    <img src="../assets/carrusel1.png" class="d-block w-100" alt="Campus">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <h5>Educación Comprometida</h5>
                        <p>Un ambiente agradable para el aprendizaje.</p>
                    </div>
                </div>
                
                <div class="carousel-item">
                    <img src="../assets/carrusel2.jpg" class="d-block w-100" alt="Clase">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <h5>Gestión de Alumnos</h5>
                        <p>Organiza la información de los estudiantes.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../assets/carrusel3.jpg" class="d-block w-100" alt="Docente">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <h5>Control Docente</h5>
                        <p>Administra asignaciones y seguimiento de profesores.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../assets/carrusel4.jpg" class="d-block w-100" alt="Estadisticas">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <h5>Reportes y Estadísticas</h5>
                        <p>Consulta indicadores clave del sistema.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../assets/carussel5.jpg" class="d-block w-100" alt="Notas">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <h5>Ingreso de Notas</h5>
                        <p>Registra y actualiza calificaciones fácilmente.</p>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>
</div>

<?php include __DIR__.'/layout/footer.php'; ?>

<style>
    .carousel-item img {
        height: 600px;
        object-fit: cover;
    }
</style>