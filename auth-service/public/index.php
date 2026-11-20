<?php

// Autoloading
spl_autoload_register(function ($class) {
    $prefix = 'AuthService\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$controller = new \AuthService\Controllers\AuthController();

// Routage API REST
if ($method === 'POST' && ($uri === '/api/auth/login' || $uri === '/login')) {
    $response = $controller->login($body);
    http_response_code($response['status']);
    echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'GET' && ($uri === '/api/users' || $uri === '/users')) {
    $response = $controller->getUsers();
    http_response_code($response['status']);
    echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST' && ($uri === '/api/users' || $uri === '/users')) {
    $response = $controller->createUser($body);
    http_response_code($response['status']);
    echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'GET' && ($uri === '/api/ministeres' || $uri === '/ministeres')) {
    $response = $controller->getMinisteres();
    http_response_code($response['status']);
    echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Healthcheck
if ($uri === '/' || $uri === '/health') {
    echo json_encode([
        'service' => 'GPE_Auth_Service',
        'status' => 'UP',
        'port' => 8001,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint introuvable dans auth-service.']);
