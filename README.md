# 🏛️ GPE - Gestion du Patrimoine de l'État

Solution numérique microservices pour la centralisation et la traçabilité du patrimoine mobilier et immobilier de l'État.

## 📋 Table des matières
1. [Contexte et Objectifs](#1-contexte-et-objectifs)
2. [Architecture Microservices](#2-architecture-microservices)
3. [Stack Technique](#3-stack-technique)
4. [Modèle de Données et Normalisation](#4-modèle-de-données-et-normalisation)
5. [Installation et Lancement (Docker)](#5-installation-et-lancement-docker)
6. [API Endpoints](#6-api-endpoints)
7. [Captures d'écran](#7-captures-décran)

---

## 1. Contexte et Objectifs

Les administrations publiques gèrent un patrimoine considérable (bâtiments, véhicules, matériels informatiques) souvent de manière fragmentée (Excel, papier). 
**GPE** est une application web centralisée permettant :
- Un inventaire unique et traçable des biens.
- La gestion des affectations (biens ↔ sites ↔ agents).
- Le suivi des états (neuf, usage, réformé).
- Des rapports consolidés pour la direction.

## 2. Architecture Microservices

Le projet repose sur une architecture **microservices** conteneurisée avec Docker.

![Architecture](docs/uml/05-deploiement.png) *(Ajouter le schéma si tu l'as)*

| Service | Rôle | Port Interne |
|---------|------|--------------|
| **Gateway** | Reverse Proxy (Nginx) - Point d'entrée | 80 (exposé sur 8080) |
| **Auth Service** | Authentification des utilisateurs | 80 |
| **Patrimoine Service** | Gestion des biens, catégories, dashboard, rapports | 80 |
| **Localisation Service** | Gestion des sites, affectations | 80 |
| **PostgreSQL** | Base de données unique (3 schémas isolés) | 5432 |

## 3. Stack Technique

- **Backend** : Laravel 11 (PHP 8.4)
- **Frontend** : Blade + Tailwind CSS (via CDN)
- **Base de données** : PostgreSQL 16
- **Conteneurisation** : Docker & Docker Compose
- **Gateway** : Nginx

## 4. Modèle de Données et Normalisation

La base de données est divisée en **3 schémas** pour respecter l'isolation des microservices.

### 4.1 Normalisation (1NF → 3NF)

**Première Forme Normale (1NF)**
- Toutes les colonnes contiennent des valeurs atomiques (ex: `ville`, `region` séparées).
- Chaque table a une clé primaire unique (`id`).

**Deuxième Forme Normale (2NF)**
- Aucune dépendance partielle : toutes les colonnes dépendent entièrement de la clé primaire.
- Les tables de jonction (`affectations`, `lignes_inventaire`) ont leurs propres clés primaires.

**Troisième Forme Normale (3NF)**
- Pas de dépendance transitive. 
- *Exemple* : Le nom de la catégorie n'est pas stocké dans `biens`, mais dans la table `categories`. La table `biens` contient uniquement `category_id`.
- Les données calculées (ex: valeur résiduelle) ne sont pas stockées mais calculées à la volée.

### 4.2 Schémas

**Schéma `auth`**
- `users` (id, name, email, password, role)

**Schéma `patrimoine`**
- `categories` (id, code, libelle)
- `biens` (id, code_inventaire, libelle, category_id, date_acquisition, valeur_acquisition, etat)

**Schéma `localisation`**
- `sites` (id, code, nom, ville, region)
- `affectations` (id, bien_id, site_id, user_id, date_debut, date_fin)

## 5. Installation et Lancement (Docker)

### Prérequis
- Docker Desktop installé et lancé.

### Lancement
```bash
# Cloner le dépôt
git clone https://github.com/goliamelerolande-lab/gestion-patrimoine-etat.git
cd gestion-patrimoine-etat

# Lancer l'architecture complète
docker compose up -d --build