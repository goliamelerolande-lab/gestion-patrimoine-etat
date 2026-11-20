<?php

spl_autoload_register(function ($class) {
    $prefix = 'AffectationService\\';
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

$controller = new \AffectationService\Controllers\AffectationController();

// GET /api/affectations
if ($method === 'GET' && ($uri === '/api/affectations' || $uri === '/affectations')) {
    $res = $controller->getAffectations($params);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// POST /api/affectations
if ($method === 'POST' && ($uri === '/api/affectations' || $uri === '/affectations')) {
    $res = $controller->createAffectation($body);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// POST /api/affectations/restituer/{id}
if ($method === 'POST' && preg_match('#^/api/affectations/restituer/(\d+)$#', $uri, $matches)) {
    $res = $controller->restituerAffectation((int)$matches[1]);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// GET /api/maintenances
if ($method === 'GET' && ($uri === '/api/maintenances' || $uri === '/maintenances')) {
    $res = $controller->getMaintenances();
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// POST /api/maintenances
if ($method === 'POST' && ($uri === '/api/maintenances' || $uri === '/maintenances')) {
    $res = $controller->createMaintenance($body);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// PUT /api/maintenances/{id}
if ($method === 'PUT' && preg_match('#^/api/maintenances/(\d+)$#', $uri, $matches)) {
    $res = $controller->updateMaintenance((int)$matches[1], $body);
    http_response_code($res['status']);
    echo json_encode($res['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Healthcheck
if ($uri === '/' || $uri === '/health') {
    echo json_encode([
        'service' => 'GPE_Affectation_Service',
        'status' => 'UP',
        'port' => 8003,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint introuvable dans affectation-service.']);
