<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_detalles_pedidos_dto.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Factura');
// $pdf->cell(33.375, 10, $_SESSION['id_usuario'], 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
// Se instancia el módelo Categoría para obtener los datos.
$pedidos = new Detalles_Pedidos;
if ($dataPedido = $pedidos->readOrderDetailFactura()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    $pdf->SetFillColor(202, 92, 245 );
    $pdf->AddPage();
    // Se establece un color de relleno para los encabezados.
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    //SELECT ROW_NUMBER() OVER(
        
    // Se imprimen las celdas con los encabezados. 186 ancho en vertical y 267 ancho en horizontal se divide en los 33.375 y se hace el calculo
    $pdf->cell(23.5, 10, 'Nº', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(23.5, 10, 'ID Producto', 1, 0, 'C', 1);
    $pdf->cell(23.5, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(23.5, 10, 'Precio', 1, 0, 'C', 1);
    $pdf->cell(23.5, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->cell(23.5, 10, 'Fecha Pedido', 1, 0, 'C', 1);
    $pdf->cell(23.5, 10, 'SubTotal', 1, 0, 'C', 1);
    $pdf->cell(23.5, 10, 'Producto', 1, 1, 'C', 1);
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 9);
    $total = 0;
    // Se recorren los registros fila por fila.
    foreach ($dataPedido as $rowPedido) {
        // subtotal = row.precio_detalle_producto * row.cantidad_detalle_producto;
        // total += subtotal;
        $subtotal = $rowPedido['precio_detalle_producto'] * $rowPedido['cantidad_detalle_producto'];
            $total +=  $subtotal;
        // Se imprime una celda con el nombre de la categoría.
        // $pdf->cell(0, 10, $pdf->encodeString('Categoría: ' . $rowUsuario['nombre_categoria']), 1, 1, 'C', 1);
        $pdf->cell(23.5, 20, $rowPedido['fila'], 1, 0,'C');
        $pdf->cell(23.5, 20, $rowPedido['id_producto'], 1, 0,'C');
        $pdf->cell(23.5, 20, $rowPedido['nombre_producto'], 1, 0,'C');
        $pdf->cell(23.5, 20, '$'.$rowPedido['precio_detalle_producto'], 1, 0,'C');
        $pdf->cell(23.5, 20, $rowPedido['cantidad_detalle_producto'], 1, 0,'C');
        $pdf->cell(23.5, 20, $rowPedido['fecha_pedido'], 1, 0,'C');
        $pdf->cell(23.5, 20, '$'.$subtotal, 1, 0,'C');
        
        $pdf->cell(23.5, 0,$pdf->Image($pdf->encodeString('../../img/productos/'.$rowPedido['imagen_producto']),null,null,23.5,20), 0, 1, 'C');                
        //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
        // $pdf->cell(20, 20, $rowPedido['estado'], 1, 1,'C');
        

    }
    $pdf->setFont('Times', 'B', 9);//fuente y negrita 
    // $pdf->SetTextColor(255,255,255);
    $pdf->SetFillColor(202, 92, 245 );//color de fondo morado 
    // $pdf->cell(0, 10, 'Fecha del Pedido '.$rowPedido['fecha_pedido'], 1, 1,'C',1);
    $pdf->cell(0, 10, 'Total: $'.$total, 1, 1, 'C', 1);
    // $pdf->cell(0, 5, 'Cliente: '.$_GET['nombre'].' '.$_GET['apellido'], 1, 1,'C');
    // $pdf->MultiCell(0, 6, 'Direccion: '.$_GET['direccion'],1,'C');

    // $pdf->cell(0, 5, 'Fecha/Hora: '.date('d-m-Y H:i:s'), 1, 1, 'C');
    $pdf->cell(0, 5, 'Fecha: '.date('d-m-Y'), 1, 1, 'C');
    // Agregamos los datos del cliente
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato persona para mostrar'), 1, 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'factura.pdf');
