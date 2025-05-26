<?php
require_once 'controllers/userController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$endpoint = basename($path);
$controller = new UserController();

switch ($endpoint) {
    case 'helloWorld':
        http_response_code(200);
        echo json_encode(['message' => 'API is running']);
        break;
    case 'CrearUsuario':
        $controller->crearUsuario();
        break;
    // case 'BuscarUsuario':
    //     $controller->getUser();
    //     break;
    case 'buscarUsuario':
        $controller->buscarUsuario();
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint not found']);
        break;
}
?>