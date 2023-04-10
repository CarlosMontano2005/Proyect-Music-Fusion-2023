<?php
require_once('../../helpers/report.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web principal.
if (isset($_GET['id_categoria'])) {
    require_once('../../entities/dto/categoria.php');
    require_once('../../entities/dto/producto.php');
    // Se instancia el módelo Categorias para procesar los datos.
    $categoria = new Categoria;
    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web principal.
    if ($categoria->setId($_GET['id_categoria'])) {
        // Se verifica si la categoría del parámetro existe, de lo contrario se direcciona a la página web principal.
        if ($rowCategoria = $categoria->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Productos de la categoría '.$rowCategoria['nombre_categoria']);
            // Se instancia el módelo Productos para procesar los datos.
            $producto = new Producto;
            // Se establece la categoría, de lo contrario se direcciona a la página web principal.
            if ($producto->setCategoria($rowCategoria['id_categoria'])) {
                // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
                if ($dataProductos = $producto->productosCategoria()) {
                    // Se establece un color de relleno para los encabezados.
                    $pdf->setFillColor(225);
                    // Se establece la fuente para los encabezados.
                    $pdf->setFont('Times', 'B', 11);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(126, 10, 'Nombre', 1, 0, 'C', 1);
                    $pdf->cell(30, 10, 'Precio (US$)', 1, 0, 'C', 1);
                    $pdf->cell(30, 10, 'Estado', 1, 1, 'C', 1);
                    // Se establece la fuente para los datos de los productos.
                    $pdf->setFont('Times', '', 11);
                    // Se recorren los registros fila por fila.
                    foreach ($dataProductos as $rowProducto) {
                        ($rowProducto['estado_producto']) ? $estado = 'Activo' : $estado = 'Inactivo';
                        // Se imprimen las celdas con los datos de los productos.
                        $pdf->cell(126, 10, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0);
                        $pdf->cell(30, 10, $rowProducto['precio_producto'], 1, 0);
                        $pdf->cell(30, 10, $estado, 1, 1);
                    }
                } else {
                    $pdf->cell(0, 10, $pdf->encodeString('No hay productos para esta categoría'), 1, 1);
                }
                // Se llama implícitamente al método footer() y se envía el documento al navegador web.
                $pdf->output('I', 'categoria.pdf');
            } else {
                header('location:'.$pdf::CLIENT_URL);
            }
        } else {
            header('location:'.$pdf::CLIENT_URL);
        }
    } else {
        header('location:'.$pdf::CLIENT_URL);
    }
} else {
    header('location:'.$pdf::CLIENT_URL);
}
