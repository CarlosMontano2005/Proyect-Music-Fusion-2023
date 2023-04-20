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
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
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
            case 'search':
                $_POST = Validator::validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $producto->searchRows($_POST['search'])) {
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
                if (!$producto->setNombre_Producto($_POST['nombre_producto'])) {
                    $result['exception'] = 'Nombre de producto incorrecto';
                }
                elseif (!isset($_POST['Marca_Producto'])) {
                    $result['exception'] = 'Seleccione una marca';
                } 
                elseif (!$producto->setId_Marca_Producto($_POST['Marca_Producto'])) {
                    $result['exception'] = 'Marca incorrecta';
                } 
                elseif (!$producto->setPrecio_Producto($_POST['precio_producto'])) {
                    $result['exception'] = 'Precio incorrecto';
                }
                elseif (!isset($_POST['categoria'])) {
                    $result['exception'] = 'Seleccione una categoria';
                }  
                elseif (!$producto->setId_Categoria_Producto($_POST['categoria'])) {
                    $result['exception'] = 'categoria incorrecto';
                } 
                elseif (!$producto->setDescripcion_Producto($_POST['descripcion'])) {
                    $result['exception'] = 'descripción incorrecto';
                }
                elseif (!isset($_POST['id_estado_producto'])) {
                    $result['exception'] = 'Seleccione  un estado para el producto';
                }  
                elseif (!$producto->setId_Estado_Producto($_POST['id_estado_producto'])) {
                    $result['exception'] = 'Estado incorrecto';
                }
                elseif (!isset($_POST['usuario'])) {
                    $result['exception'] = 'Seleccione un usuario';
                }  
                elseif (!$producto->setId_Usuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } 
                elseif (!$producto->setImagen($_FILES['foto'])) {
                    $result['exception'] = Validator::getFileError();
                } 
                elseif ($producto->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto creado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();;
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
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$producto->setId_Producto($_POST['id_producto'])) {
                    
                    $result['exception'] = 'Producto incorrecto';
                } 
                elseif (!$data = $producto->readOne()) {
                    $result['exception'] = 'Producto inexistente';
                } 
                elseif (!$producto->setNombre_Producto($_POST['nombre_producto'])) {
                    $result['exception'] = 'Nombre del producto incorrecto';
                } 
                elseif (!$producto->setId_Marca_Producto($_POST['Marca_Producto'])) {
                    $result['exception'] = 'Seleccione una marca';
                }
                elseif (!$producto->setPrecio_Producto($_POST['precio_producto'])) {
                    $result['exception'] = 'Precio incorrecta';
                } 
                elseif (!$producto->setId_Categoria_Producto($_POST['categoria'])) {
                    $result['exception'] = 'Seleccione una categoria';
                } 
                elseif (!$producto->setDescripcion_Producto($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción incorrecta';
                } 
                elseif (!$producto->setId_Estado_Producto($_POST['id_estado_producto'])) {
                    $result['exception'] = 'Seleccione un estado para el producto';
                } 
                elseif (!$producto->setId_Usuario($_POST['usuario'])) {
                    $result['exception'] = 'Seleccione un usuario';
                } 
                elseif (!is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    if ($producto->updateRow($data['foto'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Producto modificado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } elseif (!$producto->setImagen($_FILES['foto'])) {
                    $result['exception'] = Validator::getFileError();
                }
                elseif ($producto->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$producto->setId_Producto($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } 
                elseif ($producto->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto eliminado correctamente';
                }
                else {
                    $result['exception'] = Database::getException();
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