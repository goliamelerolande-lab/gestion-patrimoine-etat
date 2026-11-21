-- ====================================================================
-- GEST-PATRIMOINE - INITIALISATION MULTI-BASES POSTGRESQL
-- Conçu pour Docker & Architecture Microservices
-- ====================================================================

-- 1. CRÉATION DES BASES DE DONNÉES PAR MICROSERVICE
CREATE DATABASE auth_db;
CREATE DATABASE patrimoine_db;
CREATE DATABASE affectation_db;

-- ====================================================================
-- STRUCTURE ET DONNÉES DE AUTH_DB (Microservice Authentification)
-- ====================================================================
\c auth_db;

CREATE TABLE IF NOT EXISTS ministeres (
    id SERIAL PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    nom VARCHAR(255) NOT NULL,
    sigle VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'agent', -- superadmin, gestionnaire, agent, auditeur
    matricule VARCHAR(50) UNIQUE NOT NULL,
    telephone VARCHAR(50),
    ministere_id INT REFERENCES ministeres(id) ON DELETE SET NULL,
    service VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des ministères initiaux (Togo)
INSERT INTO ministeres (code, nom, sigle) VALUES
('MEF', 'Ministère de l''Économie et des Finances (Togo)', 'MEF'),
('MENTD', 'Ministère de l''Économie Numérique et de la Transformation Digitale', 'MENTD'),
('MTPI', 'Ministère des Travaux Publics et des Infrastructures', 'MTPI'),
('MEPST', 'Ministère des Enseignements Primaire, Secondaire et Technique', 'MEPST'),
('MSHPAUS', 'Ministère de la Santé, de l''Hygiène Publique et de l''Accès Universel aux Soins', 'MSHPAUS')
ON CONFLICT DO NOTHING;

-- Insertion des utilisateurs de démonstration (Mot de passe : 'password123' ou 'secret')
-- Hash bcrypt pour 'password123' : $2y$10$wTfD6g0u3lUfN7zZ9X0Xee1R3aQ9JgVqGj1gXyU2oJ8qM1fB9lImi
INSERT INTO users (name, email, password, role, matricule, telephone, ministere_id, service) VALUES
('Amele Rolande GOLI', 'admin@patrimoine.gouv.tg', '$2y$10$wTfD6g0u3lUfN7zZ9X0Xee1R3aQ9JgVqGj1gXyU2oJ8qM1fB9lImi', 'superadmin', 'MAT-TG-001', '+228 90 00 00 01', 1, 'Direction Générale du Patrimoine de l''État (DGPE Togo)'),
('Koffi AGBEKO', 'gestionnaire@patrimoine.gouv.tg', '$2y$10$wTfD6g0u3lUfN7zZ9X0Xee1R3aQ9JgVqGj1gXyU2oJ8qM1fB9lImi', 'gestionnaire', 'MAT-TG-002', '+228 91 00 00 02', 1, 'Direction du Matériel & de la Logistique'),
('Afiwa MENSAH', 'agent@patrimoine.gouv.tg', '$2y$10$wTfD6g0u3lUfN7zZ9X0Xee1R3aQ9JgVqGj1gXyU2oJ8qM1fB9lImi', 'agent', 'MAT-TG-003', '+228 92 00 00 03', 2, 'Direction de la Transformation Digitale'),
('Komi AMEGAN', 'auditeur@patrimoine.gouv.tg', '$2y$10$wTfD6g0u3lUfN7zZ9X0Xee1R3aQ9JgVqGj1gXyU2oJ8qM1fB9lImi', 'agent', 'MAT-TG-004', '+228 93 00 00 04', 1, 'Inspection Générale des Finances - Lomé')
ON CONFLICT DO NOTHING;

-- ====================================================================
-- STRUCTURE ET DONNÉES DE PATRIMOINE_DB (Microservice Patrimoine)
-- ====================================================================
\c patrimoine_db;

CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    libelle VARCHAR(150) NOT NULL,
    duree_amortissement_ans INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS biens (
    id SERIAL PRIMARY KEY,
    code_inventaire VARCHAR(50) UNIQUE NOT NULL, -- Format standard GPE-XXXXXX
    libelle VARCHAR(255) NOT NULL,
    categorie_id INT REFERENCES categories(id) ON DELETE RESTRICT,
    date_acquisition DATE NOT NULL,
    valeur_acquisition_fcfa BIGINT NOT NULL,
    valeur_actuelle_fcfa BIGINT NOT NULL,
    etat VARCHAR(50) NOT NULL DEFAULT 'Neuf', -- Neuf, Bon état, Passable, En panne, Réformé
    statut VARCHAR(50) NOT NULL DEFAULT 'Disponible', -- Disponible, Affecté, En réparation, Sorti
    ministere VARCHAR(150) NOT NULL,
    localisation VARCHAR(255) NOT NULL,
    fournisseur VARCHAR(150),
    numero_serie VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des catégories de biens
INSERT INTO categories (code, libelle, duree_amortissement_ans) VALUES
('VEH', 'Véhicules & Engins Roulants', 5),
('BAT', 'Immobilier & Bâtiments Administratifs', 25),
('INF', 'Matériels Informatiques & Télécoms', 3),
('MOB', 'Mobiliers & Agencements de Bureau', 10),
('DIV', 'Équipements & Matériels Divers', 5)
ON CONFLICT DO NOTHING;

-- Insertion des biens d'exemple (Togo)
INSERT INTO biens (code_inventaire, libelle, categorie_id, date_acquisition, valeur_acquisition_fcfa, valeur_actuelle_fcfa, etat, statut, ministere, localisation, fournisseur) VALUES
('GPE-000013', 'Lot Sanitaire & Robinetterie Institutionnelle', 5, '2026-11-13', 120000, 120000, 'Neuf', 'Disponible', 'Ministère de l''Économie et des Finances', 'Magasin Central - Lomé Bâtiment C', 'Comptoir Togolais de Fourniture'),
('GPE-000014', 'Toyota Hilux Double Cabine 4x4 2.8 D-4D', 1, '2026-01-15', 26500000, 24000000, 'Bon état', 'Affecté', 'Ministère de l''Économie et des Finances', 'Garage Central Administratif - Lomé', 'CFAO Motors Togo'),
('GPE-000015', 'Serveur Rack Dell PowerEdge R750 64GB', 3, '2026-02-20', 8900000, 7800000, 'Bon état', 'Affecté', 'Ministère de l''Économie Numérique et de la Transformation Digitale', 'Lomé Data Centre - Salle Serveurs', 'Dell Technologies Togo Partner'),
('GPE-000016', 'Immeuble Administratif R+5 Cité OUA', 2, '2015-05-10', 850000000, 790000000, 'Bon état', 'Affecté', 'Ministère de l''Économie et des Finances', 'Lomé Cité OUA, Avenue de la Libération', 'Société Togolaise de Bâtiment et Travaux Publics (STBTP Lomé)')
ON CONFLICT DO NOTHING;

-- ====================================================================
-- STRUCTURE ET DONNÉES DE AFFECTATION_DB (Microservice Affectations & Maintenance)
-- ====================================================================
\c affectation_db;

CREATE TABLE IF NOT EXISTS affectations (
    id SERIAL PRIMARY KEY,
    bien_id INT NOT NULL,
    code_inventaire VARCHAR(50) NOT NULL,
    beneficiaire_nom VARCHAR(150) NOT NULL,
    beneficiaire_matricule VARCHAR(50) NOT NULL,
    service_nom VARCHAR(150) NOT NULL,
    date_affectation DATE NOT NULL DEFAULT CURRENT_DATE,
    date_restitution DATE,
    motif VARCHAR(255) NOT NULL,
    statut VARCHAR(50) NOT NULL DEFAULT 'En cours', -- En cours, Restitué, Transféré
    remarques TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS maintenances (
    id SERIAL PRIMARY KEY,
    bien_id INT NOT NULL,
    code_inventaire VARCHAR(50) NOT NULL,
    libelle_bien VARCHAR(255) NOT NULL,
    description_panne TEXT NOT NULL,
    priorite VARCHAR(50) NOT NULL DEFAULT 'Moyenne', -- Basse, Moyenne, Haute, Critique
    statut VARCHAR(50) NOT NULL DEFAULT 'En attente', -- En attente, En cours, Réparé, Irréparable
    prestataire VARCHAR(150),
    cout_reparation_fcfa BIGINT DEFAULT 0,
    date_signalement DATE NOT NULL DEFAULT CURRENT_DATE,
    date_resolution DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Données initiales d'affectations (Togo)
INSERT INTO affectations (bien_id, code_inventaire, beneficiaire_nom, beneficiaire_matricule, service_nom, date_affectation, motif, statut) VALUES
(2, 'GPE-000014', 'Kodjo AGBEKO', 'MAT-TG-002', 'Direction Générale du Trésor et de la Comptabilité Publique - Lomé', '2026-01-20', 'Mise à disposition pour mission d''audit régionale', 'En cours'),
(3, 'GPE-000015', 'Afiwa MENSAH', 'MAT-TG-003', 'Direction de la Transformation Digitale - MENTD', '2026-02-25', 'Hébergement applicatif ministériel national', 'En cours')
ON CONFLICT DO NOTHING;

INSERT INTO maintenances (bien_id, code_inventaire, libelle_bien, description_panne, priorite, statut, prestataire, cout_reparation_fcfa, date_signalement) VALUES
(3, 'GPE-000015', 'Serveur Rack Dell PowerEdge R750 64GB', 'DYJONCTION', 'Haute', 'En attente', 'Prise en charge interne MENTD', 0, CURRENT_DATE)
ON CONFLICT DO NOTHING;
