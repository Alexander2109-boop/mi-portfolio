# Ejecutar el Sistema desde Visual Studio Code

## Requisitos Previos
1. Tener PHP instalado (viene con XAMPP)
2. Tener MySQL corriendo en XAMPP
3. Base de datos `bd_clientes` creada e importada

## Método 1: Usando el Script de Inicio (Más Fácil)

### En Windows:
1. Abre el proyecto en VS Code
2. Haz doble clic en el archivo `iniciar_servidor.bat`
3. Se abrirá una ventana de comando
4. Abre tu navegador en: `http://localhost:8000/CapaPresentacion/index.html`

### En Linux/Mac:
1. Abre el proyecto en VS Code
2. Dale permisos de ejecución al script:
   \`\`\`bash
   chmod +x iniciar_servidor.sh
   \`\`\`
3. Ejecuta el script:
   \`\`\`bash
   ./iniciar_servidor.sh
   \`\`\`
4. Abre tu navegador en: `http://localhost:8000/CapaPresentacion/index.html`

## Método 2: Usando la Terminal de VS Code

1. Abre VS Code
2. Presiona **Ctrl + Ñ** (o Ctrl + `) para abrir la terminal integrada
3. Asegúrate de estar en la carpeta del proyecto:
   \`\`\`bash
   cd D:\xampp\htdocs\SistemaGestionClientes
   \`\`\`
4. Ejecuta el servidor PHP:
   \`\`\`bash
   php -S localhost:8000
   \`\`\`
5. Verás un mensaje como:
   \`\`\`
   [Wed Jan 15 10:30:00 2025] PHP 8.x Development Server started on http://localhost:8000
   \`\`\`
6. Abre tu navegador en: `http://localhost:8000/CapaPresentacion/index.html`

## Método 3: Usando Extensión de VS Code (Opcional)

1. Instala la extensión **"PHP Server"** de brapifra en VS Code
2. Haz clic derecho en el archivo `index.html`
3. Selecciona **"PHP Server: Serve project"**
4. Se abrirá automáticamente en tu navegador

## Importante

- **Siempre mantén MySQL corriendo en XAMPP** (solo necesitas el módulo MySQL activo)
- No es necesario tener Apache de XAMPP corriendo si usas el servidor PHP integrado
- Para detener el servidor, presiona **Ctrl + C** en la terminal

## Credenciales por Defecto

- **Usuario:** admin@sistema.com
- **Contraseña:** admin123

## Solución de Problemas

### "php no se reconoce como comando"
Agrega PHP a las variables de entorno:
1. Abre "Variables de entorno" en Windows
2. Edita la variable PATH
3. Agrega: `D:\xampp\php`
4. Reinicia VS Code

### "Error de conexión a la base de datos"
1. Verifica que MySQL esté corriendo en XAMPP
2. Abre phpMyAdmin: `http://localhost/phpmyadmin`
3. Verifica que la base de datos `bd_clientes` exista
4. Revisa las credenciales en `CapaDatos/Conexion.php`

### El puerto 8000 ya está en uso
Cambia el puerto en el comando:
\`\`\`bash
php -S localhost:8080
\`\`\`
Y accede en: `http://localhost:8080/CapaPresentacion/index.html`
