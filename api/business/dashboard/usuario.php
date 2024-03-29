<?php
require_once('../../entities/Controller/Controller_Usuarios.php');
//bueno
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();    
    // Se instancia la clase correspondiente.
    $usuario = new ControllerUsuarios;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null, 'foto' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (!$usuario->setId_Usuario($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($data = $usuario->readOne()) {
                    $result['status'] = 1;
                    $result['username'] = $data['alias_usuario'];
                    $result['foto'] = $data['foto'];
                } else {
                    $result['exception'] = 'Alias de usuario indefinido';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $usuario->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Usuario inexistente';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$usuario->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$usuario->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif (!$usuario->setAlias($_POST['alias'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($usuario->editProfile()) {
                    $result['status'] = 1;
                    $_SESSION['alias_usuario'] = $usuario->getAlias();
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setId($_SESSION['id_usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$usuario->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$usuario->setClave($_POST['nueva'])) {
                    $result['exception'] = Validator::getPasswordError();
                } elseif ($usuario->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $usuario->readAll()) {
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
                    } elseif ($result['dataset'] = $usuario->searchRows($_POST['search'])) {
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
                    if (!$usuario->setNombre_Usuario($_POST['nombre_usuario'])) {
                        $result['exception'] = 'Nombres incorrecto';
                    } 
                    elseif (!$usuario->setApellido_Usuario($_POST['apellido_usuario'])) {
                        $result['exception'] = 'Apellidos incorrectos';
                    } 
                    elseif (!$usuario->setCorreo_Usuario($_POST['correo_usuario'])) {
                        $result['exception'] = 'Correo incorrecto';
                    }
                    elseif (!$usuario->setAlias_Usuario($_POST['alias_usuario'])) {
                        $result['exception'] = 'Alias incorrecto';
                    } 
                    elseif (!$usuario->setClave_Usuario($_POST['clave_usuario'])) {
                        $result['exception'] = 'Clave incorrecto';
                    } 
                    elseif (!$usuario->setTelefono_Usuario($_POST['telefono_usuario'])) {
                        $result['exception'] = 'Telefono incorrecto';
                    }
                    elseif (!isset($_POST['Tipo_usuario'])) {
                        $result['exception'] = 'Seleccione una tipo de usuario';
                    }
                    elseif (!$usuario->setId_tipo_usuario($_POST['Tipo_usuario'])) {
                        $result['exception'] = 'Tipo de usuario incorrecto';
                    }
                    elseif (!isset($_POST['Estado_usuario'])) {
                        $result['exception'] = 'Seleccione un estado del usuario';
                    }
                    elseif (!$usuario->setId_estado_usuario($_POST['Estado_usuario'])) {
                        $result['exception'] = 'Estado de usuario incorrecto';
                    }
                    elseif (!is_uploaded_file($_FILES['foto']['tmp_name'])) {
                        $result['exception'] = 'Seleccione una imagen';
                    } elseif (!$usuario->setFoto_Usuario($_FILES['foto'])) {
                        $result['exception'] = Validator::getFileError();
                    } elseif ($usuario->createRow()) {
                        $result['status'] = 1;
                        if (Validator::saveFile($_FILES['foto'], $usuario->getRuta(), $usuario->getFoto_Usuario())) {
                            $result['message'] = 'Usuario creado correctamente';
                        } else {
                            $result['message'] = 'Usuario creado pero no se guardó la imagen';
                        }
                    } else {
                        $result['exception'] = Database::getException();;
                    }
                    break;
                case 'readOne':
                    if (!$usuario->setId_Usuario($_POST['id_usuario'])) {
                        $result['exception'] = 'Usuario incorrecto';
                    } elseif ($result['dataset'] = $usuario->readOne()) {
                        $result['status'] = 1;
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                    break;
                case 'update':
                    $_POST = Validator::validateForm($_POST);
                    if (!$usuario->setId_Usuario($_POST['id_usuario'])) {
                        
                        $result['exception'] = 'Usuario incorrecto';
                    } 
                    elseif (!$data = $usuario->readOne()) {
                        $result['exception'] = 'Usuario inexistente';
                    } 
                    elseif (!$usuario->setNombre_Usuario($_POST['nombre_usuario'])) {
                        $result['exception'] = 'Nombre incorrecto';
                    } 
                    elseif(!$usuario->setApellido_Usuario($_POST['apellido_usuario'])){
                        $result['exception'] = 'Apellido incorrecto';
                    }
                    elseif (!$usuario->setCorreo_Usuario($_POST['correo_usuario'])) {
                        $result['exception'] = 'Correo incorrecto';
                    } 
                    elseif (!$usuario->setTelefono_Usuario($_POST['telefono_usuario'])) {
                        $result['exception'] = 'Telefono incorrecto';
                    }  
                    elseif (!$usuario->setId_tipo_usuario($_POST['Tipo_usuario'])) {
                        $result['exception'] = 'Tipo de usuario incorrecto';
                    } 
                    elseif (!$usuario->setId_estado_usuario($_POST['Estado_usuario'])) {
                        $result['exception'] = 'Estado de usuario incorrecto';
                    }
                    elseif (!is_uploaded_file($_FILES['foto']['tmp_name'])) {
                        if ($usuario->updateRow($data['foto'])) {
                            $result['status'] = 1;
                            $result['message'] = 'Usuario modificado correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } elseif (!$usuario->setFoto_Usuario($_FILES['foto'])) {
                        $result['exception'] = Validator::getFileError();
                    } elseif ($usuario->updateRow($data['foto'])) {
                        $result['status'] = 1;
                        if (Validator::saveFile($_FILES['foto'], $usuario->getRuta(), $usuario->getFoto_Usuario())) {
                            $result['message'] = 'Usuario modificado correctamente';
                        } else {
                            $result['message'] = 'Usuario modificado pero no se guardó la imagen';
                        } 
                    }    
                    // elseif ($usuario->updateRow()) {
                    //     $result['status'] = 1;
                    //     $result['message'] = 'Usuario modificado correctamente';
                    // } 
                    else {
                        $result['exception'] = Database::getException();
                    }
                    break;
                case 'delete':
                    if (!$usuario->setId_Usuario($_POST['id_usuario'])) {
                        $result['exception'] = 'Usuario incorrecto';
                    } 
                    elseif ($usuario->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Usuario eliminado correctamente';
                    }
                    else {
                        $result['exception'] = Database::getException();
                    }
                    break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['exception'] = 'Debe crear un usuario para comenzar';
                }
                break;
            case 'signup':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->setNombre_Usuario($_POST['nombre_usuario'])) {
                    $result['exception'] = 'Nombres incorrecto';
                } elseif (!$usuario->setApellido_Usuario($_POST['apellido_usuario'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$usuario->setCorreo_Usuario($_POST['correo_usuario'])) {
                    $result['exception'] = 'Correo incorrecto';
                }elseif (!$usuario->setAlias_Usuario($_POST['alias_usuario'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif (!$usuario->setTelefono_Usuario($_POST['telefono_usuario'])) {
                    $result['exception'] = 'Telefono incorrecto';
                } elseif (!isset($_POST['Tipo_usuario'])) {
                    $result['exception'] = 'Seleccione una tipo de usuario';
                }elseif (!$usuario->setId_tipo_usuario($_POST['Tipo_usuario'])) {
                    $result['exception'] = 'Tipo de usuario incorrecto';
                }elseif (!isset($_POST['Estado_usuario'])) {
                    $result['exception'] = 'Seleccione un estado del usuario';
                } elseif (!$usuario->setId_estado_usuario($_POST['Estado_usuario'])) {
                    $result['exception'] = 'Estado de usuario incorrecto';
                }
                elseif ($_POST['codigo'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves diferentes';
                } elseif (!$usuario->setClave_Usuario($_POST['codigo'])) {
                    $result['exception'] = Validator::getPasswordError();
                } 
                elseif (!is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    $result['exception'] = 'Seleccione una imagen';
                } elseif (!$usuario->setFoto_Usuario($_FILES['foto'])) {
                    $result['exception'] = Validator::getFileError();
                }  
                elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    if (Validator::saveFile($_FILES['foto'], $usuario->getRuta(), $usuario->getFoto_Usuario())) {
                        $result['message'] = 'Primer Usuario creada correctamente';
                    } else {
                        $result['message'] = 'Primer Usuario creada pero no se guardó la imagen';
                    }
                } 
                else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'login':
                $_POST = Validator::validateForm($_POST);
                if (!$usuario->checkUser($_POST['alias'])) {
                    $result['exception'] = 'Alias incorrecto';
                } elseif ($usuario->checkPassword($_POST['clave'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_usuario'] = $usuario->getId_Usuario();
                    $_SESSION['alias_usuario'] = $usuario->getAlias_Usuario();
                } else {
                    $result['exception'] = 'Clave incorrecta';
                }
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