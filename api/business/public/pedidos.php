<?php
require_once('../../entities/Controller/Controller_detalles_pedidos_dto.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedido = new Detalles_Pedidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            /*crear producto al carrito */
            case 'varlidarExistencia':
                $_POST = Validator::validateForm($_POST);
                if (!$pedido->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$pedido->setCantidad_Producto($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($result['dataset'] = $pedido->varlidarExistencia()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto validado correctamente';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'createDetail':
                $_POST = Validator::validateForm($_POST);
                if (!$pedido->startOrder()) {
                    $result['exception'] = 'Ocurrió un problema al obtener el pedido';
                } elseif (!$pedido->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$pedido->setCantidad_Producto($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($pedido->createDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto agregado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOrderDetail':
                if (!$pedido->startOrder()) {
                    $result['exception'] = 'Debe agregar un producto al carrito';
                } elseif ($result['dataset'] = $pedido->readOrderDetail()) {
                    $result['status'] = 1;
                    $_SESSION['id_pedido'] = $pedido->getId_Pedido();
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No tiene productos en el carrito';
                }
                break;
            //caso para restar la cantidad de la existencia al producto en el carrito
            case 'updateExistenciaResta':
                $_POST = Validator::validateForm($_POST);
                if (!$pedido->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$pedido->setCantidad_Producto($_POST['cantida_resta'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($result['dataset'] =$pedido->updateCantidadAumentaCarrito()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cantidad del producto autualizado correctamente';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'updateExistenciaSuma':
                $_POST = Validator::validateForm($_POST);
                if (!$pedido->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$pedido->setCantidad_Producto($_POST['cantida_resta'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($result['dataset'] =$pedido->updateCantidadRestaCarrito()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cantidad del producto autualizado correctamente';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            
            // Caso para actualizar la cantidad de un producto agregado al carrito de compras.
            case 'updateDetail':
                $_POST = Validator::validateForm($_POST);
                if (!$pedido->setId($_POST['id_detalle'])) {
                    $result['exception'] = 'Detalle incorrecto';
                } elseif (!$pedido->setCantidad_Producto($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif ($pedido->updateDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cantidad modificada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al modificar la cantidad';
                }
                break;
                //Para mover un producto y quitar un producto que se encuentra en el carrito
            case 'deleteDetail':
                if (!$pedido->setId($_POST['id_detalle'])) {
                    $result['exception'] = 'Detalle incorrecto';
                } elseif ($pedido->deleteDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto removido correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al remover el producto';
                }
                break;
                //caso para finalizar el pedido
            case 'finishOrder':
                //mandar a llamar la accion para finalizar el pedido
                if ($pedido->finishOrder()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido finalizado correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al finalizar el pedido';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'createDetail':
                $result['exception'] = 'Debe iniciar sesión para agregar el producto al carrito';
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
