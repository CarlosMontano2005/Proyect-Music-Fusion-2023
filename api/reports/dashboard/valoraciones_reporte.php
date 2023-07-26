<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_Valoraciones.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Generales');
// $pdf->cell(33.375, 10, $_SESSION['id_usuario'], 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
// Se instancia el módelo Categoría para obtener los datos.
$Valoraciones = new Valoraciones_Controller;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataValoraciones = $Valoraciones->readAll()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    $pdf->AddPage();
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(175);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    // Se imprimen las celdas con los encabezados. 126 ancho en vertical y 267 ancho en horizontal se divide en los 33.375 y se hace el calculo
    $pdf->cell(20, 10, 'ID', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 
    $pdf->cell(20, 10, 'Calificacion', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(122, 10, 'Comentario', 1, 0, 'C', 1);
    $pdf->cell(20, 10, 'Fecha', 1, 0, 'C', 1);
    $pdf->cell(20, 10, 'Estado', 1, 0, 'C', 1);
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los Valoraciones.
    $pdf->setFont('Times', '', 9);
   
    // Se recorren los registros fila por fila.
    foreach ($dataValoraciones as $rowValoraciones) {
        // Se imprime una celda con el nombre de la categoría.
        // $pdf->cell(0, 10, $pdf->encodeString('Categoría: ' . $rowUsuario['nombre_categoria']), 1, 1, 'C', 1);
        $pdf->cell(25, 20, $rowValoraciones['id_valoracion'], 1, 0,'C');
        $pdf->cell(25, 20, $rowValoraciones['calificacion_producto'], 1, 0,'C');
        $pdf->cell(25, 20, $rowValoraciones['comentario_producto'], 1, 0,'C');
        $pdf->cell(25, 20, $rowValoraciones['fecha_comentario'], 1, 0,'C');
        $pdf->cell(25, 20, $rowValoraciones['estado_comentario'], 1, 0,'C');
        //${SERVER_URL}img/people/${row.foto}
        // $this->cell(0, 10, $this->encodeString('Página ').$this->pageNo().'/{nb}', 0, 0, 'C');
        // $pdf->Image($pdf->encodeString('../../img/people/'.$rowUsuario['foto']), 30.375, 10, -300);
        // Se instancia el módelo Producto para procesar los datos.
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato Valoraciones para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documenZto al navegador web.
$pdf->output('I', 'Valoraciones.pdf');