<?php
require_once('../../entities/Controller/Controller_Valoraciones_dto.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $valoraciones = new Valoraciones_Controller;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $valoraciones->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = Validator::validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $valoraciones->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' coincidencias';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = Validator::validateForm($_POST);
                if (!$valoraciones->setNombre($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } 
                elseif (!$valoraciones->setApellido($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecta';
                } 
                elseif (!isset($_POST['generos'])) {
                    $result['exception'] = 'Seleccione  un sexo';
                }
                elseif (!$valoraciones->setId_genero($_POST['generos'])) {
                    $result['exception'] = 'Sexo incorrecta';
                } 
                elseif (!$valoraciones->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } 
                elseif (!$valoraciones->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif (!$valoraciones->setDUI($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } 
                elseif (!$valoraciones->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Dirección incorrecto';
                } 
                elseif (!$valoraciones->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Nacimiento incorrecto';
                } 
                elseif (!$valoraciones->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif (!$valoraciones->setClave($_POST['clave'])) {
                    $result['exception'] = 'Clave incorrecta';
                } 
                elseif ($valoraciones->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Valoracion creado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readOne':
                if (!$valoraciones->setId($_POST['id'])) {
                    $result['exception'] = 'Valoracion incorrecto';
                } elseif ($result['dataset'] = $valoraciones->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Valoracion inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$valoraciones->setId($_POST['id_valoracion'])) {
                    
                    $result['exception'] = 'Valoracion incorrecto';
                } 
                elseif (!$data = $valoraciones->readOne()) {
                    $result['exception'] = 'Valoracion inexistente';
                } 
                elseif (!$valoraciones->setId_DetallePedido($_POST['id_detalle'])) {
                    $result['exception'] = 'Id del detalle pedido incorrecto';
                } 
                elseif(!$valoraciones->setCalificacion_Producto($_POST['calificacion'])){
                    $result['exception'] = 'Calificación incorrecto';
                }
                elseif (!$valoraciones->setFecha_Comentario($_POST['fecha_comentario'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } 
                elseif (!$valoraciones->setComentarioProducto($_POST['comentario'])) {
                    $result['exception'] = 'Comentario incorrecto';
                } 
                
                elseif (!$valoraciones->setEstado(isset($_POST['estado_comentario']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif ($valoraciones->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Valoracion modificado correctamente';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$valoraciones->setId($_POST['id_Valoracion'])) {
                    $result['exception'] = 'Valoracion incorrecto';
                } 
                elseif ($valoraciones->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Valoracion eliminado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'cantidadValoracionsCategoria':
                if ($result['dataset'] = $valoraciones->cantidadValoracionsCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'porcentajeValoracionsCategoria':
                if ($result['dataset'] = $valoraciones->porcentajeValoracionsCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
