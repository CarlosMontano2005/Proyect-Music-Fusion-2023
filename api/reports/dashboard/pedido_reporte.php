<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_pedidos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Generales');
// $pdf->cell(33.375, 10, $_SESSION['id_usuario'], 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
// Se instancia el módelo Categoría para obtener los datos.
$pedidos = new ControllerPedidos;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataPedido = $pedidos->readAll()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    $pdf->AddPage();
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(175);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    // Se imprimen las celdas con los encabezados. 126 ancho en vertical y 267 ancho en horizontal se divide en los 33.375 y se hace el calculo
    $pdf->cell(20, 10, 'ID', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(40.4, 10, 'Fecha', 1, 0, 'C', 1);
    $pdf->cell(40.4, 10, 'Direccion', 1, 0, 'C', 1);
    $pdf->cell(40.4, 10, 'Cliente', 1, 0, 'C', 1);
    $pdf->cell(40.4, 10, 'Estado', 1, 1, 'C', 1);
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 9);
   
    // Se recorren los registros fila por fila.
    foreach ($dataPedido as $rowPedido) {
        // Se imprime una celda con el nombre de la categoría.
        // $pdf->cell(0, 10, $pdf->encodeString('Categoría: ' . $rowUsuario['nombre_categoria']), 1, 1, 'C', 1);
        
        $pdf->cell(20, 20, $rowPedido['id_pedido'], 1, 0,'C');
        $pdf->cell(40.4, 20, $rowPedido['fecha_pedido'], 1, 0,'C');
        $pdf->cell(40.4, 20, $rowPedido['direccion_pedido'], 1, 0,'C');
        $pdf->cell(40.4, 20, $rowPedido['nombre_cliente'], 1, 0,'C');
        //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
        $pdf->cell(40.4, 20, $rowPedido['estado_pedido'], 1, 1,'C');
        // ($rowUsuario['id_estado_usuario']) ? $estado = 'Activo' : $estado = 'Inactivo';
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato persona para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');
