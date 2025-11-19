let actividades = []
let oportunidades = []

async function cargarOportunidades() {
  try {
    const response = await fetch("api/oportunidades.php")
    oportunidades = await response.json()
    const select = document.getElementById("oportunidad_id")
    select.innerHTML = '<option value="">Seleccione una oportunidad</option>'
    oportunidades.forEach((op) => {
      select.innerHTML += `<option value="${op.id}">Oportunidad #${op.id} - ${op.descripcion || "Sin descripción"}</option>`
    })
  } catch (error) {
    console.error("Error al cargar oportunidades:", error)
  }
}

async function cargarActividades() {
  try {
    const response = await fetch("api/actividades.php")
    actividades = await response.json()
    mostrarActividades()
  } catch (error) {
    console.error("Error al cargar actividades:", error)
    document.getElementById("tablaActividades").innerHTML =
      '<tr><td colspan="6" class="text-center">Error al cargar datos</td></tr>'
  }
}

function mostrarActividades() {
  const tbody = document.getElementById("tablaActividades")
  if (actividades.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay actividades registradas</td></tr>'
    return
  }

  tbody.innerHTML = actividades
    .map((act) => {
      const tipos = ["", "LLAMADA", "EMAIL", "REUNION", "OTRO"]
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
    .join("")
}

function mostrarFormulario() {
  document.getElementById("formularioActividad").style.display = "block"
  document.getElementById("formActividad").reset()
  document.getElementById("actividadId").value = ""
  document.getElementById("fecha_hora").value = new Date().toISOString().slice(0, 16)
}

function ocultarFormulario() {
  document.getElementById("formularioActividad").style.display = "none"
}

document.getElementById("formActividad").addEventListener("submit", async (e) => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    const response = await fetch("api/actividades.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Actividad guardada exitosamente")
      ocultarFormulario()
      cargarActividades()
    } else {
      alert("Error al guardar actividad")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

async function eliminarActividad(id) {
  if (!confirm("¿Está seguro de eliminar esta actividad?")) return

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

function editarActividad(id) {
  const act = actividades.find((a) => a.id == id)
  if (!act) return

  document.getElementById("actividadId").value = act.id
  document.getElementById("oportunidad_id").value = act.oportunidad_id
  document.getElementById("tipo_actividad_id").value = act.tipo_actividad_id
  document.getElementById("fecha_hora").value = act.fecha_hora.slice(0, 16)
  document.getElementById("descripcion").value = act.descripcion || ""

  mostrarFormulario()
}

cargarOportunidades()
cargarActividades()
