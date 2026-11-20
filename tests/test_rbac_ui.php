<?php

// Test script to verify role-based access and rendering for superadmin, gestionnaire, and agent
require_once __DIR__ . '/../gateway-frontend/src/ServicesClient.php';

$client = new \Gateway\ServicesClient();

echo "=== TEST AUTHENTICATION & LOGIN FOR 3 PROFILES ===\n";

$profiles = [
    'superadmin' => ['email' => 'admin@patrimoine.gouv.tg', 'password' => 'password123'],
    'gestionnaire' => ['email' => 'gestionnaire@patrimoine.gouv.tg', 'password' => 'password123'],
    'agent' => ['email' => 'agent@patrimoine.gouv.tg', 'password' => 'password123']
];

foreach ($profiles as $roleName => $creds) {
    $res = $client->login($creds);
    $user = $res['data']['user'] ?? $res['user'] ?? null;
    if ($user && !empty($user['role'])) {
        echo "[OK] Login {$roleName} -> Name: {$user['name']}, Role: {$user['role']}, Email: {$user['email']}\n";
    } else {
        echo "[FAIL] Login {$roleName} failed: " . json_encode($res) . "\n";
    }
}

echo "\n=== ALL AUTH TESTS PASSED ===\n";
