<?php

require_once 'controllers/userController.php';

$uri = explode("/",$_SERVER['REQUEST_URI']);
$endpoint = end($uri);
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
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint not found']);
        break;
}
?>