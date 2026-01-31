
<!DOCTYPE html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Alumnos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        [data-bs-theme="dark"] body {
            background-color: #121212;
        }

        .form-signin {
            max-width: 400px;
            padding: 1rem;
            margin: auto;
        }

        .card-login {
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        /* Estilo para que los campos se vean unidos */
        .input-top {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-bottom {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center">

    <main class="form-signin w-100">
        <div class="card card-login bg-body-tertiary">
            <form action="index.php?accion=procesarLogin" method="post">
                <img class="mb-4" src="../assets/logo.png"  alt="" width="72" height="57">
                
                <h1 class="h3 mb-3 fw-bold">INICIAR SESIÓN</h1>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger p-2 small" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="form-floating my-3">
                    <input type="text" class="form-control input-top" id="usuario" name="usuario" placeholder="Nombre de usuario" required>
                    <label for="usuario">Usuario</label>
                </div>

                <div class="form-floating my-3">
                    <input type="password" class="form-control input-bottom" id="clave" name="clave" placeholder="Contraseña" required>
                    <label for="clave">Contraseña</label>
                </div>

                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Recordarme
                    </label>
                </div>

                <button class="btn btn-primary w-100 py-2 fw-bold" type="submit">Ingresar</button>
                
                <p class="mt-5 mb-3 text-body-secondary small">&copy; 2026 Sistema de Alumnos</p>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>