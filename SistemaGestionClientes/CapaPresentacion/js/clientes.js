let editando = false

async function cargarClientes() {
  try {
    const response = await fetch("api/clientes.php")
    const clientes = await response.json()

    const tbody = document.getElementById("tablaClientes")

    if (clientes.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay clientes registrados</td></tr>'
      return
    }

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
      .join("")
  } catch (error) {
    console.error("Error:", error)
    document.getElementById("tablaClientes").innerHTML =
      '<tr><td colspan="7" class="text-center error">Error al cargar clientes</td></tr>'
  }
}

function mostrarFormulario() {
  editando = false
  document.getElementById("formularioCliente").style.display = "block"
  document.getElementById("tituloForm").textContent = "Nuevo Cliente"
  document.getElementById("formCliente").reset()
  document.getElementById("clienteId").value = ""
  document.getElementById("fecha_alta").valueAsDate = new Date()
}

function ocultarFormulario() {
  document.getElementById("formularioCliente").style.display = "none"
  document.getElementById("formCliente").reset()
}

async function editarCliente(id) {
  try {
    const response = await fetch(`api/clientes.php?id=${id}`)
    const cliente = await response.json()

    editando = true
    document.getElementById("formularioCliente").style.display = "block"
    document.getElementById("tituloForm").textContent = "Editar Cliente"
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

async function eliminarCliente(id) {
  if (!confirm("¿Está seguro de eliminar este cliente?")) return

  try {
    const response = await fetch(`api/clientes.php?id=${id}`, {
      method: "DELETE",
    })

    const data = await response.json()
    if (data.success) {
      alert("Cliente eliminado exitosamente")
      cargarClientes()
    } else {
      alert("Error al eliminar cliente")
    }
  } catch (error) {
    alert("Error de conexión")
  }
}

document.getElementById("formCliente").addEventListener("submit", async (e) => {
  e.preventDefault()

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
    const url = "api/clientes.php"
    const method = editando ? "PUT" : "POST"

    const response = await fetch(url, {
      method: method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(cliente),
    })

    const data = await response.json()
    if (data.success) {
      alert(editando ? "Cliente actualizado" : "Cliente creado")
      ocultarFormulario()
      cargarClientes()
    } else {
      alert(data.error || "Error al guardar")
    }
  } catch (error) {
    alert("Error de conexión")
  }
})

// Cargar al iniciar
cargarClientes()
