/**
 * MÓDULO JAVASCRIPT - login.js
 * Descripción: Maneja la interfaz de usuario del formulario de login
 * Propósito: Enviar credenciales al API y mostrar mensajes de error visuales
 *
 * CARACTERÍSTICAS:
 * - Comunicación asíncrona con el backend (fetch API)
 * - Manejo visual diferenciado de las 3 excepciones personalizadas
 * - Feedback visual durante el proceso de autenticación
 */

// Escuchar el evento de envío del formulario de login
document.getElementById("loginForm").addEventListener("submit", async (e) => {
  // Prevenir el comportamiento por defecto del formulario (recargar página)
  e.preventDefault()

  // Obtener valores de los campos del formulario
  const email = document.getElementById("email").value
  const password = document.getElementById("password").value

  // Referencias a elementos del DOM para feedback visual
  const errorDiv = document.getElementById("error-message")
  const submitBtn = document.getElementById("submitBtn")

  // Limpiar mensajes de error previos
  errorDiv.classList.add("hidden")

  // Deshabilitar botón y mostrar estado de carga
  submitBtn.disabled = true
  submitBtn.textContent = "Iniciando sesión..."

  // BLOQUE TRY-CATCH: Manejo de errores de red y del API
  try {
    // ========================================
    // PASO 1: ENVIAR PETICIÓN AL API
    // ========================================
    const response = await fetch("api/login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email, password }),
    })

    // Decodificar la respuesta JSON del servidor
    const result = await response.json()

    // ========================================
    // PASO 2: PROCESAR LA RESPUESTA
    // ========================================
    if (result.success) {
      // LOGIN EXITOSO: Redirigir al dashboard
      window.location.href = "dashboard.php"
    } else {
      // LOGIN FALLIDO: Mostrar error según el tipo de excepción capturada en el backend
      mostrarError(result)
    }
  } catch (error) {
    // ========================================
    // MANEJO DE ERRORES DE RED
    // ========================================
    // Si falla la conexión al servidor (red caída, servidor apagado, etc.)
    mostrarError({
      tipo_error: "ERROR_RED",
      message: "Error de conexión con el servidor",
      detalles: "No se pudo conectar con el servidor. Verifique su conexión.",
    })
  } finally {
    // ========================================
    // LIMPIAR ESTADO DEL BOTÓN
    // ========================================
    // Este bloque se ejecuta siempre, haya éxito o error
    submitBtn.disabled = false
    submitBtn.textContent = "Iniciar Sesión"
  }
})

/**
 * Función para mostrar mensajes de error personalizados
 * Recibe el objeto result del API y renderiza el mensaje apropiado
 *
 * @param {Object} result - Objeto con tipo_error, message y detalles
 *
 * TIPOS DE ERROR MANEJADOS:
 * 1. USUARIO_NO_EXISTENTE - Email no registrado (color rojo)
 * 2. CONTRASEÑA_INCORRECTA - Contraseña incorrecta (color amarillo)
 * 3. CUENTA_BLOQUEADA - Cuenta bloqueada por intentos (color rojo intenso)
 * 4. ERROR_RED - Problemas de conexión (color gris)
 */
function mostrarError(result) {
  // Obtener referencias a los elementos del mensaje de error
  const errorDiv = document.getElementById("error-message")
  const errorIcon = document.getElementById("error-icon")
  const errorTitle = document.getElementById("error-title")
  const errorText = document.getElementById("error-text")

  // Switch para determinar estilo y contenido según el tipo de error
  switch (result.tipo_error) {
    // ========================================
    // CASO 1: Usuario No Existente
    // ========================================
    case "USUARIO_NO_EXISTENTE":
      // Aplicar estilos de error crítico (rojo)
      errorDiv.className = "error-message bg-red-50 border border-red-200 rounded-lg p-4 mb-4"
      errorIcon.textContent = "👤" // Icono de usuario
      errorTitle.textContent = "Usuario No Existente"
      errorText.innerHTML = `<strong>${result.message}</strong><br>${result.detalles}`
      break

    // ========================================
    // CASO 2: Contraseña Incorrecta
    // ========================================
    case "CONTRASEÑA_INCORRECTA":
      // Aplicar estilos de advertencia (amarillo)
      errorDiv.className = "error-message bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4"
      errorIcon.textContent = "🔒" // Icono de candado
      errorTitle.textContent = "Contraseña Incorrecta"
      // Mostrar intentos restantes del mensaje
      errorText.innerHTML = `<strong>${result.message}</strong><br>${result.detalles}`
      break

    // ========================================
    // CASO 3: Cuenta Bloqueada
    // ========================================
    case "CUENTA_BLOQUEADA":
      // Aplicar estilos de error crítico intenso (rojo fuerte)
      errorDiv.className = "error-message bg-red-100 border border-red-300 rounded-lg p-4 mb-4"
      errorIcon.textContent = "🚫" // Icono de prohibición
      errorTitle.textContent = "¡Cuenta Bloqueada!"
      errorText.innerHTML = `<strong>${result.message}</strong><br>${result.detalles}`
      break

    // ========================================
    // CASO DEFAULT: Otros errores
    // ========================================
    default:
      // Estilo genérico para errores no clasificados
      errorDiv.className = "error-message bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4"
      errorIcon.textContent = "⚠️" // Icono de advertencia
      errorTitle.textContent = "Error"
      errorText.innerHTML = `${result.message || "Error desconocido"}`
  }

  // Hacer visible el mensaje de error
  errorDiv.classList.remove("hidden")
}
