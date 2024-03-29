<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_Usuarios.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Generales');
// $pdf->cell(33.375, 10, $_SESSION['id_usuario'], 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
// Se instancia el módelo Categoría para obtener los datos.
$Usuario = new ControllerUsuarios;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataUsuario = $Usuario->readAll()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    $pdf->AddPage("landscape");
    // Se establece un color de relleno para los encabezados.
    $pdf->SetFillColor(202, 92, 245 );
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    // Se imprimen las celdas con los encabezados. 126 ancho en vertical y 267 ancho en horizontal se divide en los 33.375 y se hace el calculo
    $pdf->cell(33.375, 10, 'Nombre', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(33.375, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(33.375, 10, 'Alias', 1, 0, 'C', 1);
    $pdf->cell(33.375, 10, 'Telefono', 1, 0, 'C', 1);
    $pdf->cell(33.375, 10, 'Tipo Usuario', 1, 0, 'C', 1);
    $pdf->cell(33.375, 10, 'Estado Usuario', 1, 0, 'C', 1);
    $pdf->cell(46.375, 10, 'Correo', 1, 0, 'C', 1);
    $pdf->cell(20.375, 10, 'Imagen', 1, 1, 'C', 1);
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 9);
   
    // Se recorren los registros fila por fila.
    foreach ($dataUsuario as $rowCliente) {
        // Se imprime una celda con el nombre de la categoría.
        // $pdf->cell(0, 10, $pdf->encodeString('Categoría: ' . $rowCliente['nombre_categoria']), 1, 1, 'C', 1);
        $pdf->cell(33.375, 20, $rowCliente['nombre_usuario'], 1, 0,'C');
        $pdf->cell(33.375, 20, $rowCliente['apellido_usuario'], 1, 0,'C');
        $pdf->cell(33.375, 20, $rowCliente['alias_usuario'], 1, 0,'C');
        $pdf->cell(33.375, 20, $rowCliente['telefono_usuario'], 1, 0,'C');
        //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
        $pdf->cell(33.375, 20, $rowCliente['tipo_usuario'], 1, 0,'C');
        // ($rowCliente['id_estado_usuario']) ? $estado = 'Activo' : $estado = 'Inactivo';
        $pdf->cell(33.375, 20, $rowCliente['estado_usuario'], 1, 0,'C');
        $pdf->cell(46.375, 20, $rowCliente['correo_usuario'], 1, 0,'C');
        //${SERVER_URL}img/people/${row.foto}
        // $this->cell(0, 10, $this->encodeString('Página ').$this->pageNo().'/{nb}', 0, 0, 'C');
        // $pdf->Image($pdf->encodeString('../../img/people/'.$rowCliente['foto']), 30.375, 10, -300);
        $pdf->cell(33.375, 1,$pdf->Image($pdf->encodeString('../../img/people/'.$rowCliente['foto']),null,null,20.5,20), 0, 1, 'C');//indicar el 1,1 al final para que sea una sola fila cada dato 
        // Se instancia el módelo Producto para procesar los datos.
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato persona para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'usuarios.pdf');
