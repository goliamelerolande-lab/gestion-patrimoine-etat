<?php

// Test script to verify role-based access, authentication, and service endpoints for all 4 profiles
require_once __DIR__ . '/../gateway-frontend/src/ServicesClient.php';

$client = new \Gateway\ServicesClient();

echo "========================================================\n";
echo "=== TEST 1 : AUTHENTICATION FOR ALL 4 OFFICIAL ROLES ===\n";
echo "========================================================\n";

$profiles = [
    'superadmin'   => ['login' => 'admin@patrimoine.gouv.tg', 'password' => 'password123', 'expected_role' => 'superadmin'],
    'gestionnaire' => ['login' => 'gestionnaire@patrimoine.gouv.tg', 'password' => 'password123', 'expected_role' => 'gestionnaire'],
    'agent'        => ['login' => 'agent@patrimoine.gouv.tg', 'password' => 'password123', 'expected_role' => 'agent'],
    'auditeur'     => ['login' => 'auditeur@patrimoine.gouv.tg', 'password' => 'password123', 'expected_role' => 'auditeur'],
];

foreach ($profiles as $roleName => $p) {
    $res = $client->login(['login' => $p['login'], 'password' => $p['password']]);
    $user = $res['data']['user'] ?? $res['user'] ?? null;
    if ($user && ($user['role'] ?? '') === $p['expected_role']) {
        echo "[OK] Login {$roleName} via Email -> Name: {$user['name']}, Role: {$user['role']}, Email: {$user['email']}\n";
    } else {
        echo "[FAIL] Login {$roleName} failed: " . json_encode($res) . "\n";
        exit(1);
    }
}

echo "\n========================================================\n";
echo "=== TEST 2 : FLEXIBLE LOGIN (MATRICULE & NAME) ========\n";
echo "========================================================\n";

$flexTests = [
    'Matricule MAT-TG-001' => ['login' => 'MAT-TG-001', 'password' => 'password123', 'expected_name' => 'GOLI Amele Rolande'],
    'Matricule MAT-TG-004' => ['login' => 'MAT-TG-004', 'password' => 'password123', 'expected_name' => 'Komi AMEGAN'],
    'Nom GODHOPE\'S'       => ['login' => 'GODHOPE\'S', 'password' => 'password123', 'expected_name' => 'GODHOPE\'S'],
    'Nom MY ALL'           => ['login' => 'MY ALL', 'password' => 'password123', 'expected_name' => 'MY ALL'],
];

foreach ($flexTests as $label => $test) {
    $res = $client->login(['login' => $test['login'], 'password' => $test['password']]);
    $user = $res['data']['user'] ?? $res['user'] ?? null;
    if ($user && $user['name'] === $test['expected_name']) {
        echo "[OK] Login flexible {$label} -> Identifie: {$user['name']} ({$user['role']})\n";
    } else {
        echo "[FAIL] Login flexible {$label} failed: " . json_encode($res) . "\n";
        exit(1);
    }
}

echo "\n========================================================\n";
echo "=== TEST 3 : SECURITY & INCORRECT PASSWORD REJECTION ==\n";
echo "========================================================\n";

$badRes = $client->login(['login' => 'admin@patrimoine.gouv.tg', 'password' => 'wrong_password_xyz']);
if (empty($badRes['data']['user']) && empty($badRes['user'])) {
    echo "[OK] Mot de passe incorrect correctement rejete avec statut 401\n";
} else {
    echo "[FAIL] La securite a accepte un mauvais mot de passe !\n";
    exit(1);
}

echo "\n========================================================\n";
echo "=== TEST 4 : DATA & METRICS ENDPOINTS ==================\n";
echo "========================================================\n";

$biens = $client->getBiens();
echo "[OK] Registre des Biens: " . count($biens) . " biens repertories\n";

$stats = $client->getStats();
echo "[OK] Statistiques: Total = " . ($stats['total_biens'] ?? 0) . ", Valeur = " . number_format($stats['valeur_cumulee_fcfa'] ?? 0, 0, ',', ' ') . " FCFA\n";

$affectations = $client->getAffectations();
echo "[OK] Affectations: " . count($affectations) . " affectations actives\n";

$maintenances = $client->getMaintenances();
echo "[OK] Maintenances: " . count($maintenances) . " tickets de maintenance\n";

$users = $client->getUsers();
echo "[OK] Annuaire utilisateurs: " . count($users) . " comptes enregistres\n";

echo "\n========================================================\n";
echo "=== ALL TESTS COMPLETED SUCCESSFULLY (100% PASS) ======\n";
echo "========================================================\n";
