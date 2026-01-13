let contactos = []     // Arreglo para almacenar los contactos cargados desde la API
let clientes = []      // Arreglo para almacenar clientes para asociar con contactos

// =============================
// Cargar lista de clientes
// =============================
async function cargarClientes() {
  try {
    const response = await fetch("api/clientes.php") // Consulta la lista de clientes
    clientes = await response.json() // Convierte la respuesta en JSON

    const select = document.getElementById("cliente_id") // Select del formulario
    select.innerHTML = '<option value="">Seleccione un cliente</option>' // Opción inicial

    // Agrega cada cliente como una opción del select
    clientes.forEach((cliente) => {
      select.innerHTML += `<option value="${cliente.id}">${cliente.nombre}</option>`
    })
  } catch (error) {
    console.error("Error al cargar clientes:", error)
  }
}

// =============================
// Cargar lista de contactos
// =============================
async function cargarContactos() {
  try {
    const response = await fetch("api/contactos.php") // Solicita contactos al servidor
    contactos = await response.json() // Convierte el JSON a arreglo
    mostrarContactos() // Llama a la función para renderizar en la tabla
  } catch (error) {
    console.error("Error al cargar contactos:", error)
    document.getElementById("tablaContactos").innerHTML =
      '<tr><td colspan="7" class="text-center">Error al cargar datos</td></tr>'
  }
}

// =============================
// Mostrar contactos en la tabla HTML
// =============================
function mostrarContactos() {
  const tbody = document.getElementById("tablaContactos")

  // Si no hay registros, muestra mensaje
  if (contactos.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay contactos registrados</td></tr>'
    return
  }

  // Genera dinámicamente las filas de la tabla
  tbody.innerHTML = contactos
    .map((contacto) => {
      // Busca el cliente asociado al contacto
      const cliente = clientes.find((c) => c.id == contacto.cliente_id)

      return `
            <tr>
                <td>${contacto.id}</td>
                <td>${cliente ? cliente.nombre : "N/A"}</td>
                <td>${contacto.nombre}</td>
                <td>${contacto.cargo || "N/A"}</td>
                <td>${contacto.email || "N/A"}</td>
                <td>${contacto.telefono || "N/A"}</td>
                <td>
                    <button class="btn btn-sm btn-secondary" onclick="editarContacto(${contacto.id})">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarContacto(${contacto.id})">Eliminar</button>
                </td>
            </tr>
        `
    })
    .join("") // Une las filas en una sola cadena HTML
}

// =============================
// Mostrar formulario de registro/edición
// =============================
function mostrarFormulario() {
  document.getElementById("formularioContacto").style.display = "block" // Muestra formulario
  document.getElementById("formContacto").reset() // Limpia formulario
  document.getElementById("contactoId").value = "" // Limpia el ID (modo crear)
}

// Ocultar formulario
function ocultarFormulario() {
  document.getElementById("formularioContacto").style.display = "none"
}

// ===============================================
// Guardar contacto (crear o actualizar según ID)
// ===============================================
document.getElementById("formContacto").addEventListener("submit", async (e) => {
  e.preventDefault() // Evita recargar página

  const formData = new FormData(e.target) // Obtiene datos del formulario
  const data = Object.fromEntries(formData) // Convierte a objeto simple

  try {
    const response = await fetch("api/contactos.php", {
      method: "POST", // Se usa POST para agregar o editar
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data), // Convierte los datos a JSON
    })

    if (response.ok) {
      alert("Contacto guardado exitosamente")
      ocultarFormulario()
      cargarContactos() // Refresca la tabla
    } else {
      alert("Error al guardar contacto")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

// =============================
// Eliminar contacto
// =============================
async function eliminarContacto(id) {
  if (!confirm("¿Está seguro de eliminar este contacto?")) return // Confirmación del usuario

  try {
    const response = await fetch(`api/contactos.php?id=${id}`, {
      method: "DELETE", // Se envía método DELETE para borrar
    })

    if (response.ok) {
      alert("Contacto eliminado")
      cargarContactos() // Recargar lista
    } else {
      alert("Error al eliminar")
    }
  } catch (error) {
    console.error("Error:", error)
  }
}

// =============================
// Cargar datos en formulario (modo edición)
// =============================
function editarContacto(id) {
  const contacto = contactos.find((c) => c.id == id) // Busca contacto por ID
  if (!contacto) return

  // Cargar valores en el formulario
  document.getElementById("contactoId").value = contacto.id
  document.getElementById("cliente_id").value = contacto.cliente_id
  document.getElementById("nombre").value = contacto.nombre
  document.getElementById("cargo").value = contacto.cargo || ""
  document.getElementById("email").value = contacto.email || ""
  document.getElementById("telefono").value = contacto.telefono || ""

  mostrarFormulario() // Muestra formulario para editar
}

// =============================
// Carga inicial de datos
// =============================
cargarClientes()
cargarContactos()
