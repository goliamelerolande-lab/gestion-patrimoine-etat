<?php

namespace AuthService;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $host = getenv('DB_HOST') ?: 'postgres';
        $port = getenv('DB_PORT') ?: '5432';
        $db   = getenv('DB_DATABASE') ?: 'auth_db';
        $user = getenv('DB_USERNAME') ?: 'postgres';
        $pass = getenv('DB_PASSWORD') ?: 'secret_gpe_2026';

        // 1. Tentative de connexion PostgreSQL (Docker / Prod)
        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$db}";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3
            ]);
            self::$instance = $pdo;
            return self::$instance;
        } catch (PDOException $e) {
            // 2. Mode Fallback Local : SQLite embarqué pour test sans Docker démarré
            $sqlitePath = __DIR__ . '/../storage/auth.sqlite';
            if (!is_dir(dirname($sqlitePath))) {
                mkdir(dirname($sqlitePath), 0777, true);
            }
            $pdo = new PDO("sqlite:{$sqlitePath}", null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            self::initSqliteTables($pdo);
            self::$instance = $pdo;
            return self::$instance;
        }
    }

    private static function initSqliteTables(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS ministeres (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT UNIQUE NOT NULL,
                nom TEXT NOT NULL,
                sigle TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'agent',
                matricule TEXT UNIQUE NOT NULL,
                telephone TEXT,
                ministere_id INTEGER,
                service TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Seeders si vide
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        if ((int)$stmt->fetch()['count'] === 0) {
            $pdo->exec("
                INSERT INTO ministeres (code, nom, sigle) VALUES
                ('MEF', 'Ministère de l''Économie et des Finances', 'MEF'),
                ('MBPE', 'Ministère du Budget et du Portefeuille de l''État', 'MBPE'),
                ('MENA', 'Ministère de l''Éducation Nationale', 'MENA');

                INSERT INTO users (name, email, password, role, matricule, telephone, ministere_id, service) VALUES
                ('Amele Rolande GOLI', 'admin@patrimoine.gouv', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'superadmin', 'MAT-001', '+225 0700000001', 1, 'Direction Générale du Patrimoine de l''État'),
                ('Kouamé Jean-Marc', 'gestionnaire@patrimoine.gouv', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'gestionnaire', 'MAT-002', '+225 0700000002', 1, 'Service Logistique & Matériel'),
                ('Amadou Koné', 'agent@patrimoine.gouv', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'agent', 'MAT-003', '+225 0700000003', 2, 'Direction du Budget');
            ");
        }
    }
}
