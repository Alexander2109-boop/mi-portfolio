let contactos = []
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

async function cargarContactos() {
  try {
    const response = await fetch("api/contactos.php")
    contactos = await response.json()
    mostrarContactos()
  } catch (error) {
    console.error("Error al cargar contactos:", error)
    document.getElementById("tablaContactos").innerHTML =
      '<tr><td colspan="7" class="text-center">Error al cargar datos</td></tr>'
  }
}

function mostrarContactos() {
  const tbody = document.getElementById("tablaContactos")
  if (contactos.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay contactos registrados</td></tr>'
    return
  }

  tbody.innerHTML = contactos
    .map((contacto) => {
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
    .join("")
}

function mostrarFormulario() {
  document.getElementById("formularioContacto").style.display = "block"
  document.getElementById("formContacto").reset()
  document.getElementById("contactoId").value = ""
}

function ocultarFormulario() {
  document.getElementById("formularioContacto").style.display = "none"
}

document.getElementById("formContacto").addEventListener("submit", async (e) => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    const response = await fetch("api/contactos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Contacto guardado exitosamente")
      ocultarFormulario()
      cargarContactos()
    } else {
      alert("Error al guardar contacto")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

async function eliminarContacto(id) {
  if (!confirm("¿Está seguro de eliminar este contacto?")) return

  try {
    const response = await fetch(`api/contactos.php?id=${id}`, {
      method: "DELETE",
    })

    if (response.ok) {
      alert("Contacto eliminado")
      cargarContactos()
    } else {
      alert("Error al eliminar")
    }
  } catch (error) {
    console.error("Error:", error)
  }
}

function editarContacto(id) {
  const contacto = contactos.find((c) => c.id == id)
  if (!contacto) return

  document.getElementById("contactoId").value = contacto.id
  document.getElementById("cliente_id").value = contacto.cliente_id
  document.getElementById("nombre").value = contacto.nombre
  document.getElementById("cargo").value = contacto.cargo || ""
  document.getElementById("email").value = contacto.email || ""
  document.getElementById("telefono").value = contacto.telefono || ""

  mostrarFormulario()
}

cargarClientes()
cargarContactos()
