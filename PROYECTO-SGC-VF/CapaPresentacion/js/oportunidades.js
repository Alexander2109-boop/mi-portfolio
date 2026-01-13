// Arreglos donde se guardarán los datos cargados desde la API
let oportunidades = []
let clientes = []

// ================================
// CARGAR CLIENTES
// ================================
async function cargarClientes() {
  try {
    // Solicita la lista de clientes desde el backend
    const response = await fetch("api/clientes.php")
    clientes = await response.json()

    // Obtiene el <select> donde se mostrarán los clientes
    const select = document.getElementById("cliente_id")

    // Opción inicial
    select.innerHTML = '<option value="">Seleccione un cliente</option>'

    // Agrega cada cliente como opción
    clientes.forEach((cliente) => {
      select.innerHTML += `<option value="${cliente.id}">${cliente.nombre}</option>`
    })
  } catch (error) {
    console.error("Error al cargar clientes:", error)
  }
}

// ================================
// CARGAR OPORTUNIDADES
// ================================
async function cargarOportunidades() {
  try {
    // Solicita la lista de oportunidades
    const response = await fetch("api/oportunidades.php")
    oportunidades = await response.json()

    // Llama a la función para mostrarlas en la tabla
    mostrarOportunidades()
  } catch (error) {
    console.error("Error al cargar oportunidades:", error)

    // Si hay error, muestra un mensaje dentro de la tabla
    document.getElementById("tablaOportunidades").innerHTML =
      '<tr><td colspan="7" class="text-center">Error al cargar datos</td></tr>'
  }
}

// ================================
// MOSTRAR OPORTUNIDADES EN LA TABLA
// ================================
function mostrarOportunidades() {
  const tbody = document.getElementById("tablaOportunidades")

  // Si la lista está vacía, muestra un mensaje
  if (oportunidades.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay oportunidades registradas</td></tr>'
    return
  }

  // Llena la tabla usando map para generar cada fila
  tbody.innerHTML = oportunidades
    .map((op) => {
      // Busca el cliente correspondiente
      const cliente = clientes.find((c) => c.id == op.cliente_id)

      // Lista de estados según su ID
      const estados = ["", "EN PROCESO", "GANADA", "PERDIDA"]

      return `
            <tr>
                <td>${op.id}</td>
                <td>${cliente ? cliente.nombre : "N/A"}</td>

                <!-- Etiqueta de estado con colores dinámicos -->
                <td>
                  <span class="badge badge-${
                    op.estado_oportunidad_id == 1
                      ? "blue"
                      : op.estado_oportunidad_id == 2
                      ? "green"
                      : "red"
                  }">
                    ${estados[op.estado_oportunidad_id]}
                  </span>
                </td>

                <!-- Fecha formateada -->
                <td>${new Date(op.fecha_hora).toLocaleString()}</td>

                <!--Monto formateado a 2 decimales-->
                <td>$${Number.parseFloat(op.monto).toFixed(2)}</td>

                <td>${op.descripcion || "N/A"}</td>

                <!-- Botones de acción -->
                <td>
                    <button class="btn btn-sm btn-secondary" onclick="editarOportunidad(${op.id})">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarOportunidad(${op.id})">Eliminar</button>
                </td>
            </tr>
        `
    })
    .join("")
}

// ================================
// MOSTRAR FORMULARIO DE REGISTRO
// ================================
function mostrarFormulario() {
  document.getElementById("formularioOportunidad").style.display = "block"

  // Limpia los campos del formulario
  document.getElementById("formOportunidad").reset()

  // El ID vacío indica que será un registro nuevo
  document.getElementById("oportunidadId").value = ""

  // Establece fecha/hora actual en formato compatible con <input type="datetime-local">
  document.getElementById("fecha_hora").value = new Date().toISOString().slice(0, 16)
}

// Oculta el formulario
function ocultarFormulario() {
  document.getElementById("formularioOportunidad").style.display = "none"
}

// ================================
// GUARDAR / EDITAR OPORTUNIDAD
// ================================
document.getElementById("formOportunidad").addEventListener("submit", async (e) => {
  e.preventDefault()

  // Convierte los datos del formulario a un objeto JS
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    // Envía la información al backend
    const response = await fetch("api/oportunidades.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Oportunidad guardada exitosamente")
      ocultarFormulario()
      cargarOportunidades()
    } else {
      alert("Error al guardar oportunidad")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

// ================================
// ELIMINAR OPORTUNIDAD
// ================================
async function eliminarOportunidad(id) {
  // Confirmación del usuario
  if (!confirm("¿Está seguro de eliminar esta oportunidad?")) return

  try {
    const response = await fetch(`api/oportunidades.php?id=${id}`, {
      method: "DELETE",
    })

    if (response.ok) {
      alert("Oportunidad eliminada")
      cargarOportunidades()
    } else {
      alert("Error al eliminar")
    }
  } catch (error) {
    console.error("Error:", error)
  }
}

// ================================
// EDITAR OPORTUNIDAD (CARGA EN FORMULARIO)
// ================================
function editarOportunidad(id) {
  // Busca la oportunidad por ID
  const op = oportunidades.find((o) => o.id == id)
  if (!op) return

  // Llena los campos del formulario con los datos existentes
  document.getElementById("oportunidadId").value = op.id
  document.getElementById("cliente_id").value = op.cliente_id
  document.getElementById("estado_oportunidad_id").value = op.estado_oportunidad_id
  document.getElementById("fecha_hora").value = op.fecha_hora.slice(0, 16) // Formato compatible con datetime-local
  document.getElementById("monto").value = op.monto
  document.getElementById("descripcion").value = op.descripcion || ""

  // Muestra el formulario
  mostrarFormulario()
}

// ================================
// CARGA INICIAL DE DATOS
// ================================
cargarClientes()
cargarOportunidades()
