<?php

session_start();

require_once __DIR__ . '/../src/ServicesClient.php';

$client = new \Gateway\ServicesClient();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Middleware auth minimal
if (!isset($_SESSION['user']) && $uri !== '/login') {
    // Session par défaut pour fluidité de la soutenance (Amele Rolande GOLI)
    $_SESSION['user'] = [
        'id' => 1,
        'name' => 'Amele Rolande GOLI',
        'email' => 'admin@patrimoine.gouv',
        'role' => 'Super-Administrateur'
    ];
}

// 1. ROUTE : LOGIN
if ($uri === '/login') {
    if ($method === 'POST') {
        $res = $client->login($_POST);
        if ($res['status'] === 200) {
            $_SESSION['user'] = $res['data']['user'];
            $_SESSION['token'] = $res['data']['token'];
            header('Location: /');
            exit;
        }
        $error = $res['data']['error'] ?? 'Échec connexion.';
        require __DIR__ . '/../views/login.php';
        exit;
    }
    require __DIR__ . '/../views/login.php';
    exit;
}

// 2. ROUTE : LOGOUT
if ($uri === '/logout') {
    session_destroy();
    header('Location: /login');
    exit;
}

// 3. ROUTE : DASHBOARD
if ($uri === '/' || $uri === '/dashboard') {
    $stats = $client->getStats();
    $biens = $client->getBiens();
    $affectations = $client->getAffectations();
    $maintenances = $client->getMaintenances();
    require __DIR__ . '/../views/dashboard.php';
    exit;
}

// 4. ROUTE : REGISTRE DES BIENS
if ($uri === '/biens') {
    $biens = $client->getBiens($_GET);
    $categories = $client->getCategories();
    require __DIR__ . '/../views/biens.php';
    exit;
}

if ($uri === '/biens/create' && $method === 'POST') {
    $client->createBien($_POST);
    header('Location: /biens?success=1');
    exit;
}

// 5. ROUTE : AFFECTATIONS
if ($uri === '/affectations') {
    $affectations = $client->getAffectations();
    $biens = $client->getBiens();
    require __DIR__ . '/../views/affectations.php';
    exit;
}

if ($uri === '/affectations/create' && $method === 'POST') {
    $client->createAffectation($_POST);
    header('Location: /affectations?success=1');
    exit;
}

if ($uri === '/affectations/restituer' && $method === 'POST') {
    require_once __DIR__ . '/../../affectation-service/src/Controllers/AffectationController.php';
    $affCtrl = new \AffectationService\Controllers\AffectationController();
    $affCtrl->restituerAffectation((int)$_POST['id']);
    header('Location: /affectations?success=1');
    exit;
}

// 6. ROUTE : MAINTENANCE & PANNES
if ($uri === '/maintenances') {
    $maintenances = $client->getMaintenances();
    $biens = $client->getBiens();
    require __DIR__ . '/../views/maintenances.php';
    exit;
}

if ($uri === '/maintenances/create' && $method === 'POST') {
    $client->createMaintenance($_POST);
    header('Location: /maintenances?success=1');
    exit;
}

// 7. ROUTE : RAPPORTS GLOBAUX (conforme à Rapports - GPE.pdf)
if ($uri === '/rapports') {
    $stats = $client->getStats();
    $biens = $client->getBiens();
    require __DIR__ . '/../views/rapports.php';
    exit;
}

// 8. ROUTE : UTILISATEURS & MINISTÈRES
if ($uri === '/utilisateurs') {
    $users = $client->getUsers();
    $ministeres = $client->getMinisteres();
    require __DIR__ . '/../views/utilisateurs.php';
    exit;
}

// Fichiers statiques ou 404
header('Location: /');
exit;
