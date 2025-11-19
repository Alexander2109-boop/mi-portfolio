let productos = []

async function cargarProductos() {
  try {
    const response = await fetch("api/productos.php")
    productos = await response.json()
    mostrarProductos()
  } catch (error) {
    console.error("Error al cargar productos:", error)
    document.getElementById("tablaProductos").innerHTML =
      '<tr><td colspan="6" class="text-center">Error al cargar datos</td></tr>'
  }
}

function mostrarProductos() {
  const tbody = document.getElementById("tablaProductos")
  if (productos.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay productos registrados</td></tr>'
    return
  }

  tbody.innerHTML = productos
    .map(
      (prod) => `
        <tr>
            <td>${prod.id}</td>
            <td>${prod.nombre}</td>
            <td>${prod.descripcion || "N/A"}</td>
            <td>$${Number.parseFloat(prod.precio).toFixed(2)}</td>
            <td><span class="badge badge-${prod.activo == 1 ? "green" : "red"}">${prod.activo == 1 ? "Activo" : "Inactivo"}</span></td>
            <td>
                <button class="btn btn-sm btn-secondary" onclick="editarProducto(${prod.id})">Editar</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${prod.id})">Eliminar</button>
            </td>
        </tr>
    `,
    )
    .join("")
}

function mostrarFormulario() {
  document.getElementById("formularioProducto").style.display = "block"
  document.getElementById("formProducto").reset()
  document.getElementById("productoId").value = ""
}

function ocultarFormulario() {
  document.getElementById("formularioProducto").style.display = "none"
}

document.getElementById("formProducto").addEventListener("submit", async (e) => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    const response = await fetch("api/productos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Producto guardado exitosamente")
      ocultarFormulario()
      cargarProductos()
    } else {
      alert("Error al guardar producto")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

async function eliminarProducto(id) {
  if (!confirm("¿Está seguro de eliminar este producto?")) return

  try {
    const response = await fetch(`api/productos.php?id=${id}`, {
      method: "DELETE",
    })

    if (response.ok) {
      alert("Producto eliminado")
      cargarProductos()
    } else {
      alert("Error al eliminar")
    }
  } catch (error) {
    console.error("Error:", error)
  }
}

function editarProducto(id) {
  const prod = productos.find((p) => p.id == id)
  if (!prod) return

  document.getElementById("productoId").value = prod.id
  document.getElementById("nombre").value = prod.nombre
  document.getElementById("descripcion").value = prod.descripcion || ""
  document.getElementById("precio").value = prod.precio
  document.getElementById("activo").value = prod.activo

  mostrarFormulario()
}

cargarProductos()
