let oportunidades = []
let clientes = []

async function cargarClientes() {
  try {
    const response = await fetch("api/clientes.php")
    clientes = await response.json()
    const select = document.getElementById("cliente_id")
    select.innerHTML = '<option value="">Seleccione un cliente</option>'
    clientes.forEach((cliente) => {
      select.innerHTML += `<option value="${cliente.id}">${cliente.nombre}</option>`
    })
  } catch (error) {
    console.error("Error al cargar clientes:", error)
  }
}

async function cargarOportunidades() {
  try {
    const response = await fetch("api/oportunidades.php")
    oportunidades = await response.json()
    mostrarOportunidades()
  } catch (error) {
    console.error("Error al cargar oportunidades:", error)
    document.getElementById("tablaOportunidades").innerHTML =
      '<tr><td colspan="7" class="text-center">Error al cargar datos</td></tr>'
  }
}

function mostrarOportunidades() {
  const tbody = document.getElementById("tablaOportunidades")
  if (oportunidades.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay oportunidades registradas</td></tr>'
    return
  }

  tbody.innerHTML = oportunidades
    .map((op) => {
      const cliente = clientes.find((c) => c.id == op.cliente_id)
      const estados = ["", "EN PROCESO", "GANADA", "PERDIDA"]
      return `
            <tr>
                <td>${op.id}</td>
                <td>${cliente ? cliente.nombre : "N/A"}</td>
                <td><span class="badge badge-${op.estado_oportunidad_id == 1 ? "blue" : op.estado_oportunidad_id == 2 ? "green" : "red"}">${estados[op.estado_oportunidad_id]}</span></td>
                <td>${new Date(op.fecha_hora).toLocaleString()}</td>
                <td>$${Number.parseFloat(op.monto).toFixed(2)}</td>
                <td>${op.descripcion || "N/A"}</td>
                <td>
                    <button class="btn btn-sm btn-secondary" onclick="editarOportunidad(${op.id})">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarOportunidad(${op.id})">Eliminar</button>
                </td>
            </tr>
        `
    })
    .join("")
}

function mostrarFormulario() {
  document.getElementById("formularioOportunidad").style.display = "block"
  document.getElementById("formOportunidad").reset()
  document.getElementById("oportunidadId").value = ""
  document.getElementById("fecha_hora").value = new Date().toISOString().slice(0, 16)
}

function ocultarFormulario() {
  document.getElementById("formularioOportunidad").style.display = "none"
}

document.getElementById("formOportunidad").addEventListener("submit", async (e) => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
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

async function eliminarOportunidad(id) {
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

function editarOportunidad(id) {
  const op = oportunidades.find((o) => o.id == id)
  if (!op) return

  document.getElementById("oportunidadId").value = op.id
  document.getElementById("cliente_id").value = op.cliente_id
  document.getElementById("estado_oportunidad_id").value = op.estado_oportunidad_id
  document.getElementById("fecha_hora").value = op.fecha_hora.slice(0, 16)
  document.getElementById("monto").value = op.monto
  document.getElementById("descripcion").value = op.descripcion || ""

  mostrarFormulario()
}

cargarClientes()
cargarOportunidades()
