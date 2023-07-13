<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../entities/Controller/Controller_Clientes_dto.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Datos Generales');
// $pdf->cell(26.7, 10, $_SESSION['id_usuario'], 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
// Se instancia el módelo Categoría para obtener los datos.
$Cliente = new Cliente;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataUsuario = $Cliente->readAll()) {
    //se aliniar horizontalmente con el comentado landscape -> $pdf->AddPage("landscape");
    $pdf->AddPage("landscape");
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(175);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 9);
    // Se imprimen las celdas con los encabezados. 126 ancho en vertical y 267 ancho en horizontal se divide en los 26.7 y se hace el calculo
    //SELECT id_cliente, nombre_cliente, apellido_cliente, correo_cliente, fecha_nacimiento, genero, telefono_cliente,dui,  estado, direccion_cliente
        // FROM clientes INNER JOIN generos USING(id_genero) ORDER BY  (id_cliente) asc
    $pdf->cell(26.7, 10, 'ID', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(26.7, 10, 'Nombre', 1, 0, 'C', 1); //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
    $pdf->cell(26.7, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(38.7, 10, 'Correo', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'Nacimiento', 1, 0, 'C', 1);
    $pdf->cell(20.7, 10, 'Sexo', 1, 0, 'C', 1);
    $pdf->cell(20.7, 10, 'Telefono', 1, 0, 'C', 1);
    $pdf->cell(26.7, 10, 'DUI', 1, 0, 'C', 1);
    $pdf->cell(20.7, 10, 'Estado', 1, 0, 'C', 1);
    $pdf->cell(32.7, 10, 'Dirección', 1, 1, 'C', 1);
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(225);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 9);
   
    // Se recorren los registros fila por fila.
    foreach ($dataUsuario as $rowUsuario) {
        // Se imprime una celda con el nombre de la categoría.
        // $pdf->cell(0, 10, $pdf->encodeString('Categoría: ' . $rowUsuario['nombre_categoria']), 1, 1, 'C', 1);
        
        $pdf->cell(26.7, 20, $rowUsuario['id_cliente'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowUsuario['nombre_cliente'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowUsuario['apellido_cliente'], 1, 0,'C');
        $pdf->cell(38.7, 20, $rowUsuario['correo_cliente'], 1, 0,'C');
        //(Ancho, Alto, Titulo, borde 1 - 0, salto de linea L-C-R, color de fondo 1-0)
        $pdf->cell(26.7, 20, $rowUsuario['fecha_nacimiento'], 1, 0,'C');
        // ($rowUsuario['id_estado_usuario']) ? $estado = 'Activo' : $estado = 'Inactivo';
        $pdf->cell(20.7, 20, $rowUsuario['genero'], 1, 0,'C');
        $pdf->cell(20.7, 20, $rowUsuario['telefono_cliente'], 1, 0,'C');
        $pdf->cell(26.7, 20, $rowUsuario['dui'], 1, 0,'C');
        ($rowUsuario['estado']) ? $estado = 'Activo' : $estado = 'Inactivo';
        $pdf->cell(20.7, 20, $estado, 1, 0,'C');
        $pdf->cell(32.7, 20, $rowUsuario['direccion_cliente'], 1, 1,'C');
        //${SERVER_URL}img/people/${row.foto}
        // $this->cell(0, 10, $this->encodeString('Página ').$this->pageNo().'/{nb}', 0, 0, 'C');
        // $pdf->Image($pdf->encodeString('../../img/people/'.$rowUsuario['foto']), 30.375, 10, -300);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay dato persona para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'clientes.pdf');
