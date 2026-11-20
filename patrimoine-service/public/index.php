<?php

spl_autoload_register(function ($class) {
    $prefix = 'PatrimoineService\\';
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
$params = $_GET;

$controller = new \PatrimoineService\Controllers\PatrimoineController();

// GET /api/stats
if ($method === 'GET' && ($uri === '/api/stats' || $uri === '/stats')) {
    $res = $controller->getStats();
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /api/categories
if ($method === 'GET' && ($uri === '/api/categories' || $uri === '/categories')) {
    $res = $controller->getCategories();
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /api/biens/{id}
if ($method === 'GET' && preg_match('#^/api/biens/(\d+)$#', $uri, $matches)) {
    $res = $controller->getBien((int)$matches[1]);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// PUT /api/biens/{id}
if ($method === 'PUT' && preg_match('#^/api/biens/(\d+)$#', $uri, $matches)) {
    $res = $controller->updateBien((int)$matches[1], $body);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// DELETE /api/biens/{id}
if ($method === 'DELETE' && preg_match('#^/api/biens/(\d+)$#', $uri, $matches)) {
    $res = $controller->deleteBien((int)$matches[1]);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /api/biens
if ($method === 'GET' && ($uri === '/api/biens' || $uri === '/biens')) {
    $res = $controller->getBiens($params);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// POST /api/biens
if ($method === 'POST' && ($uri === '/api/biens' || $uri === '/biens')) {
    $res = $controller->createBien($body);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Healthcheck
if ($uri === '/' || $uri === '/health') {
    echo json_encode([
        'service' => 'GPE_Patrimoine_Service',
        'status' => 'UP',
        'port' => 8002,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint introuvable dans patrimoine-service.']);
