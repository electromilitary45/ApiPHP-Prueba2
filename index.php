<?php
header('Content-Type: application/json');
require_once 'controllers/configController.php'; // Nombre del controlador de configuración
$confController = new ConfigController();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

// Si estás trabajando en una carpeta (como ApiPHP-Prueba2), elimínala de los segmentos
$baseFolder = 'ApiPHP-Prueba2'; // Cambia esto si tu carpeta tiene otro nombre
if (!empty($segments[0]) && $segments[0] === $baseFolder) {
    array_shift($segments); // quitamos ApiPHP-Prueba2
}

// ✅ Si no hay segmentos → mostrar mensaje por defecto
if (empty($segments[0])) {
    http_response_code(200);
    echo json_encode(['message' => 'API is running']);
    //salto de linea
    echo "\n";
    $confController->probarConexion(); // Probar conexión a la base de datos
    exit;
}

// Asignar controlador y acción
$controllerName = $segments[0] ?? null;
$action = $segments[1] ?? null;

if (!$controllerName || !$action) {
    http_response_code(400);
    echo json_encode(['message' => 'Falta controlador o acción en la URL']);
    exit;
}

// Formar ruta del archivo del controlador y nombre de clase
$controllerFile = 'controllers/' . $controllerName . 'Controller.php';
$controllerClass = ucfirst($controllerName) . 'Controller';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo json_encode(['message' => "Controlador '$controllerClass' no encontrado"]);
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo json_encode(['message' => "La clase '$controllerClass' no existe"]);
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo json_encode(['message' => "Método '$action' no encontrado en $controllerClass"]);
    exit;
}

// ✅ Ejecutar acción
call_user_func([$controller, $action]);
?>