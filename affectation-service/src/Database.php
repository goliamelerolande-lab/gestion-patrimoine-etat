<?php

namespace AffectationService;

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
        $db   = getenv('DB_DATABASE') ?: 'affectation_db';
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
            // Mode Fallback Local : SQLite embarqué
            $sqlitePath = __DIR__ . '/../storage/affectation.sqlite';
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
            CREATE TABLE IF NOT EXISTS affectations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                bien_id INTEGER NOT NULL,
                code_inventaire TEXT NOT NULL,
                beneficiaire_nom TEXT NOT NULL,
                beneficiaire_matricule TEXT NOT NULL,
                service_nom TEXT NOT NULL,
                date_affectation DATE NOT NULL DEFAULT CURRENT_DATE,
                date_restitution DATE,
                motif TEXT NOT NULL,
                statut TEXT NOT NULL DEFAULT 'En cours',
                remarques TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS maintenances (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                bien_id INTEGER NOT NULL,
                code_inventaire TEXT NOT NULL,
                libelle_bien TEXT NOT NULL,
                description_panne TEXT NOT NULL,
                priorite TEXT NOT NULL DEFAULT 'Moyenne',
                statut TEXT NOT NULL DEFAULT 'En attente',
                prestataire TEXT,
                cout_reparation_fcfa INTEGER DEFAULT 0,
                date_signalement DATE NOT NULL DEFAULT CURRENT_DATE,
                date_resolution DATE,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $stmt = $pdo->query("SELECT COUNT(*) as count FROM affectations");
        if ((int)$stmt->fetch()['count'] === 0) {
            $pdo->exec("
                INSERT INTO affectations (bien_id, code_inventaire, beneficiaire_nom, beneficiaire_matricule, service_nom, date_affectation, motif, statut) VALUES
                (2, 'GPE-000014', 'Amadou Koné', 'MAT-003', 'Direction Générale du Trésor', '2026-01-20', 'Mise à disposition pour mission d''audit', 'En cours'),
                (3, 'GPE-000015', 'Kouamé Jean-Marc', 'MAT-002', 'Direction des Systèmes d''Information', '2026-02-25', 'Hébergement applicatif ministériel', 'En cours'),
                (5, 'GPE-000017', 'Secrétariat Général', 'SERV-01', 'Bureau Central', '2025-08-15', 'Équipement d''impression bureautique', 'En cours');

                INSERT INTO maintenances (bien_id, code_inventaire, libelle_bien, description_panne, priorite, statut, prestataire, cout_reparation_fcfa, date_signalement) VALUES
                (5, 'GPE-000017', 'Photocopieur Industriel HP LaserJet Enterprise Flow', 'Bourrage papier systématique dans le module d''alimentation et tambour usé.', 'Haute', 'En cours', 'Maintenance Informatique Pro CI', 185000, '2026-11-10');
            ");
        }
    }
}
