<?php
require_once('../../entities/Controller/Controller_Productos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $producto = new ControllerProductos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se compara la acción a realizar según la petición del controlador.
    if (isset($_SESSION['id_cliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
        case 'readProductosCategoria':  
            if (!$producto->setId_Producto($_POST['id_categoria'])) {
                $result['exception'] = 'Categoría incorrecta';
            } elseif ($result['dataset'] = $producto->readProductosCategoria()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No existen productos para mostrar';
            }
            break;
        case 'readOne':
            if (!$producto->setId_Producto($_POST['id_producto'])) {
                $result['exception'] = 'Producto incorrecto';
            } elseif ($result['dataset'] = $producto->readOne()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'Producto inexistente';
            }
            break;
        case 'readAllComentarios':
            if (!$producto->setId_Producto($_POST['id_producto'])) {
                $result['exception'] = 'Producto incorrecto';
            } elseif ($result['dataset'] = $producto->readAllComentarios()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'Este producto aun no tiene comentario';
            }
            break;
        case 'readAll':
            if ($result['dataset'] = $producto->readAll()) {
                $result['status'] = 1;
                $result['message'] = 'Existen '.count($result['dataset']).' registros';
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No hay datos registrados';
            }
            break;
        default:
            $result['exception'] = 'Acción no disponible';
        }
    } else {
        // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readProductosCategoria':  
                if (!$producto->setId_Producto($_POST['id_categoria'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif ($result['dataset'] = $producto->readProductosCategoria()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen productos para mostrar';
                }
                break;
            case 'readOne':
                if (!$producto->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif ($result['dataset'] = $producto->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Producto inexistente';
                }
                break;
            case 'readAllComentarios':
                if (!$producto->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif ($result['dataset'] = $producto->readAllComentarios()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Este producto aun no tiene comentario';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $producto->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen '.count($result['dataset']).' registros';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible';
            }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
