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

        $host = getenv('DB_HOST');
        $isLocalStandalone = !$host && !file_exists('/.dockerenv');

        if (!$isLocalStandalone) {
            $host = $host ?: 'postgres';
            $port = getenv('DB_PORT') ?: '5432';
            $db   = getenv('DB_DATABASE') ?: 'auth_db';
            $user = getenv('DB_USERNAME') ?: 'postgres';
            $pass = getenv('DB_PASSWORD') ?: 'secret_gpe_2026';

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
                // Fallback direct vers SQLite ci-dessous
            }
        }

        // Mode Fallback Local : SQLite embarqué pour test sans Docker démarré
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
                password_plain TEXT DEFAULT 'password123',
                role TEXT NOT NULL DEFAULT 'agent',
                matricule TEXT UNIQUE NOT NULL,
                telephone TEXT,
                ministere_id INTEGER,
                service TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Migration douce pour tables existantes sans la colonne password_plain
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN password_plain TEXT DEFAULT 'password123'");
        } catch (\Throwable $e) {}

        // Seeders si vide
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        if ((int)$stmt->fetch()['count'] === 0) {
            $pdo->exec("
                INSERT INTO ministeres (code, nom, sigle) VALUES
                ('MEF', 'Ministère de l''Économie et des Finances (Togo)', 'MEF'),
                ('MENTD', 'Ministère de l''Économie Numérique et de la Transformation Digitale', 'MENTD'),
                ('MTPI', 'Ministère des Travaux Publics et des Infrastructures', 'MTPI'),
                ('MEPST', 'Ministère des Enseignements Primaire, Secondaire et Technique', 'MEPST'),
                ('MSHPAUS', 'Ministère de la Santé, de l''Hygiène Publique et de l''Accès Universel aux Soins', 'MSHPAUS');

                INSERT INTO users (name, email, password, password_plain, role, matricule, telephone, ministere_id, service) VALUES
                ('GOLI Amele Rolande', 'admin@patrimoine.gouv.tg', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'password123', 'superadmin', 'MAT-TG-001', '+228 96 49 37 14', 1, 'Direction Générale du Patrimoine de l''État (DGPE Togo)'),
                ('GODHOPE''S', 'gestionnaire@patrimoine.gouv.tg', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'password123', 'gestionnaire', 'MAT-TG-002', '+228 96 83 01 54', 1, 'Direction du Matériel & de la Logistique'),
                ('MY ALL', 'agent@patrimoine.gouv.tg', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'password123', 'agent', 'MAT-TG-003', '+228 96 08 12 83', 2, 'Direction de la Transformation Digitale'),
                ('Komi AMEGAN', 'auditeur@patrimoine.gouv.tg', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'password123', 'auditeur', 'MAT-TG-004', '+228 92 50 97 05', 1, 'Inspection Générale des Finances (IGF Lomé)');
            ");
        } else {
            // Mettre à jour automatiquement le rôle Auditeur pour Komi AMEGAN et initialiser password_plain
            try {
                $pdo->exec("UPDATE users SET role = 'auditeur', service = 'Inspection Générale des Finances (IGF Lomé)' WHERE email = 'auditeur@patrimoine.gouv.tg' OR matricule = 'MAT-TG-004'");
                $pdo->exec("UPDATE users SET password_plain = 'password123' WHERE password_plain IS NULL OR password_plain = ''");
            } catch (\Throwable $e) {}
        }
    }
}
