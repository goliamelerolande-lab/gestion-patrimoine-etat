# FICHE TECHNIQUE DU PROJET
## GEST-PATRIMOINE — Architecture Microservices & Stack Technologique

---

## 1. Vue d'Ensemble de la Stack Technique

| Composant | Technologie Choisie | Version | Justification du Choix |
| :--- | :--- | :--- | :--- |
| **Backend Framework** | **Laravel (PHP)** | 10.x / 11.x (PHP 8.2) | Robustesse, architecture MVC élégante, ORM Eloquent performant, écosystème mature pour APIs |
| **Base de Données** | **PostgreSQL** | 16.x | Robustesse ACID légendaire, support avancé des types JSONB, intégrité référentielle stricte pour les données publiques |
| **Frontend** | **Blade / HTML5 / CSS3 / JavaScript** | ES6+ & Bootstrap 5 | Légèreté, pas de dépendance complexe superflue, rendu ultra-rapide, compatibilité totale tous terminaux |
| **Data Visualisation** | **Chart.js** | 4.x | Graphiques dynamiques et interactifs pour les tableaux de bord ministériels |
| **Conteneurisation** | **Docker & Docker Compose** | 29.x / Compose v5 | Isolation parfaite des environnements, portabilité garantie sans conflits d'installation |
| **Web Server / Reverse Proxy**| **Nginx / Artisan Web Server** | Stable | Routage des requêtes HTTP vers les microservices appropriés |

---

## 2. Découpage en Microservices

L'application respecte le principe fondamental du pattern microservices : **"Database per Service"** (chaque microservice est maître de sa propre persistance).

```
                            [ UTILISATEURS / AGENTS ]
                                       │
                                       ▼ HTTP (Port 8080)
                         ┌───────────────────────────┐
                         │   API GATEWAY & FRONTEND  │
                         │   Tableau de Bord Unifié  │
                         └─────────────┬─────────────┘
                                       │
             ┌─────────────────────────┼─────────────────────────┐
             ▼                         ▼                         ▼
    ┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
    │  AUTH SERVICE   │       │ PATRIMOINE SVC  │       │ AFFECTATION SVC │
    │   Port : 8001   │       │   Port : 8002   │       │   Port : 8003   │
    └────────┬────────┘       └────────┬────────┘       └────────┬────────┘
             │                         │                         │
             ▼                         ▼                         ▼
      [ PostgreSQL ]            [ PostgreSQL ]            [ PostgreSQL ]
         auth_db                 patrimoine_db            affectation_db
```

### Microservice 1 : `auth-service` (Port 8001)
- **Responsabilité** : Gestion des identités, rôles, permissions, ministères et services.
- **Base de données** : `auth_db`
- **Endpoints Clés** :
  - `POST /api/auth/login` : Authentification et émission du token.
  - `POST /api/auth/logout` : Révocation du token.
  - `GET /api/auth/me` : Profil de l'utilisateur connecté.
  - `GET /api/users` : Annuaire des agents de l'État.
  - `GET /api/departments` : Liste des ministères et directions.

### Microservice 2 : `patrimoine-service` (Port 8002)
- **Responsabilité** : Catalogue complet des biens étatiques, cycle de vie, amortissement, inventaire.
- **Base de données** : `patrimoine_db`
- **Endpoints Clés** :
  - `GET /api/biens` : Liste paginée et filtrée des biens.
  - `POST /api/biens` : Enregistrement d'un nouveau bien (avec génération du code `GPE-XXXXXX`).
  - `GET /api/biens/{id}` : Fiche détaillée d'un bien.
  - `PUT /api/biens/{id}` : Mise à jour de l'état ou caractéristiques.
  - `DELETE /api/biens/{id}` : Sortie d'inventaire / Réforme.
  - `GET /api/categories` : Catégories de biens (Véhicules, Bâtiments, etc.).
  - `GET /api/stats/patrimoine` : Total des biens et valeur financière cumulée en FCFA.

### Microservice 3 : `affectation-service` (Port 8003)
- **Responsabilité** : Traçabilité des détenteurs, mouvements inter-services et cycle de maintenance des équipements.
- **Base de données** : `affectation_db`
- **Endpoints Clés** :
  - `GET /api/affectations` : Registre des affectations en cours et passées.
  - `POST /api/affectations` : Assigner un bien à un agent / bureau.
  - `POST /api/affectations/restituer/{id}` : Clôture d'affectation avec état des lieux.
  - `GET /api/maintenances` : Suivi des pannes et entretiens.
  - `POST /api/maintenances` : Déclaration d'un incident ou demande de réparation.
  - `PUT /api/maintenances/{id}` : Mise à jour de l'état d'intervention et coût en FCFA.

---

## 3. Configuration Réseau & Conteneurisation Docker

### Réseau Privé Docker : `gest_patrimoine_net`
Tous les conteneurs partagent un pont réseau isolé (`bridge`), interdisant tout accès externe non autorisé aux bases de données tout en permettant une communication inter-services fluide par nom d'hôte Docker.

### Variables d'Environnement Standardisées
- `DB_CONNECTION=pgsql`
- `DB_HOST=postgres`
- `DB_PORT=5432`
- `DB_USERNAME=postgres`
- `DB_PASSWORD=secret_gpe_2026`
- `APP_ENV=production / local`
- `APP_KEY=base64:...`
