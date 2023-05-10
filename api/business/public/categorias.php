<?php
//mandar a llamar la ruta
require_once('../../entities/Controller/Controller_Categoria_Productos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se instancia la clase correspondiente.
    $categoria = new ControllerCategoriaProductos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        //caso leer todo
        case 'readAll':
            //declaracion de operador if
            if ($result['dataset'] = $categoria->readAll()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                //resultados con datos existentes en la base
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No existen categorías del instrumento para mostrar';
            }
            break;
        default:
        //no encotro la accion que esta llamando
            $result['exception'] = 'Acción no disponible';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
