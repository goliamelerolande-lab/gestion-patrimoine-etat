# Fiche technique — GPE

**Version documentaire :** 1.1 · **Date :** 13 novembre 2026 · **Auteur :** GOLI Amélé Rolande

## 1. Identification

| Élément | Description |
|---|---|
| Nom | Gestion du Patrimoine de l’État (GPE) |
| Type | Application web de démonstration, architecture microservices |
| Objectif | Centraliser un registre de biens, des sites et des comptes utilisateurs |
| Public cible du MVP | Administrateur et agents de gestion |
| Périmètre de cette version | Biens, sites, comptes, notifications, indicateurs et rapport imprimable |
| État de déploiement | Environnement Docker local ; hébergement public à réaliser |

## 2. Architecture technique

| Composant | Responsabilité | Technologie / accès |
|---|---|---|
| `gateway` | Point d’entrée HTTP et routage vers les services | Nginx Alpine, port hôte `8080` |
| `auth-service` | Connexion, jetons API et comptes utilisateurs | Laravel 13, PHP 8.4, routes internes `/api/login`, `/api/me`, `/api/users` |
| `patrimoine-service` | Interface Blade, biens, indicateurs, notifications et rapports | Laravel 13, PHP 8.4, pages web et API biens |
| `localisation-service` | CRUD des sites et routes d’affectation | Laravel 13, PHP 8.4, API localisation |
| `postgres` | Persistance relationnelle des trois domaines | PostgreSQL 16, volume Docker `pgdata` |
| Navigateur | Interface utilisateur | HTML, Blade, Tailwind CSS 4, JavaScript, Vite 8 |

Les services partagent le réseau privé Docker `patrinet`. Nginx transmet `/api/auth/*` vers `auth-service`, `/api/patrimoine/*` vers `patrimoine-service`, `/api/localisation/*` vers `localisation-service`, et les pages web vers `patrimoine-service`.

## 3. Stockage de données

La base `patrimoine` contient trois schémas :

| Schéma | Tables principales | Rôle |
|---|---|---|
| `auth` | `users`, `api_tokens` | Comptes, rôles simples (`admin`, `agent`), état actif, empreintes de jetons |
| `patrimoine` | `categories`, `biens`, `notifications` | Référentiel, registre des biens et notifications |
| `localisation` | `sites`, `affectations` | Sites et affectations |

Les clés étrangères restent internes au schéma lorsque c’est possible. Une affectation porte des identifiants logiques vers un bien ou un utilisateur géré dans un autre service ; le schéma actuel ne crée pas de FK inter-schémas pour ces identifiants.

### Principales règles de gestion

- Le code d’inventaire est généré côté serveur au format `GPE-000001` et doit être unique.
- La valeur d’acquisition est positive ou nulle ; la date d’acquisition est requise.
- Le mot de passe administrateur doit contenir au moins 12 caractères lorsqu’il est créé ou remplacé.
- Les mots de passe sont hachés avec le mécanisme de hachage Laravel ; ils ne sont jamais renvoyés dans les réponses JSON.
- Un administrateur actif ne peut pas désactiver son propre compte ni retirer son propre rôle.
- Le dernier administrateur actif ne peut pas être désactivé ou rétrogradé.
- Une désactivation révoque les jetons du compte.
- Les suppressions de biens sont idempotentes ; répéter une suppression ne renvoie pas « No query results ».

## 4. Flux d’authentification administrateur

1. Le navigateur soumet le formulaire `/connexion` au service web Patrimoine avec protection CSRF.
2. `patrimoine-service` appelle `auth-service` sur le réseau privé Docker.
3. `auth-service` valide le compte administrateur et émet un jeton aléatoire valable huit heures.
4. Seule l’empreinte SHA-256 du jeton est stockée dans `auth.api_tokens` ; le jeton expire après huit heures.
5. `patrimoine-service` place le jeton en session serveur ; le navigateur reçoit uniquement l’identifiant de session.
6. Le middleware vérifie `/api/me` avant l’accès à la gestion utilisateurs.
7. La déconnexion révoque le jeton et invalide la session web.

La session web possède en plus une expiration après inactivité (120 minutes par défaut, configurable via `SESSION_LIFETIME`).

La connexion des comptes `agent` n’est pas encore ouverte dans l’interface. Le rôle agent existe pour l’administration, mais le flux fonctionnel de l’agent reste à définir.

## 5. Interfaces HTTP principales

### Auth service (via la gateway)

| Méthode | Route publique gateway | Route interne auth-service | Protection | Usage |
|---|---|---|---|---|
| POST | `/api/auth/login` | `/api/login` | Limitation 5/minute | Connexion admin |
| GET | `/api/auth/me` | `/api/me` | Jeton admin | Vérifier la session |
| POST | `/api/auth/logout` | `/api/logout` | Jeton admin | Révoquer la session |
| GET | `/api/auth/users` | `/api/users` | Jeton admin | Lister les comptes |
| POST | `/api/auth/users` | `/api/users` | Jeton admin | Créer un compte |
| PUT | `/api/auth/users/{id}` | `/api/users/{id}` | Jeton admin | Modifier un compte ou son mot de passe |
| PATCH | `/api/auth/users/{id}/status` | `/api/users/{id}/status` | Jeton admin | Activer ou désactiver un compte |

### Patrimoine et localisation

Les routes REST `biens`, `sites` et `affectations` sont décrites dans [les routes de l’application](../services/patrimoine/routes/api.php) et [celles du service localisation](../services/localisation/routes/api.php). Les routes métier ne sont pas encore intégralement protégées par authentification et doivent être durcies avant toute exposition publique.

## 6. Conteneurisation et configuration

- `docker-compose.yml` construit les trois services Laravel et démarre PostgreSQL et la gateway.
- Le volume `pgdata` conserve les données PostgreSQL entre les redémarrages.
- Des volumes nommés isolent les dépendances Composer des services.
- Les `.env` sont locaux, ignorés par Git et doivent définir `DB_CONNECTION=pgsql`, `DB_HOST=postgres` et les identifiants de la base.
- La gateway locale écoute sur `http://localhost:8080` ; TLS n’est pas configuré en local.

## 7. Qualités, limites et décisions

### Décisions

- Trois services regroupent les domaines connexes sans multiplier les petits services pour un MVP individuel.
- PostgreSQL fournit une cohérence relationnelle et une séparation logique en schémas.
- La passerelle Nginx fournit un point d’entrée unique et masque les ports internes des services web.
- Les notifications sont stockées pour rester consultables après navigation ou redémarrage.

### Limites connues

- Les biens et les sites partagent le même utilisateur PostgreSQL et les opérations métier ne sont pas encore toutes authentifiées.
- Les fonctions campagne d’inventaire, pièces jointes, amortissement, journal d’audit et historique de mouvements ne sont pas encore livrées.
- Le rapport est imprimable depuis le navigateur ; un export PDF/CSV complet n’est pas inclus.
- Les sessions web sont stockées localement dans le conteneur. Une mise à l’échelle horizontale nécessite un magasin de sessions partagé.
- Aucun déploiement gratuit ne garantit à la fois disponibilité permanente, stockage durable et sauvegardes. Voir [la proposition financière](proposition-financiere.md).

## 8. Évolutions recommandées

1. Protéger les APIs biens, sites et affectations par des politiques d’accès.
2. Établir une matrice RBAC validée par le commanditaire et l’appliquer aux routes.
3. Ajouter des tests automatisés pour les règles de dernier administrateur, l’expiration de jetons et les opérations CRUD.
4. Ajouter un audit immuable, l’historique des affectations et les pièces justificatives.
5. Déployer avec secrets gérés, TLS, sauvegardes, supervision et compte PostgreSQL distinct par service.

## 9. Vérifications de développement

Depuis le dépôt :

```powershell
docker compose ps
docker exec -w /var/www gpe-auth php artisan route:list --path=api
docker exec -w /var/www gpe-patrimoine php artisan route:list
Set-Location services/patrimoine; npm run build
```

Ces commandes vérifient le démarrage, les routes et la compilation frontend ; elles ne remplacent pas une recette fonctionnelle complète.
