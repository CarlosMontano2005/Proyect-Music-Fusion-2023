<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_detalles_pedidos_dto.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Generales');
// $pdf->cell(26.7, 10, $_SESSION['id_usuario'], 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
// Se instancia el módelo Categoría para obtener los datos.
$Detalles_Pedidos = new Detalles_Pedidos;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataDetallePedido = $Detalles_Pedidos->readAll()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    
    $pdf->AddPage();
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(175);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    // Se imprimen las celdas con los encabezados. 186 ancho en vertical y 267 ancho en horizontal se divide en los 26.7 y se hace el calculo
    $pdf->cell(37.2, 10, 'ID', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(37.2, 10, 'Producto', 1, 0, 'C', 1);
    $pdf->cell(37.2, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->cell(37.2, 10, 'Precio x U', 1, 0, 'C', 1);
    $pdf->cell(31.3, 10, 'SubTotal', 1, 1, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 9);
    // Se recorren los registros fila por fila.
    foreach ($dataDetallePedido as $rowDetalles_Pedidos) {
        $pdf->cell(0, 10, 'Numero de Pedido: '.$rowDetalles_Pedidos['id_pedido'], 1, 1,'C',1);
         // Se instancia el módelo Producto para procesar los datos.
        $pedidos = new Detalles_Pedidos;
        if ($pedidos->setId_Pedido($rowDetalles_Pedidos['id_pedido'])) {
            if($dataPedidos = $pedidos->readOrderDetail()){
                $total = 0;
                
                foreach($dataPedidos as $rowPedido){
                    // Se declara e inicializa una variable para calcular el importe por cada producto.
        // let subtotal = 0;
        // // Se declara e inicializa una variable para sumar cada subtotal y obtener el monto final a pagar.
        // let total = 0;
        //             subtotal = row.precio_producto * row.cantidad_producto;
        //             total += subtotal;  
                    $subtotal = $rowPedido['precio_detalle_producto'] * $rowPedido['cantidad_detalle_producto'];
                    $total +=  $subtotal;
                    // $total = $total + $subtotal;
                    $pdf->cell(37.2, 20, $rowPedido['id_detalle_pedido'], 1, 0,'C');
                    $pdf->cell(37.2, 20, $rowPedido['nombre_producto'], 1, 0,'C');
                    $pdf->cell(37.2, 20, $rowPedido['cantidad_detalle_producto'], 1, 0,'C');
                    //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
                    $pdf->cell(37.2, 20, $rowPedido['precio_detalle_producto'], 1, 0,'C');
                    $pdf->cell(31.3, 20, '$'.$subtotal, 1, 1,'C');

                }
                // $pdf->cell(0, 10, 'Fecha del Pedido '.$rowPedido['fecha_pedido'], 1, 1,'C',1);

                $pdf->cell(0, 5, 'Total: $'.$total, 1, 1,'C');
            }
            else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay pedido para mostrar'), 1, 1);
            }
        }
        else {
            $pdf->cell(0, 10, $pdf->encodeString('Pedido incorrecta o inexistente'), 1, 1);
        }
        
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Detalles_Pedidoss.pdf');
