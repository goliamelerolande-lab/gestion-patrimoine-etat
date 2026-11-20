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

if ($uri === '/biens/update' && $method === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $client->updateBien($id, $_POST);
    }
    header('Location: /biens?updated=1');
    exit;
}

if ($uri === '/biens/delete' && $method === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $client->deleteBien($id);
    }
    header('Location: /biens?deleted=1');
    exit;
}

// 5. ROUTE : AFFECTATIONS
if ($uri === '/affectations') {
    $affectations = $client->getAffectations($_GET);
    $biens = $client->getBiens();
    require __DIR__ . '/../views/affectations.php';
    exit;
}

if ($uri === '/affectations/create' && $method === 'POST') {
    $client->createAffectation($_POST);
    header('Location: /affectations?success=1');
    exit;
}

if ($uri === '/affectations/update' && $method === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $client->updateAffectation($id, $_POST);
    }
    header('Location: /affectations?updated=1');
    exit;
}

if ($uri === '/affectations/delete' && $method === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $client->deleteAffectation($id);
    }
    header('Location: /affectations?deleted=1');
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
    $maintenances = $client->getMaintenances($_GET);
    $biens = $client->getBiens();
    require __DIR__ . '/../views/maintenances.php';
    exit;
}

if ($uri === '/maintenances/create' && $method === 'POST') {
    $client->createMaintenance($_POST);
    header('Location: /maintenances?success=1');
    exit;
}

if ($uri === '/maintenances/update' && $method === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $client->updateMaintenance($id, $_POST);
    }
    header('Location: /maintenances?updated=1');
    exit;
}

if ($uri === '/maintenances/delete' && $method === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $client->deleteMaintenance($id);
    }
    header('Location: /maintenances?deleted=1');
    exit;
}

// 7. ROUTE : RAPPORTS GLOBAUX, CACHET & SIGNATAIRE OFFICIEL (Togo)
function getSignataireSettings(): array {
    $settingsFile = __DIR__ . '/uploads/signataire_settings.json';
    $defaults = [
        'nom' => 'Amele Rolande GOLI',
        'titre' => 'Le Directeur Général du Patrimoine',
        'formule' => 'Pour le Ministre et par ordre,',
        'lieu' => 'Lomé',
        'qualite' => 'Direction Générale du Patrimoine de l\'État'
    ];
    if (file_exists($settingsFile)) {
        $saved = json_decode(file_get_contents($settingsFile), true);
        if (is_array($saved)) {
            return array_merge($defaults, $saved);
        }
    }
    return $defaults;
}

function getActiveSignatureInfo(): array {
    $uploadDir = __DIR__ . '/uploads';
    foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $ext) {
        $file = $uploadDir . '/signature_custom.' . $ext;
        if (file_exists($file)) {
            return [
                'url' => '/uploads/signature_custom.' . $ext . '?v=' . filemtime($file),
                'is_custom' => true,
                'filename' => 'signature_custom.' . $ext,
                'updated_at' => date('d/m/Y à H:i', filemtime($file)),
                'filesize' => round(filesize($file) / 1024, 1) . ' Ko'
            ];
        }
    }
    return [
        'url' => null,
        'is_custom' => false,
        'filename' => null,
        'updated_at' => null,
        'filesize' => null
    ];
}

function getActiveCachetInfo(): array {
    $uploadDir = __DIR__ . '/uploads';
    foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $ext) {
        $file = $uploadDir . '/cachet_custom.' . $ext;
        if (file_exists($file)) {
            return [
                'url' => '/uploads/cachet_custom.' . $ext . '?v=' . filemtime($file),
                'is_custom' => true,
                'filename' => 'cachet_custom.' . $ext,
                'updated_at' => date('d/m/Y à H:i', filemtime($file)),
                'filesize' => round(filesize($file) / 1024, 1) . ' Ko'
            ];
        }
    }
    $defaultFile = $uploadDir . '/cachet_default.svg';
    return [
        'url' => '/uploads/cachet_default.svg',
        'is_custom' => false,
        'filename' => 'cachet_default.svg (Sceau d\'État Officiel)',
        'updated_at' => file_exists($defaultFile) ? date('d/m/Y à H:i', filemtime($defaultFile)) : date('d/m/Y'),
        'filesize' => file_exists($defaultFile) ? round(filesize($defaultFile) / 1024, 1) . ' Ko' : '5 Ko'
    ];
}

if ($uri === '/rapports') {
    $stats = $client->getStats();
    $biens = $client->getBiens();
    $cachetInfo = getActiveCachetInfo();
    $activeCachetUrl = $cachetInfo['url'];
    $hasCustomCachet = $cachetInfo['is_custom'];
    $signataireSettings = getSignataireSettings();
    $signatureInfo = getActiveSignatureInfo();
    require __DIR__ . '/../views/rapports.php';
    exit;
}

// 7.1 ROUTE DÉDIÉE : GESTION DU CACHET, DE LA SIGNATURE & PARAMÈTRES DU SIGNATAIRE
if (in_array($uri, ['/cachet', '/parametres', '/signature'])) {
    $cachetInfo = getActiveCachetInfo();
    $activeCachetUrl = $cachetInfo['url'];
    $hasCustomCachet = $cachetInfo['is_custom'];
    $signataireSettings = getSignataireSettings();
    $signatureInfo = getActiveSignatureInfo();
    require __DIR__ . '/../views/cachet.php';
    exit;
}

// Enregistrement des paramètres du signataire (Nom, Titre, Formule, Lieu, Signature)
if ($uri === '/parametres/save-signataire' && $method === 'POST') {
    $destDir = __DIR__ . '/uploads';
    if (!is_dir($destDir)) {
        mkdir($destDir, 0777, true);
    }
    $settingsFile = $destDir . '/signataire_settings.json';
    $data = [
        'nom' => trim($_POST['nom'] ?? 'Amele Rolande GOLI'),
        'titre' => trim($_POST['titre'] ?? 'Le Directeur Général du Patrimoine'),
        'formule' => trim($_POST['formule'] ?? 'Pour le Ministre et par ordre,'),
        'lieu' => trim($_POST['lieu'] ?? 'Lomé'),
        'qualite' => trim($_POST['qualite'] ?? 'Direction Générale du Patrimoine de l\'État')
    ];
    file_put_contents($settingsFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Si une image de signature est également transmise
    if (!empty($_FILES['signature_file']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION)) ?: 'png';
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
            foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $oldExt) {
                $f = $destDir . '/signature_custom.' . $oldExt;
                if (file_exists($f)) @unlink($f);
            }
            move_uploaded_file($_FILES['signature_file']['tmp_name'], $destDir . '/signature_custom.' . $ext);
        }
    }

    $redirectTo = $_POST['redirect_to'] ?? '/cachet';
    header('Location: ' . $redirectTo . '?settings_saved=1');
    exit;
}

// Téléversement d'une signature isolée
if (($uri === '/signature/upload' || $uri === '/rapports/upload-signature') && $method === 'POST') {
    $redirectTo = ($uri === '/signature/upload') ? '/cachet' : '/rapports';
    if (!empty($_FILES['signature_file']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION)) ?: 'png';
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
            $destDir = __DIR__ . '/uploads';
            if (!is_dir($destDir)) mkdir($destDir, 0777, true);
            foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $oldExt) {
                $f = $destDir . '/signature_custom.' . $oldExt;
                if (file_exists($f)) @unlink($f);
            }
            move_uploaded_file($_FILES['signature_file']['tmp_name'], $destDir . '/signature_custom.' . $ext);
            header('Location: ' . $redirectTo . '?sig_saved=1');
            exit;
        }
    }
    header('Location: ' . $redirectTo . '?error_sig=1');
    exit;
}

// Réinitialisation de la signature importée
if (($uri === '/signature/reset' || $uri === '/rapports/reset-signature') && $method === 'POST') {
    $redirectTo = ($uri === '/signature/reset') ? '/cachet' : '/rapports';
    $destDir = __DIR__ . '/uploads';
    foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $oldExt) {
        $f = $destDir . '/signature_custom.' . $oldExt;
        if (file_exists($f)) @unlink($f);
    }
    header('Location: ' . $redirectTo . '?sig_reset=1');
    exit;
}

// Téléversement du cachet officiel
if (($uri === '/cachet/upload' || $uri === '/rapports/upload-cachet') && $method === 'POST') {
    $redirectTo = ($uri === '/cachet/upload') ? '/cachet' : '/rapports';
    if (!empty($_FILES['cachet_file']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['cachet_file']['name'], PATHINFO_EXTENSION)) ?: 'png';
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
            $destDir = __DIR__ . '/uploads';
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }
            foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $oldExt) {
                $f = $destDir . '/cachet_custom.' . $oldExt;
                if (file_exists($f)) @unlink($f);
            }
            $destPath = $destDir . '/cachet_custom.' . $ext;
            move_uploaded_file($_FILES['cachet_file']['tmp_name'], $destPath);
            header('Location: ' . $redirectTo . '?cachet_saved=1');
            exit;
        }
    }
    header('Location: ' . $redirectTo . '?error_cachet=1');
    exit;
}

// Réinitialisation du cachet officiel
if (($uri === '/cachet/reset' || $uri === '/rapports/reset-cachet') && $method === 'POST') {
    $redirectTo = ($uri === '/cachet/reset') ? '/cachet' : '/rapports';
    $destDir = __DIR__ . '/uploads';
    foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $oldExt) {
        $f = $destDir . '/cachet_custom.' . $oldExt;
        if (file_exists($f)) @unlink($f);
    }
    header('Location: ' . $redirectTo . '?cachet_reset=1');
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
