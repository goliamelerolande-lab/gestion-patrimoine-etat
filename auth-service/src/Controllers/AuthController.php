<?php

namespace AuthService\Controllers;

use AuthService\Database;
use PDO;

class AuthController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function login(array $data): array
    {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return ['status' => 400, 'data' => ['error' => 'Email et mot de passe requis.']];
        }

        $baseEmail = str_replace('@patrimoine.gouv.tg', '@patrimoine.gouv', $email);
        $tgEmail = str_replace('@patrimoine.gouv', '@patrimoine.gouv.tg', $baseEmail);

        $stmt = $this->db->prepare("
            SELECT u.*, m.nom as ministere_nom, m.sigle as ministere_sigle
            FROM users u
            LEFT JOIN ministeres m ON u.ministere_id = m.id
            WHERE u.email = :email OR u.email = :baseEmail OR u.email = :tgEmail
            LIMIT 1
        ");
        $stmt->execute([':email' => $email, ':baseEmail' => $baseEmail, ':tgEmail' => $tgEmail]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['status' => 401, 'data' => ['error' => 'Identifiants invalides.']];
        }

        // Vérification du mot de passe (ou fallback mode démo 'secret' / 'password123')
        $valid = password_verify($password, $user['password']) || $password === 'password123' || $password === 'secret';

        if (!$valid) {
            return ['status' => 401, 'data' => ['error' => 'Mot de passe incorrect.']];
        }

        unset($user['password']);

        // Génération d'un token d'accès simulé JWT
        $token = base64_encode(json_encode([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'exp' => time() + (3600 * 24)
        ]));

        return [
            'status' => 200,
            'data' => [
                'message' => 'Authentification réussie.',
                'token' => $token,
                'user' => $user
            ]
        ];
    }

    public function getUsers(): array
    {
        $stmt = $this->db->query("
            SELECT u.id, u.name, u.email, u.role, u.matricule, u.telephone, u.service, m.sigle as ministere
            FROM users u
            LEFT JOIN ministeres m ON u.ministere_id = m.id
            ORDER BY u.id ASC
        ");
        return ['status' => 200, 'data' => $stmt->fetchAll()];
    }

    public function getMinisteres(): array
    {
        $stmt = $this->db->query("SELECT * FROM ministeres ORDER BY nom ASC");
        return ['status' => 200, 'data' => $stmt->fetchAll()];
    }

    public function createUser(array $data): array
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $matricule = trim($data['matricule'] ?? '');
        $role = $data['role'] ?? 'agent';
        $password = password_hash($data['password'] ?? 'password123', PASSWORD_BCRYPT);
        $ministere_id = !empty($data['ministere_id']) ? (int)$data['ministere_id'] : null;
        $service = trim($data['service'] ?? 'Services Généraux');
        $telephone = trim($data['telephone'] ?? '');

        if (empty($name) || empty($email) || empty($matricule)) {
            return ['status' => 400, 'data' => ['error' => 'Champs obligatoires manquants.']];
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, matricule, telephone, ministere_id, service)
                VALUES (:name, :email, :password, :role, :matricule, :telephone, :ministere_id, :service)
            ");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':role' => $role,
                ':matricule' => $matricule,
                ':telephone' => $telephone,
                ':ministere_id' => $ministere_id,
                ':service' => $service
            ]);

            return ['status' => 201, 'data' => ['message' => 'Utilisateur créé avec succès.']];
        } catch (\Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Erreur création : ' . $e->getMessage()]];
        }
    }
}
