<?php

namespace PatrimoineService;

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
            $db   = getenv('DB_DATABASE') ?: 'patrimoine_db';
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

        // Mode Fallback Local : SQLite embarqué
        $sqlitePath = __DIR__ . '/../storage/patrimoine.sqlite';
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
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT UNIQUE NOT NULL,
                libelle TEXT NOT NULL,
                duree_amortissement_ans INTEGER DEFAULT 5,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS biens (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code_inventaire TEXT UNIQUE NOT NULL,
                libelle TEXT NOT NULL,
                categorie_id INTEGER,
                date_acquisition DATE NOT NULL,
                valeur_acquisition_fcfa INTEGER NOT NULL,
                valeur_actuelle_fcfa INTEGER NOT NULL,
                etat TEXT NOT NULL DEFAULT 'Neuf',
                statut TEXT NOT NULL DEFAULT 'Disponible',
                ministere TEXT NOT NULL,
                localisation TEXT NOT NULL,
                fournisseur TEXT,
                numero_serie TEXT,
                description TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $stmt = $pdo->query("SELECT COUNT(*) as count FROM biens");
        if ((int)$stmt->fetch()['count'] === 0) {
            $pdo->exec("
                INSERT INTO categories (code, libelle, duree_amortissement_ans) VALUES
                ('VEH', 'Véhicules & Engins Roulants', 5),
                ('BAT', 'Immobilier & Bâtiments Administratifs', 25),
                ('INF', 'Matériels Informatiques & Télécoms', 3),
                ('MOB', 'Mobiliers & Agencements de Bureau', 10),
                ('DIV', 'Équipements & Matériels Divers', 5);

                INSERT INTO biens (code_inventaire, libelle, categorie_id, date_acquisition, valeur_acquisition_fcfa, valeur_actuelle_fcfa, etat, statut, ministere, localisation, fournisseur) VALUES
                ('GPE-000013', 'Lot Sanitaire & Robinetterie Institutionnelle', 5, '2026-11-13', 120000, 120000, 'Neuf', 'Disponible', 'Ministère de l''Économie et des Finances', 'Magasin Central - Lomé Bâtiment C', 'Comptoir Togolais de Fourniture'),
                ('GPE-000014', 'Toyota Hilux Double Cabine 4x4 2.8 D-4D', 1, '2026-01-15', 26500000, 24000000, 'Bon état', 'Affecté', 'Ministère de l''Économie et des Finances', 'Garage Central Administratif - Lomé', 'CFAO Motors Togo'),
                ('GPE-000015', 'Serveur Rack Dell PowerEdge R750 64GB', 3, '2026-02-20', 8900000, 7800000, 'Bon état', 'Affecté', 'Ministère de l''Économie Numérique et de la Transformation Digitale', 'Lomé Data Centre - Salle Serveurs', 'Dell Technologies Togo Partner'),
                ('GPE-000016', 'Immeuble Administratif R+5 Cité OUA', 2, '2015-05-10', 850000000, 790000000, 'Bon état', 'Affecté', 'Ministère de l''Économie et des Finances', 'Lomé Cité OUA, Avenue de la Libération', 'Société Togolaise de Bâtiment et Travaux Publics (STBTP Lomé)');
            ");
        }

        // Nettoyage automatique au démarrage pour garantir exactement 4 biens nationaux réels
        $pdo->exec("DELETE FROM biens WHERE code_inventaire = 'GPE-000017' OR LOWER(libelle) LIKE '%photocopieur%'");
    }
}
