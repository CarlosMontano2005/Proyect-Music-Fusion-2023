<?php
require_once('../../entities/Controller/Controller_pedidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedidos = new ControllerPedidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $pedidos->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readAllEstadoPedido':
                if ($result['dataset'] = $pedidos->readAllEstadoPedido()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados estado pedidos';
                }
                break;
            case 'readAllClientes':
                if ($result['dataset'] = $pedidos->readAllClientes()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados de clientes';
                }
                break;
            case 'search':
                $_POST = Validator::validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $pedidos->searchRows($_POST['search'])) {
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
                if (!$pedidos->setNombre($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } 
                elseif (!$pedidos->setApellido($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecta';
                } 
                elseif (!isset($_POST['generos'])) {
                    $result['exception'] = 'Seleccione  un sexo';
                }
                elseif (!$pedidos->setId_genero($_POST['generos'])) {
                    $result['exception'] = 'Sexo incorrecta';
                } 
                elseif (!$pedidos->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } 
                elseif (!$pedidos->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif (!$pedidos->setDUI($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } 
                elseif (!$pedidos->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Dirección incorrecto';
                } 
                elseif (!$pedidos->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Nacimiento incorrecto';
                } 
                elseif (!$pedidos->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif (!$pedidos->setClave($_POST['clave'])) {
                    $result['exception'] = 'Clave incorrecta';
                } 
                elseif ($pedidos->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido creado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readOne':
                if (!$pedidos->setId_Pedido($_POST['id'])) {
                    $result['exception'] = 'Pedido incorrecto';
                } elseif ($result['dataset'] = $pedidos->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Pedido inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$pedidos->setId_Pedido($_POST['id'])) {
                    
                    $result['exception'] = 'Pedido incorrecto';
                } 
                elseif (!$data = $pedidos->readOne()) {
                    $result['exception'] = 'Pedido inexistente';
                } 
                elseif (!$pedidos->setFecha_Pedido($_POST['fecha_pedido'])) {
                    $result['exception'] = 'fecha incorrecto';
                } 
                elseif(!$pedidos->setDireccion_Pedido($_POST['direccion'])){
                    $result['exception'] = 'Direccion incorrecta';
                }
                elseif (!$pedidos->setId_Cliente($_POST['cliente'])) {
                    $result['exception'] = 'Seleccione un cliente';
                } 
                elseif (!$pedidos->setId_Estado_Pedido($_POST['estado_pedido'])) {
                    $result['exception'] = 'Seleccione un estado del pedido';
                } 
                elseif ($pedidos->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido modificado correctamente';
                } 
                else {
                    $result['exception'] =  Database::getException();
                }
                break;
            case 'delete':
                if (!$pedidos->setId($_POST['id_pedido'])) {
                    $result['exception'] = 'Pedido incorrecto';
                } 
                elseif ($pedidos->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido eliminado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'cantidadClientesCategoria':
                if ($result['dataset'] = $pedidos->cantidadClientesCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'porcentajeClientesCategoria':
                if ($result['dataset'] = $pedidos->porcentajeClientesCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
                case 'porcentajePedidosEstado':
                    if ($result['dataset'] = $pedidos->porcentajePedidosEstado()) {
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
