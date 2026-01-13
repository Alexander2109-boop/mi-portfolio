// Arreglo donde se almacenarán los productos cargados desde la API
let productos = []

// ================================
// CARGAR PRODUCTOS DESDE EL SERVIDOR
// ================================
async function cargarProductos() {
  try {
    // Solicita los productos al backend
    const response = await fetch("api/productos.php")
    productos = await response.json()

    // Muestra los productos en la tabla
    mostrarProductos()
  } catch (error) {
    console.error("Error al cargar productos:", error)

    // Si ocurre un error, se muestra un mensaje en la tabla
    document.getElementById("tablaProductos").innerHTML =
      '<tr><td colspan="6" class="text-center">Error al cargar datos</td></tr>'
  }
}

// ================================
// MOSTRAR PRODUCTOS EN LA TABLA
// ================================
function mostrarProductos() {
  const tbody = document.getElementById("tablaProductos")

  // Si no hay productos, muestra un mensaje informativo
  if (productos.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay productos registrados</td></tr>'
    return
  }

  // Genera las filas de la tabla dinámicamente con la información del producto
  tbody.innerHTML = productos
    .map(
      (prod) => `
        <tr>
            <td>${prod.id}</td>
            <td>${prod.nombre}</td>
            <td>${prod.descripcion || "N/A"}</td>

            <!-- Precio formateado con dos decimales -->
            <td>$${Number.parseFloat(prod.precio).toFixed(2)}</td>

            <!-- Etiqueta visual según estado activo/inactivo -->
            <td>
              <span class="badge badge-${prod.activo == 1 ? "green" : "red"}">
                ${prod.activo == 1 ? "Activo" : "Inactivo"}
              </span>
            </td>

            <!-- Botones para editar y eliminar -->
            <td>
                <button class="btn btn-sm btn-secondary" onclick="editarProducto(${prod.id})">Editar</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${prod.id})">Eliminar</button>
            </td>
        </tr>
    `,
    )
    .join("")
}

// ================================
// MOSTRAR FORMULARIO PARA CREAR / EDITAR PRODUCTO
// ================================
function mostrarFormulario() {
  document.getElementById("formularioProducto").style.display = "block"

  // Limpia todos los campos del formulario
  document.getElementById("formProducto").reset()

  // Deja el ID vacío para indicar nuevo registro
  document.getElementById("productoId").value = ""
}

// Ocultar formulario
function ocultarFormulario() {
  document.getElementById("formularioProducto").style.display = "none"
}

// ================================
// GUARDAR PRODUCTO (NUEVO O EDITADO)
// ================================
document.getElementById("formProducto").addEventListener("submit", async (e) => {
  e.preventDefault()

  // Convierte los valores del formulario en un objeto JS
  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  try {
    // Envía la información al backend mediante POST
    const response = await fetch("api/productos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })

    if (response.ok) {
      alert("Producto guardado exitosamente")
      ocultarFormulario()
      cargarProductos() // Refresca la tabla
    } else {
      alert("Error al guardar producto")
    }
  } catch (error) {
    console.error("Error:", error)
    alert("Error de conexión")
  }
})

// ================================
// ELIMINAR UN PRODUCTO
// ================================
async function eliminarProducto(id) {
  // Confirma si el usuario quiere eliminar
  if (!confirm("¿Está seguro de eliminar este producto?")) return

  try {
    // Solicita al backend que elimine el producto
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

// ================================
// EDITAR PRODUCTO (CARGA DATOS EN EL FORMULARIO)
// ================================
function editarProducto(id) {
  // Busca el producto por su ID
  const prod = productos.find((p) => p.id == id)
  if (!prod) return

  // Llena los campos del formulario con los datos del producto
  document.getElementById("productoId").value = prod.id
  document.getElementById("nombre").value = prod.nombre
  document.getElementById("descripcion").value = prod.descripcion || ""
  document.getElementById("precio").value = prod.precio
  document.getElementById("activo").value = prod.activo

  // Muestra el formulario
  mostrarFormulario()
}

// ================================
// CARGA INICIAL DE PRODUCTOS
// ================================
cargarProductos()
