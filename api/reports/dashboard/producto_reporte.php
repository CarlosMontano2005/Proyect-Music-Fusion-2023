<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_Productos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Generales');
// Se instancia el módelo Categoría para obtener los datos.
$Productos = new ControllerProductos;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataProductos = $Productos->readAll()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    $pdf->AddPage("landscape");
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(175);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    // Se imprimen las celdas con los encabezados. 126 ancho en vertical y 267 ancho en horizontal se divide en los 33.375 y se hace el calculo
    $pdf->cell(26.7, 10, 'ID', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 
    $pdf->cell(26.7, 10, 'Nombre ', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(26.7, 10, 'Marca', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Precio', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Categoria', 1, 0, 'C', 1);
    // $pdf->cell(26.7, 10, 'Descripcion', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Estado', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Usuario', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Fecha Compra', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Imagen', 1, 1, 'C', 1);
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 9);
   
    // Se recorren los registros fila por fila.
    foreach ($dataProductos as $rowProductos) {
        // Se imprime una celda con el nombre de la categoría.
        // $pdf->cell(0, 10, $pdf->encodeString('Categoría: ' . $rowUsuario['nombre_categoria']), 1, 1, 'C', 1);
        $pdf->cell(26.7, 20, $rowProductos['id_producto'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['nombre_producto'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['nombre_marca'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['precio_producto'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['nombre_categoria'], 1, 0,'C');
        // $pdf->Cell(26.7, 20,$pdf->MultiCell(26.7, 5, $rowProductos ['descripcion'],1,'C'),1, 0,'C');
        // $pdf->Cell(26.7, 20,$rowProductos ['descripcion'],1, 0,'C');
        ($rowProductos['estado_producto']) ? $estado = 'Activo' : $estado = 'Inactivo';
        $pdf->cell(26.7, 20, $estado, 1, 0,'C');
        // $pdf->cell(26.7, 20, $rowProductos['estado_productod'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['nombre_usuario'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['cantidad_producto'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowProductos['fecha_compra'], 1, 0,'C');
        //${SERVER_URL}img/people/${row.foto}
        // $this->cell(0, 10, $this->encodeString('Página ').$this->pageNo().'/{nb}', 0, 0, 'C');
        // $pdf->Image($pdf->encodeString('../../img/people/'.$rowUsuario['foto']), 30.375, 10, -300);
        $pdf->cell(26.7, 0,$pdf->Image($pdf->encodeString('../../img/productos/'.$rowProductos['imagen_producto']),null,null,26.7,20),0,1,'C');//indicar el 1,1 al final para que sea una sola fila cada dato 
        // Se instancia el módelo Producto para procesar los datos.
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato Productos para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documenZto al navegador web.
$pdf->output('I', 'Productoss.pdf');