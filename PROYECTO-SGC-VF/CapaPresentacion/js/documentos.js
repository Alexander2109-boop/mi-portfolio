// Arreglos donde se almacenarán los documentos y oportunidades cargadas del servidor
let documentos = []
let oportunidades = []

// Función para cargar las oportunidades desde la API
async function cargarOportunidades() {
  try {
    // Solicita datos de oportunidades
    const response = await fetch("api/oportunidades.php")
    oportunidades = await response.json()

    // Obtiene el <select> donde se mostrarán
    const select = document.getElementById("oportunidad_id")

    // Agrega opción inicial
    select.innerHTML = '<option value="">Seleccione una oportunidad</option>'

    // Recorre cada oportunidad y la agrega como opción
    oportunidades.forEach((op) => {
      select.innerHTML += `<option value="${op.id}">Oportunidad #${op.id} - ${op.descripcion || "Sin descripción"}</option>`
    })
  } catch (error) {
    console.error("Error al cargar oportunidades:", error)
  }
}

// Función para cargar los documentos desde la API
async function cargarDocumentos() {
  try {
    // Solicita documentos
    const response = await fetch("api/documentos.php")
    documentos = await response.json()

    // Muestra documentos en la tabla
    mostrarDocumentos()
  } catch (error) {
    console.error("Error al cargar documentos:", error)

    // Muestra mensaje de error en la tabla
    document.getElementById("tablaDocumentos").innerHTML =
      '<tr><td colspan="6" class="text-center">Error al cargar datos</td></tr>'
  }
}

// Función que imprime los documentos en la tabla
function mostrarDocumentos() {
  const tbody = document.getElementById("tablaDocumentos")

  // Si no hay documentos, muestra un mensaje
  if (documentos.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay documentos registrados</td></tr>'
    return
  }

  // Genera cada fila de la tabla usando los datos
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

// Muestra el formulario y limpia valores
function mostrarFormulario() {
  document.getElementById("formularioDocumento").style.display = "block"
  document.getElementById("formDocumento").reset()
  document.getElementById("documentoId").value = ""
}

// Oculta el formulario
function ocultarFormulario() {
  document.getElementById("formularioDocumento").style.display = "none"
}

// Maneja el envío del formulario para crear/editar documentos
document.getElementById("formDocumento").addEventListener("submit", async (e) => {
  e.preventDefault()

  // Convierte los datos del formulario a objeto
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    // Envía los datos a la API
    const response = await fetch("api/documentos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Documento guardado exitosamente")
      ocultarFormulario()
      cargarDocumentos() // Recarga la tabla
    } else {
      alert("Error al guardar documento")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

// Función para eliminar un documento
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

// Carga los datos de un documento en el formulario para edición
function editarDocumento(id) {
  const doc = documentos.find((d) => d.id == id)
  if (!doc) return

  // Rellena cada campo del formulario
  document.getElementById("documentoId").value = doc.id
  document.getElementById("oportunidad_id").value = doc.oportunidad_id
  document.getElementById("nombre").value = doc.nombre
  document.getElementById("url").value = doc.url
  document.getElementById("tipo").value = doc.tipo || ""

  mostrarFormulario()
}

// Ejecuta carga inicial de datos
cargarOportunidades()
cargarDocumentos()
