/**
 * MÓDULO JAVASCRIPT - clientes.js
 * Descripción: Maneja la interfaz CRUD de Clientes
 * Propósito: Comunicación con el API REST y manipulación del DOM
 *
 * FUNCIONALIDADES:
 * - Listar clientes en tabla
 * - Crear nuevo cliente
 * - Editar cliente existente
 * - Eliminar cliente con confirmación
 */

// Variable global para controlar si estamos en modo edición
let editando = false

/**
 * Función principal: Cargar y mostrar clientes en la tabla
 * Se ejecuta al cargar la página y después de cada operación CRUD
 */
async function cargarClientes() {
  try {
    // ========================================
    // PASO 1: PETICIÓN GET al API
    // ========================================
    const response = await fetch("api/clientes.php")
    const clientes = await response.json()

    // Obtener referencia al tbody de la tabla
    const tbody = document.getElementById("tablaClientes")

    // ========================================
    // PASO 2: VERIFICAR SI HAY DATOS
    // ========================================
    if (clientes.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay clientes registrados</td></tr>'
      return
    }

    // ========================================
    // PASO 3: RENDERIZAR FILAS DE LA TABLA
    // ========================================
    // Usar map() para transformar cada cliente en una fila HTML
    tbody.innerHTML = clientes
      .map(
        (cliente) => `
            <tr>
                <td>${cliente.id}</td>
                <td>${cliente.nombre}</td>
                <td>${cliente.tipo_cliente_nombre}</td>
                <td>${cliente.telefono || "-"}</td>
                <td>${cliente.direccion || "-"}</td>
                <td>${cliente.fecha_alta || "-"}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editarCliente(${cliente.id})">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarCliente(${cliente.id})">Eliminar</button>
                </td>
            </tr>
        `,
      )
      .join("") // Unir array de strings en un solo string
  } catch (error) {
    // ========================================
    // MANEJO DE ERRORES
    // ========================================
    console.error("Error:", error)
    document.getElementById("tablaClientes").innerHTML =
      '<tr><td colspan="7" class="text-center error">Error al cargar clientes</td></tr>'
  }
}

/**
 * Mostrar formulario para crear nuevo cliente
 * Limpia el formulario y configura modo "Nuevo"
 */
function mostrarFormulario() {
  editando = false // Modo creación
  document.getElementById("formularioCliente").style.display = "block"
  document.getElementById("tituloForm").textContent = "Nuevo Cliente"
  document.getElementById("formCliente").reset() // Limpiar campos
  document.getElementById("clienteId").value = "" // ID vacío = nuevo

  // Establecer fecha actual como fecha de alta por defecto
  document.getElementById("fecha_alta").valueAsDate = new Date()
}

/**
 * Ocultar formulario y limpiar campos
 */
function ocultarFormulario() {
  document.getElementById("formularioCliente").style.display = "none"
  document.getElementById("formCliente").reset()
}

/**
 * Cargar datos de un cliente en el formulario para editar
 * @param {number} id - ID del cliente a editar
 */
async function editarCliente(id) {
  try {
    // ========================================
    // PASO 1: OBTENER DATOS DEL CLIENTE
    // ========================================
    const response = await fetch(`api/clientes.php?id=${id}`)
    const cliente = await response.json()

    // ========================================
    // PASO 2: LLENAR FORMULARIO CON LOS DATOS
    // ========================================
    editando = true // Activar modo edición
    document.getElementById("formularioCliente").style.display = "block"
    document.getElementById("tituloForm").textContent = "Editar Cliente"

    // Llenar cada campo del formulario
    document.getElementById("clienteId").value = cliente.id
    document.getElementById("nombre").value = cliente.nombre
    document.getElementById("tipo_cliente_id").value = cliente.tipo_cliente_id
    document.getElementById("telefono").value = cliente.telefono || ""
    document.getElementById("direccion").value = cliente.direccion || ""
    document.getElementById("fecha_alta").value = cliente.fecha_alta || ""
  } catch (error) {
    alert("Error al cargar cliente")
  }
}

/**
 * Eliminar un cliente con confirmación del usuario
 * @param {number} id - ID del cliente a eliminar
 */
async function eliminarCliente(id) {
  // ========================================
  // PASO 1: CONFIRMAR ACCIÓN
  // ========================================
  if (!confirm("¿Está seguro de eliminar este cliente?")) return

  try {
    // ========================================
    // PASO 2: PETICIÓN DELETE al API
    // ========================================
    const response = await fetch(`api/clientes.php?id=${id}`, {
      method: "DELETE",
    })

    const data = await response.json()

    if (data.success) {
      alert("Cliente eliminado exitosamente")
      cargarClientes() // Recargar tabla
    } else {
      alert("Error al eliminar cliente")
    }
  } catch (error) {
    alert("Error de conexión")
  }
}

/**
 * EVENTO: Submit del formulario de cliente
 * Maneja tanto creación como actualización según el modo
 */
document.getElementById("formCliente").addEventListener("submit", async (e) => {
  // Prevenir recarga de página
  e.preventDefault()

  // ========================================
  // PASO 1: RECOLECTAR DATOS DEL FORMULARIO
  // ========================================
  const cliente = {
    id: document.getElementById("clienteId").value,
    nombre: document.getElementById("nombre").value,
    tipo_cliente_id: document.getElementById("tipo_cliente_id").value,
    telefono: document.getElementById("telefono").value,
    direccion: document.getElementById("direccion").value,
    fecha_alta: document.getElementById("fecha_alta").value,
    activo: 1,
  }

  try {
    // ========================================
    // PASO 2: ENVIAR AL API (POST o PUT)
    // ========================================
    const url = "api/clientes.php"
    const method = editando ? "PUT" : "POST" // PUT si edita, POST si crea

    const response = await fetch(url, {
      method: method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(cliente),
    })

    const data = await response.json()

    // ========================================
    // PASO 3: PROCESAR RESPUESTA
    // ========================================
    if (data.success) {
      alert(editando ? "Cliente actualizado" : "Cliente creado")
      ocultarFormulario()
      cargarClientes() // Recargar tabla
    } else {
      alert(data.error || "Error al guardar")
    }
  } catch (error) {
    alert("Error de conexión")
  }
})

// ========================================
// INICIALIZACIÓN: Cargar clientes al cargar la página
// ========================================
cargarClientes()
