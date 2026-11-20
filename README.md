# GEST-PATRIMOINE — Plateforme Numérique de Gestion du Patrimoine de l'État

[![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://php.net)
[![Framework](https://img.shields.io/badge/Laravel-10.x%20%7C%2011.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Database](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![Docker](https://img.shields.io/badge/Docker-29.x-2496ED?logo=docker&logoColor=white)](https://docker.com)
[![Architecture](https://img.shields.io/badge/Architecture-Microservices-success)](https://microservices.io)
[![Licence](https://img.shields.io/badge/Licence-Gouvernementale-blue)](#)

> **Projet de Fin de Brief — Développeuse Full Stack**  
> **Auteure** : Amele Rolande GOLI  
> **Formatrice Référente** : Mme Amele Rolande GOLI  
> **Durée estimée** : ~40 heures | **Évaluation** : Soutenance Orale & Code GitHub

---

## 🏛️ 1. Contexte & Problématique du Projet

La gestion du patrimoine mobilier et immobilier de l'État (bâtiments, parcs de véhicules administratifs, serveurs et matériels informatiques, mobilier de bureau) repose historiquement sur des registres physiques et des tableurs Excel isolés.

### Conséquences majeures constatées :
- ❌ **Perte de traçabilité** des équipements lors des mutations ou réorganisations ministérielles.
- ❌ **Absence de visibilité financière** en temps réel sur la valeur cumulée du patrimoine en Francs CFA (FCFA).
- ❌ **Difficultés de localisation** physique des biens et des agents détenteurs.
- ❌ **Manque d'anticipation des pannes** et envolée des coûts de réparation.

### Solution Apportée : **GEST-PATRIMOINE**
Une plateforme web centralisée, moderne et conteneurisée permettant de :
1. Enregistrer chaque bien sous un **Code d'Inventaire National normalisé** (`GPE-XXXXXX`).
2. Gérer les **affectations**, décharges et transferts d'équipements aux agents de l'État.
3. Piloter le cycle complet de **maintenance et suivi des pannes** avec devis en FCFA.
4. Générer des **Rapports Globaux certifiés** avec calcul de valeur et impression PDF (conforme aux modèles officiels de la Direction Générale du Patrimoine).

---

## 🎯 2. Les 13 Compétences Visées (Référentiel RNCP Développeur Full Stack)

| N° | Compétence Clé | Validation dans le Projet |
| :---: | :--- | :--- |
| **C1** | Maquetter une application (Niv. 3) | Wireframes et Design System dans [`docs/design-figma-specifications.md`](docs/design-figma-specifications.md) |
| **C2** | Réaliser une UI web statique et adaptable (Niv. 3) | Interface 100% responsive, variables CSS et accessibilité |
| **C3** | Développer une interface web dynamique (Niv. 3) | Graphiques interactifs Chart.js, filtres instantanés, modales |
| **C4** | Rechercher de façon méthodique des solutions (Niv. 3) | Résolution du pattern "Database-per-service" et mode fallback local |
| **C5** | Créer une base de données (Niv. 3) | Conception PostgreSQL multi-bases (`auth_db`, `patrimoine_db`, `affectation_db`) |
| **C6** | Développer les composants d’accès aux données (Niv. 3) | Classes PDO sécurisées avec requêtes préparées contre les injections SQL |
| **C7** | Développer la partie back-end (Niv. 3) | Microservices PHP 8.2 / Laravel avec API REST et gestion des sessions |
| **C8** | Élaborer et mettre en œuvre des composants (Niv. 3) | Composants de routage, Client API cURL inter-services, contrôleurs |
| **C1-T** | Planifier le travail individuel (Niv. 3) | Planning 40h détaillé et estimation financière par lots |
| **C5-T** | Partager la solution adoptée (Niv. 3) | Dépôt Git documenté, code propre et scripts d'initialisation |
| **C6-T** | Présenter un travail en synthétisant ses résultats (Niv. 3) | Guide complet de soutenance orale dans [`docs/guide-soutenance-orale.md`](docs/guide-soutenance-orale.md) |
| **C7-T** | Se familiariser avec l'environnement pro (Niv. 3) | Conformance aux formats officiels de documents administratifs |
| **C8-T** | Interagir de façon professionnelle et constructive (Niv. 3) | Proposition financière formelle et cahier des charges |

---

## 🏗️ 3. Architecture Microservices & Stack Technique

L'application est découpée en microservices autonomes respectant le principe **"Database per Service"** :

```
                                  [ UTILISATEURS / AGENTS ]
                                              │
                                              ▼ HTTP
                                ┌───────────────────────────┐
                                │   API GATEWAY & FRONTEND  │ (Port 8080)
                                │  Dashboard, Vues & Proxy  │
                                └─────────────┬─────────────┘
                                              │
                    ┌─────────────────────────┼─────────────────────────┐
                    ▼                         ▼                         ▼
           ┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
           │  AUTH SERVICE   │       │ PATRIMOINE SVC  │       │ AFFECTATION SVC │
           │   (Port 8001)   │       │   (Port 8002)   │       │   (Port 8003)   │
           └────────┬────────┘       └────────┬────────┘       └────────┬────────┘
                    │                         │                         │
                    ▼                         ▼                         ▼
             [ PostgreSQL ]            [ PostgreSQL ]            [ PostgreSQL ]
                auth_db                 patrimoine_db            affectation_db
                    │                         │                         │
                    └─────────────────────────┼─────────────────────────┘
                                              ▼
                                 [ DOCKER COMPOSE NETWORK ]
                                   (gest_patrimoine_net)
```

### Détail des Composants :
1. **API Gateway & Frontend (`gateway-frontend/`) — Port 8080** :  
   Point d'entrée principal. Gère les vues du Dashboard, la consultation du registre, les filtres dynamiques, les graphiques Chart.js et l'édition des rapports certifiés.
2. **Microservice Auth (`auth-service/`) — Port 8001** :  
   Authentification des fonctionnaires, gestion des rôles (Super-Admin, Gestionnaire, Agent), annuaire ministériel. Base : `auth_db`.
3. **Microservice Patrimoine (`patrimoine-service/`) — Port 8002** :  
   Gestion du registre physique, attribution automatique des codes `GPE-XXXXXX`, valorisation comptable en FCFA, calcul d'amortissement. Base : `patrimoine_db`.
4. **Microservice Affectations & Maintenance (`affectation-service/`) — Port 8003** :  
   Traçabilité des mouvements d'équipements, décharges, signalement des pannes et facturation des réparations. Base : `affectation_db`.

---

## 📂 4. Structure du Dépôt

```
GEST-PATRIMOINE/
│
├── auth-service/                  # Microservice Authentification & Rôles (Port 8001)
│   ├── src/Database.php           # Connexion PostgreSQL & Fallback
│   ├── src/Controllers/           # Contrôleur API REST
│   └── public/index.php           # Routeur du service
│
├── patrimoine-service/            # Microservice Biens & Inventaires (Port 8002)
│   ├── src/Database.php           # Connexion PostgreSQL & Fallback
│   ├── src/Controllers/           # Gestion des codes GPE et biens
│   └── public/index.php           # Routeur du service
│
├── affectation-service/           # Microservice Affectations & Pannes (Port 8003)
│   ├── src/Database.php           # Connexion PostgreSQL & Fallback
│   ├── src/Controllers/           # Gestion des mouvements et réparations
│   └── public/index.php           # Routeur du service
│
├── gateway-frontend/              # API Gateway & Interface Web (Port 8080)
│   ├── src/ServicesClient.php     # Client cURL inter-microservices
│   ├── views/                     # Vues Dashboard, Biens, Rapports, Login
│   ├── public/css/app.css         # Styles institutionnels & impression
│   └── public/index.php           # Point d'entrée de l'application
│
├── docker/
│   ├── Dockerfile.service         # Image Docker PHP 8.2 + PDO PostgreSQL
│   └── init-databases.sql         # Script SQL multi-bases automatique
│
├── docs/                          # Dossier des Livrables Documentaires
│   ├── cahier-des-charges.md      # Spécifications fonctionnelles & contexte
│   ├── conception-uml.md          # 6 Diagrammes UML (Mermaid)
│   ├── fiche-technique.md         # Fiche technique d'architecture
│   ├── proposition-financiere.md  # Devis professionnel chiffré en FCFA
│   ├── guide-utilisation.md       # Manuel d'utilisation pas-à-pas
│   ├── design-figma-specifications.md # Guide UI & Design System
│   └── guide-soutenance-orale.md  # Réponses aux 10 questions types du jury
│
├── docker-compose.yml             # Orchestration complète Docker
├── render.yaml                    # Déploiement Cloud gratuit (Render)
├── start-local.bat                # Lancement local en 1 clic (sans Docker)
├── start-docker.bat               # Lancement Docker en 1 clic
└── README.md                      # Présentation et normalisation
```

---

## 🚀 5. Instructions d'Installation & Lancement

Vous avez le choix entre **deux méthodes** pour exécuter le projet :

### Méthode A : Avec Docker (Recommandée pour la conformité Microservices)
1. Assurez-vous que **Docker Desktop** est lancé.
2. Dans le dossier racine du projet, lancez :
   ```bash
   docker compose up -d
   ```
   *(Ou double-cliquez simplement sur `start-docker.bat`)*
3. Ouvrez votre navigateur sur : **`http://localhost:8080`**

### Méthode B : Lancement Local Immédiat (Sans Docker)
Si vous souhaitez tester la solution sans lancer de conteneurs :
1. Double-cliquez simplement sur le script **`start-local.bat`**  
   *(Ou tapez `php -S 0.0.0.0:8080 -t gateway-frontend/public`)*
2. L'application démarre automatiquement sur **`http://localhost:8080`** grâce au moteur de persistance embarqué.

---

## 🔑 6. Identifiants de Démonstration (Soutenance)

Des boutons de remplissage en 1 clic sont disponibles sur la page de connexion :

| Profil Utilisateur | Email Institutionnel | Mot de Passe | Privilèges |
| :--- | :--- | :--- | :--- |
| **Super-Administrateur** | `admin@patrimoine.gouv` | `password123` *(ou secret)* | Accès complet, paramétrage, gestion des agents |
| **Gestionnaire Logistique** | `gestionnaire@patrimoine.gouv` | `password123` | Registre des biens, affectations, pannes |
| **Agent / Détenteur** | `agent@patrimoine.gouv` | `password123` | Consultation de ses biens, déclaration de panne |

---

## 🌿 7. Normalisation Git & Historique des Branches

Conformément aux bonnes pratiques professionnelles de développement collaboratif, chaque sous-projet et chaque lot fonctionnel a été réalisé sur sa propre branche Git avant d'être fusionné (*merged*) sur la branche principale `main` :

- `feature/documentation-et-conception` ➔ Conception UML, cahier des charges, devis.
- `feature/docker-architecture` ➔ Configuration Docker multi-bases PostgreSQL.
- `feature/auth-service` ➔ Microservice d'authentification et rôles.
- `feature/patrimoine-service` ➔ Microservice du catalogue et inventaire des biens.
- `feature/affectation-service` ➔ Microservice des affectations et maintenances.
- `feature/frontend-gateway` ➔ Interface web unifiée, graphiques Chart.js et rapports.
- `feature/readme-et-livrables` ➔ Documentation, scripts de lancement et guide oral.

---

## 📄 8. Liens Directs vers les Livrables (`docs/`)

- 📘 [Cahier des Charges Fonctionnel & Analyse des Besoins](docs/cahier-des-charges.md)
- 📐 [Modélisation & Diagrammes UML Complets](docs/conception-uml.md)
- ⚙️ [Fiche Technique d'Architecture](docs/fiche-technique.md)
- 💰 [Proposition Financière & Devis Professionnel (FCFA)](docs/proposition-financiere.md)
- 📖 [Guide d'Utilisation Pas-à-Pas](docs/guide-utilisation.md)
- 🎨 [Spécifications Maquettes Figma & Design System](docs/design-figma-specifications.md)
- 🎓 [Guide de Préparation à la Soutenance Orale](docs/guide-soutenance-orale.md)
