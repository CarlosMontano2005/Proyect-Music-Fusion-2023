<?php
require_once('../../entities/Controller/Controller_detalles_pedidos_dto.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $det_pedidos = new Detalles_Pedidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $det_pedidos->readAll()) {
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
                } elseif ($result['dataset'] = $det_pedidos->searchRows($_POST['search'])) {
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
                if (!$det_pedidos->setNombre($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } 
                elseif (!$det_pedidos->setApellido($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecta';
                } 
                elseif (!isset($_POST['generos'])) {
                    $result['exception'] = 'Seleccione  un sexo';
                }
                elseif (!$det_pedidos->setId_genero($_POST['generos'])) {
                    $result['exception'] = 'Sexo incorrecta';
                } 
                elseif (!$det_pedidos->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } 
                elseif (!$det_pedidos->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif (!$det_pedidos->setDUI($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } 
                elseif (!$det_pedidos->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Dirección incorrecto';
                } 
                elseif (!$det_pedidos->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Nacimiento incorrecto';
                } 
                elseif (!$det_pedidos->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif (!$det_pedidos->setClave($_POST['clave'])) {
                    $result['exception'] = 'Clave incorrecta';
                } 
                elseif ($det_pedidos->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente creado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readOne':
                if (!$det_pedidos->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif ($result['dataset'] = $det_pedidos->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Cliente inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$det_pedidos->setId($_POST['id'])) {
                    
                    $result['exception'] = 'Cliente incorrecto';
                } 
                elseif (!$data = $det_pedidos->readOne()) {
                    $result['exception'] = 'Cliente inexistente';
                } 
                elseif (!$det_pedidos->setNombre($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } 
                elseif(!$det_pedidos->setClave($_POST['clave'])){
                    $result['exception'] = 'Clave incorrecto';
                }
                elseif (!$det_pedidos->setApellido($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecta';
                } 
                elseif (!$det_pedidos->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } 
                elseif (!$det_pedidos->setId_genero($_POST['generos'])) {
                    $result['exception'] = 'Seleccione un sexo';
                } 
                elseif (!$det_pedidos->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif (!$det_pedidos->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecto';
                } 
                elseif (!$det_pedidos->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif(!$det_pedidos->setDUI($_POST['dui'])){
                    $result['exception'] = 'DUI incorrecto';
                }
                elseif(!$det_pedidos->setDireccion($_POST['direccion'])){
                    $result['exception'] = 'Dirección incorrecto';
                }
                elseif ($det_pedidos->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente modificado correctamente';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$det_pedidos->setId($_POST['id_cliente'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } 
                elseif ($det_pedidos->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente eliminado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'cantidadClientesCategoria':
                if ($result['dataset'] = $det_pedidos->cantidadClientesCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'porcentajeClientesCategoria':
                if ($result['dataset'] = $det_pedidos->porcentajeClientesCategoria()) {
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
