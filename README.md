# Gestion du Patrimoine de l’État (GPE)

**🔗 Dépôt GitHub** : [https://github.com/goliamelerolande-lab/gestion-patrimoine-etat](https://github.com/goliamelerolande-lab/gestion-patrimoine-etat)

Application web de démonstration pour enregistrer et consulter le patrimoine mobilier de l’État. Le projet est un MVP pédagogique en architecture microservices, avec Laravel, PostgreSQL, Docker et Nginx.

> **État du projet :** démo locale fonctionnelle. Les services et les écrans livrés sont décrits ci-dessous. Le déploiement public durable, l’authentification sur toutes les opérations métier et plusieurs fonctions du besoin cible restent à réaliser.

## Sommaire

- [Fonctionnalités](#fonctionnalités)
- [Architecture](#architecture)
- [Technologies](#technologies)
- [Démarrage local](#démarrage-local)
- [Créer le premier administrateur](#créer-le-premier-administrateur)
- [Structure du dépôt](#structure-du-dépôt)
- [Données et normalisation](#données-et-normalisation)
- [Sécurité et limites connues](#sécurité-et-limites-connues)
- [Déploiement](#déploiement)
- [Documentation du projet](#documentation-du-projet)
- [Branches et contributions](#branches-et-contributions)

## Fonctionnalités

| Module | État dans le MVP |
|---|---|
| Tableau de bord | Indicateurs globaux, derniers biens, aperçu des notifications |
| Biens | Liste paginée, création, consultation, modification, suppression et génération du code `GPE-xxxxxx` |
| Sites | Gestion des sites, codes SITE-XX générés automatiquement |
| Utilisateurs | Connexion administrateur, liste, création, modification, rôle `admin`/`agent`, activation et désactivation |
| Notifications | Enregistrement PostgreSQL lors de certains événements, consultation et marquage comme lu |
| Rapports | Totaux agrégés et aperçu imprimable |
| Affectations | Affecter un bien a un site, conserver l'historique et gerer les changements de site |
| Inventaires physiques, pièces jointes et amortissement | Besoin cible, pas encore disponibles dans le MVP |

L’interface des utilisateurs utilise le vrai service d’authentification. Le compte désactivé ne peut plus ouvrir de session et ses jetons existants sont révoqués. Le dernier administrateur actif ne peut pas être retiré ou désactivé.

## Architecture

```mermaid
flowchart LR
    B[Navigateur] -->|HTTP local / HTTPS en hébergement| G[Nginx API Gateway :8080]
    G -->|Pages Blade et API patrimoine| P[patrimoine-service Laravel]
    G -->|/api/auth/*| A[auth-service Laravel]
    G -->|/api/localisation/*| L[localisation-service Laravel]
    P -->|auth interne : connexion, session et comptes| A
    A --> DB[(PostgreSQL 16\nschéma auth)]
    P --> DBP[(PostgreSQL 16\nschéma patrimoine)]
    L --> DBL[(PostgreSQL 16\nschéma localisation)]