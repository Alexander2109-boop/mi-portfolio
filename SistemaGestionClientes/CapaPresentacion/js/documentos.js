let documentos = []
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

async function cargarDocumentos() {
  try {
    const response = await fetch("api/documentos.php")
    documentos = await response.json()
    mostrarDocumentos()
  } catch (error) {
    console.error("Error al cargar documentos:", error)
    document.getElementById("tablaDocumentos").innerHTML =
      '<tr><td colspan="6" class="text-center">Error al cargar datos</td></tr>'
  }
}

function mostrarDocumentos() {
  const tbody = document.getElementById("tablaDocumentos")
  if (documentos.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay documentos registrados</td></tr>'
    return
  }

  tbody.innerHTML = documentos
    .map(
      (doc) => `
        <tr>
            <td>${doc.id}</td>
            <td>Oportunidad #${doc.oportunidad_id}</td>
            <td><a href="${doc.url}" target="_blank">${doc.nombre}</a></td>
            <td>${doc.tipo || "N/A"}</td>
            <td>${new Date(doc.fecha_subida).toLocaleDateString()}</td>
            <td>
                <button class="btn btn-sm btn-secondary" onclick="editarDocumento(${doc.id})">Editar</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarDocumento(${doc.id})">Eliminar</button>
            </td>
        </tr>
    `,
    )
    .join("")
}

function mostrarFormulario() {
  document.getElementById("formularioDocumento").style.display = "block"
  document.getElementById("formDocumento").reset()
  document.getElementById("documentoId").value = ""
}

function ocultarFormulario() {
  document.getElementById("formularioDocumento").style.display = "none"
}

document.getElementById("formDocumento").addEventListener("submit", async (e) => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    const response = await fetch("api/documentos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Documento guardado exitosamente")
      ocultarFormulario()
      cargarDocumentos()
    } else {
      alert("Error al guardar documento")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

async function eliminarDocumento(id) {
  if (!confirm("¿Está seguro de eliminar este documento?")) return

  try {
    const response = await fetch(`api/documentos.php?id=${id}`, {
      method: "DELETE",
    })

    if (response.ok) {
      alert("Documento eliminado")
      cargarDocumentos()
    } else {
      alert("Error al eliminar")
    }
  } catch (error) {
    console.error("Error:", error)
  }
}

function editarDocumento(id) {
  const doc = documentos.find((d) => d.id == id)
  if (!doc) return

  document.getElementById("documentoId").value = doc.id
  document.getElementById("oportunidad_id").value = doc.oportunidad_id
  document.getElementById("nombre").value = doc.nombre
  document.getElementById("url").value = doc.url
  document.getElementById("tipo").value = doc.tipo || ""

  mostrarFormulario()
}

cargarOportunidades()
cargarDocumentos()
