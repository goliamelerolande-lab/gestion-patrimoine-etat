# 🧠 Analyse Réflexive - Projet GPE

**Auteur** : GOLI Amélé Rolande
**Date** : Novembre 2026

## 1. Introduction

Ce projet de **Gestion du Patrimoine de l'État (GPE)** m'a permis de mettre en pratique l'ensemble des compétences de la formation Full Stack. En 5 jours (40h), j'ai conçu et développé une application microservices complète, de l'analyse du besoin jusqu'au déploiement local.

## 2. Démarche

J'ai suivi une approche **itérative** et **structurée** :

1. **Jour 1** : Analyse du besoin, diagrammes UML, maquettes Figma, proposition financière.
2. **Jour 2** : Setup Docker, PostgreSQL, initialisation des 3 microservices.
3. **Jour 3** : Développement de l'API (auth, patrimoine, localisation).
4. **Jour 4** : Frontend (Blade + Tailwind) et intégration des API.
5. **Jour 5** : Tests, documentation (README), analyse réflexive et préparation soutenance.

J'ai utilisé **Git** avec une branche `develop` et des branches `feature/` pour chaque fonctionnalité. Chaque branche a fait l'objet d'une Pull Request avant merge, simulant un workflow professionnel.

## 3. Difficultés rencontrées et solutions

### 3.1 Configuration de Docker avec PostgreSQL multi-schémas

**Difficulté** : Le `search_path` de PostgreSQL n'était pas interprété nativement par Laravel. Les requêtes échouaient car Laravel cherchait dans le schéma `public`.

**Solution** : J'ai modifié `config/database.php` pour ajouter `'schema' => env('DB_SEARCH_PATH', 'public')`, et j'ai précisé le nom du schéma dans les modèles (`protected $table = 'patrimoine.biens'`).

### 3.2 Erreur 504 Gateway Timeout

**Difficulté** : Nginx renvoyait des 504 car les conteneurs changeaient d'adresse IP interne et Nginx gardait l'ancienne en cache.

**Solution** : J'ai augmenté les timeouts dans `nginx.conf` (`proxy_read_timeout 120s`), et j'ai systématiquement fait `docker compose down && up` (au lieu de `restart`) pour forcer la recréation du réseau.

### 3.3 Méthode PUT/DELETE bloquée par Apache

**Difficulté** : Les requêtes `PUT` et `DELETE` étaient bloquées par Apache, provoquant des 404.

**Solution** : J'ai utilisé la méthode officielle Laravel : envoyer un `POST` avec `_method: 'PUT'` dans les données. C'est une astuce recommandée par la documentation Laravel.

### 3.4 Lenteur de l'application

**Difficulté** : Navigation lente à cause du rechargement des vues Blade à chaque requête.

**Solution** : Activation du cache de production (`php artisan optimize`) et suppression des effets de flou excessifs (`backdrop-blur`).

## 4. Ce que j'ai appris

### 4.1 Compétences techniques
- **Architecture microservices** : découpage, isolation, API Gateway.
- **Docker** : conteneurisation, réseaux, volumes, Compose.
- **Laravel avancé** : Eloquent, migrations, middlewares, API REST.
- **PostgreSQL** : schémas, contraintes, index, normalisation.
- **Nginx** : reverse proxy, configuration, timeouts.
- **Tailwind CSS** : design responsive, effets visuels.

### 4.2 Compétences transversales
- **Gestion du temps** : planification sur 5 jours, priorisation des tâches.
- **Recherche de solutions** : utilisation de la documentation officielle, Stack Overflow, tests itératifs.
- **Communication** : rédaction de documentation claire (README, guide).
- **Autonomie** : travail individuel, prise de décision.

## 5. Points d'amélioration

- **Tests unitaires** : Je n'ai pas eu le temps d'écrire des tests PHPUnit.
- **Authentification complète** : Les routes API ne sont pas toutes protégées.
- **Déploiement public** : Je n'ai pas pu déployer sur un hébergeur gratuit par manque de temps.
- **Documentation API** : Une collection Postman serait un plus.

## 6. Conclusion

Ce projet m'a permis de **consolider mes compétences Full Stack** et de découvrir les **bonnes pratiques DevOps** (Docker, Git Flow, CI/CD). Malgré les difficultés techniques, j'ai su **rechercher des solutions** et **livrer un MVP fonctionnel** en 5 jours.

Je suis fier du résultat : une application complète, avec une architecture professionnelle et une documentation soignée. Ce projet me prépare concrètement au métier de développeur Full Stack.