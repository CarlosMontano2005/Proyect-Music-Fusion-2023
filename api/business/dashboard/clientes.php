<?php
require_once('../../entities/Controller/clientes_dto.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new Cliente;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $cliente->readAll()) {
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
                } elseif ($result['dataset'] = $cliente->searchRows($_POST['search'])) {
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
                if (!$cliente->setNombre($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } 
                elseif (!$cliente->setApellido($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecta';
                } 
                elseif (!isset($_POST['generos'])) {
                    $result['exception'] = 'Seleccione  un sexo';
                }
                elseif (!$cliente->setId_genero($_POST['generos'])) {
                    $result['exception'] = 'Sexo incorrecta';
                } 
                elseif (!$cliente->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } 
                elseif (!$cliente->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif (!$cliente->setDUI($_POST['dui'])) {
                    $result['exception'] = 'DUI incorrecto';
                } 
                elseif (!$cliente->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Dirección incorrecto';
                } 
                elseif (!$cliente->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Nacimiento incorrecto';
                } 
                elseif (!$cliente->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif (!$cliente->setClave($_POST['clave'])) {
                    $result['exception'] = 'Clave incorrecta';
                } 
                /*elseif (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                    $result['exception'] = 'Seleccione una imagen';
                } 
                elseif (!$cliente->setImagen($_FILES['archivo'])) {
                    $result['exception'] = Validator::getFileError();
                } 
                elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    if (Validator::saveFile($_FILES['archivo'], $cliente->getRuta(), $cliente->getImagen())) {
                        $result['message'] = 'Cliente creado correctamente';
                    } else {
                        $result['message'] = 'Cliente creado pero no se guardó la imagen';
                    }
                }*/
                else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readOne':
                if (!$cliente->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif ($result['dataset'] = $cliente->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Cliente inexistente';
                }
                break;
            case 'update':
                $_POST = Validator::validateForm($_POST);
                if (!$cliente->setId($_POST['id'])) {
                    
                    $result['exception'] = 'Cliente incorrecto';
                } 
                elseif (!$data = $cliente->readOne()) {
                    $result['exception'] = 'Cliente inexistente';
                } 
                elseif (!$cliente->setNombre($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } 
                elseif(!$cliente->setClave($_POST['clave'])){
                    $result['exception'] = 'Clave incorrecto';
                }
                elseif (!$cliente->setApellido($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrecta';
                } 
                elseif (!$cliente->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } 
                elseif (!$cliente->setId_genero($_POST['generos'])) {
                    $result['exception'] = 'Seleccione un sexo';
                } 
                elseif (!$cliente->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } 
                elseif (!$cliente->setNacimiento($_POST['nacimiento'])) {
                    $result['exception'] = 'Fecha incorrecto';
                } 
                elseif (!$cliente->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif (!$cliente->setTelefono($_POST['telefono'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } 
                elseif(!$cliente->setDUI($_POST['dui'])){
                    $result['exception'] = 'DUI incorrecto';
                }
                elseif(!$cliente->setDireccion($_POST['direccion'])){
                    $result['exception'] = 'Dirección incorrecto';
                }
               /* elseif (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                    if ($cliente->updateRow($data['imagen_producto'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Cliente modificado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } 
                elseif (!$cliente->setImagen($_FILES['archivo'])) {
                    $result['exception'] = Validator::getFileError();
                } 
                elseif ($cliente->updateRow($data['imagen_producto'])) {
                    $result['status'] = 1;
                    if (Validator::saveFile($_FILES['archivo'], $cliente->getRuta(), $cliente->getImagen())) {
                        $result['message'] = 'Cliente modificado correctamente';
                    } else {
                        $result['message'] = 'Cliente modificado pero no se guardó la imagen';
                    }
                }*/
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if (!$cliente->setId($_POST['id_cliente'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'cantidadClientesCategoria':
                if ($result['dataset'] = $cliente->cantidadClientesCategoria()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'porcentajeClientesCategoria':
                if ($result['dataset'] = $cliente->porcentajeClientesCategoria()) {
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
