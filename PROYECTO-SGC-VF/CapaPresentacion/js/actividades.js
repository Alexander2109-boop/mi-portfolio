let actividades = []      // Arreglo donde se guardarán las actividades cargadas desde la API
let oportunidades = []    // Arreglo donde se guardarán las oportunidades cargadas desde la API

// =============================
// Cargar oportunidades desde API
// =============================
async function cargarOportunidades() {
  try {
    const response = await fetch("api/oportunidades.php") // Solicita la lista de oportunidades
    oportunidades = await response.json() // Convierte la respuesta a JSON

    const select = document.getElementById("oportunidad_id") // Obtiene el selector del formulario

    // Reinicia el select con una opción por defecto
    select.innerHTML = '<option value="">Seleccione una oportunidad</option>'

    // Agrega cada oportunidad como opción dentro del select
    oportunidades.forEach((op) => {
      select.innerHTML += `<option value="${op.id}">Oportunidad #${op.id} - ${op.descripcion || "Sin descripción"}</option>`
    })
  } catch (error) {
    console.error("Error al cargar oportunidades:", error) // Error en consola
  }
}

// =============================
// Cargar actividades desde API
// =============================
async function cargarActividades() {
  try {
    const response = await fetch("api/actividades.php") // Solicita todas las actividades
    actividades = await response.json() // Convierte la respuesta en JSON
    mostrarActividades() // Renderiza las actividades en la tabla
  } catch (error) {
    console.error("Error al cargar actividades:", error)
    document.getElementById("tablaActividades").innerHTML =
      '<tr><td colspan="6" class="text-center">Error al cargar datos</td></tr>'
  }
}

// =============================
// Mostrar actividades en tabla
// =============================
function mostrarActividades() {
  const tbody = document.getElementById("tablaActividades")

  // Si no hay actividades registradas, muestra mensaje
  if (actividades.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay actividades registradas</td></tr>'
    return
  }

  // Si hay actividades, genera dinámicamente las filas
  tbody.innerHTML = actividades
    .map((act) => {
      const tipos = ["", "LLAMADA", "EMAIL", "REUNION", "OTRO"] // Tipos de actividad
      return `
            <tr>
                <td>${act.id}</td>
                <td>Oportunidad #${act.oportunidad_id}</td>
                <td><span class="badge badge-blue">${tipos[act.tipo_actividad_id]}</span></td>
                <td>${new Date(act.fecha_hora).toLocaleString()}</td>
                <td>${act.descripcion || "N/A"}</td>
                <td>
                    <button class="btn btn-sm btn-secondary" onclick="editarActividad(${act.id})">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarActividad(${act.id})">Eliminar</button>
                </td>
            </tr>
        `
    })
    .join("") // Une todas las filas generadas en una sola cadena HTML
}

// =============================
// Mostrar formulario para agregar/editar
// =============================
function mostrarFormulario() {
  document.getElementById("formularioActividad").style.display = "block" // Muestra el formulario
  document.getElementById("formActividad").reset() // Reinicia inputs
  document.getElementById("actividadId").value = "" // Limpia ID (modo crear)
  document.getElementById("fecha_hora").value = new Date().toISOString().slice(0, 16) // Fecha actual por defecto
}

// Oculta el formulario
function ocultarFormulario() {
  document.getElementById("formularioActividad").style.display = "none"
}

// ============================================================
// Evento de submit del formulario (crear o actualizar actividad)
// ============================================================
document.getElementById("formActividad").addEventListener("submit", async (e) => {
  e.preventDefault() // Evita que el formulario recargue la página

  const formData = new FormData(e.target) // Obtiene los datos del formulario
  const data = Object.fromEntries(formData) // Convierte a objeto simple

  try {
    const response = await fetch("api/actividades.php", {
      method: "POST", // Método POST (el backend decide si crear o actualizar)
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data), // Convierte datos del formulario a JSON
    })

    if (response.ok) {
      alert("Actividad guardada exitosamente")
      ocultarFormulario()
      cargarActividades() // Recarga tabla
    } else {
      alert("Error al guardar actividad")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

// =============================
// Eliminar una actividad
// =============================
async function eliminarActividad(id) {
  if (!confirm("¿Está seguro de eliminar esta actividad?")) return // Confirmación del usuario

  try {
    const response = await fetch(`api/actividades.php?id=${id}`, {
      method: "DELETE",
    })

    if (response.ok) {
      alert("Actividad eliminada")
      cargarActividades()
    } else {
      alert("Error al eliminar")
    }
  } catch (error) {
    console.error("Error:", error)
  }
}

// =============================
// Editar una actividad (cargar valores en el form)
// =============================
function editarActividad(id) {
  const act = actividades.find((a) => a.id == id) // Busca la actividad en el arreglo local
  if (!act) return

  // Carga los datos de la actividad en los campos del formulario
  document.getElementById("actividadId").value = act.id
  document.getElementById("oportunidad_id").value = act.oportunidad_id
  document.getElementById("tipo_actividad_id").value = act.tipo_actividad_id
  document.getElementById("fecha_hora").value = act.fecha_hora.slice(0, 16)
  document.getElementById("descripcion").value = act.descripcion || ""

  mostrarFormulario() // Muestra el formulario en modo edición
}

// =============================
// Cargar datos iniciales al entrar en la página
// =============================
cargarOportunidades()
cargarActividades()
