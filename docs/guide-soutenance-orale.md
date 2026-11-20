# GUIDE DE PRÉPARATION À LA SOUTENANCE ORALE
## PROJET : GEST-PATRIMOINE (Gestion du Patrimoine de l'État)
### Candidate : Amele Rolande GOLI | Formatrice : Mme Amele Rolande GOLI

---

Ce document prépare l'argumentaire complet et structuré pour soutenir avec brio la solution devant le jury et la formatrice.

---

## 1. Plan Conseillé pour la Présentation (15 à 20 minutes)

1. **Introduction & Problématique (3 min)** :
   - Présentation de la gestion du patrimoine de l'État et des limites des classeurs papier et fichiers Excel.
   - Perte de traçabilité, absence de visibilité financière en FCFA, opacité des affectations.
2. **Cadrage Fonctionnel & Démonstration Live (7 min)** :
   - Connexion en profil Super-Administrateur.
   - Présentation des 4 KPI du Dashboard (Total des biens, Valeur cumulée en FCFA, Répartition par état, Pannes).
   - Enregistrement d'un nouveau bien avec auto-génération du code national `GPE-XXXXXX`.
   - Affectation du bien à un agent détenteur.
   - Signalement d'une panne et suivi de la maintenance.
   - Génération et impression du rapport officiel `Rapports - GPE` (conforme au document certifié).
3. **Défense de l'Architecture Technique & Choix Technologiques (5 min)** :
   - Découpage en microservices (Auth, Patrimoine, Affectation, Gateway).
   - Justification du pattern "Database-per-service" avec PostgreSQL.
   - Rôle de Docker et isolation réseau.
4. **Conclusion & Perspectives d'Évolution (2 min)** :
   - Module de géolocalisation GPS des véhicules de l'État.
   - Application mobile pour scan d'inventaire physique par QR code / code-barres.

---

## 2. Réponses Types aux 10 Questions Clés du Jury

### Q1. Pourquoi avoir choisi Laravel / PHP ?
> **Réponse type** :
> *"J'ai choisi Laravel car c'est l'un des frameworks les plus matures et robustes du web pour les applications de gestion d'entreprise et d'administration publique. Son architecture MVC stricte, son ORM Eloquent qui prévient nativement les injections SQL, son système de migration de bases de données et sa gestion native de la sécurité (hachage Bcrypt, protection CSRF) permettent de délivrer en 40 heures une solution fiable, propre et facilement maintenable par d'autres développeurs de l'État."*

### Q2. Pourquoi PostgreSQL plutôt que MySQL ou SQLite ?
> **Réponse type** :
> *"Le patrimoine de l'État implique des données souveraines et des montants financiers colossaux en milliards de FCFA. PostgreSQL est reconnu mondialement pour son respect absolu des propriétés ACID (Atomicité, Cohérence, Isolation, Durabilité), sa gestion avancée des verrous concurrents et la rigueur de son typage. De plus, j'ai implémenté un système de fallback intelligent permettant à l'application de s'exécuter aussi bien sur PostgreSQL conteneurisé que sur SQLite en environnement local de test."*

### Q3. Pourquoi une architecture microservices plutôt qu'un monolithe ?
> **Réponse type** :
> *"Dans l'administration publique, les services n'évoluent pas au même rythme. L'authentification (Auth Service) est un service transverse qui peut être sollicité par des centaines de ministères. Le catalogue des biens (Patrimoine Service) subit de fortes charges lors des campagnes d'inventaire annuel. L'architecture microservices permet de faire évoluer, tester, déployer et scaler chaque domaine métier de façon totalement indépendante, sans risquer de faire tomber l'ensemble du système en cas d'anomalie sur un module."*

### Q4. Quelle est la différence concrète entre un monolithe et des microservices ?
> **Réponse type** :
> *"Dans un monolithe, tout le code (utilisateurs, biens, facturation, vues) partage la même base de code et la même base de données. Si une partie plante ou nécessite une mise à jour, c'est l'ensemble de l'application qui est indisponible. Dans une architecture microservices, l'application est découpée en briques autonomes (Auth sur le port 8001, Patrimoine sur le port 8002, Affectations sur le port 8003). Chaque microservice possède sa propre responsabilité métier, son propre cycle de vie et sa propre base de données dédiée (auth_db, patrimoine_db, affectation_db)."*

### Q5. Quel est le rôle exact de Docker et Docker Compose dans votre projet ?
> **Réponse type** :
> *"Docker résout le problème classique du 'ça marche sur ma machine mais pas sur le serveur'. Grâce aux Dockerfiles et à `docker-compose.yml`, l'ensemble de l'environnement (serveur PostgreSQL 16 multi-bases, dépendances PHP 8.2, extensions pdo_pgsql, et services web) est empaqueté dans des conteneurs isolés. N'importe quel évaluateur ou administrateur système peut cloner le dépôt et lancer l'intégralité de la plateforme en une seule commande : `docker compose up -d`."*

### Q6. Comment les microservices communiquent-ils entre eux ?
> **Réponse type** :
> *"La communication s'effectue via des APIs REST au format standardisé JSON. L'API Gateway (port 8080) fait office de point d'entrée unique pour les utilisateurs et route les requêtes vers les microservices internes via le réseau privé Docker `gest_patrimoine_net`. Les services peuvent également interagir entre eux de manière synchrone via des requêtes HTTP cURL sécurisées."*

### Q7. Comment sécurisez-vous les données sensibles du patrimoine de l'État ?
> **Réponse type** :
> *"La sécurité est assurée à plusieurs niveaux :*
> *1. **Réseau** : Les bases PostgreSQL ne sont pas exposées publiquement sur Internet, elles restent confinées au réseau privé Docker.*
> *2. **Authentification** : Mots de passe chiffrés en Bcrypt avec coût élevé (10 rounds) et échange de tokens JWT.*
> *3. **Contrôle d'accès RBAC** : Vérification stricte des rôles (Super-Admin, Gestionnaire, Agent).*
> *4. **Validation des entrées** : Filtrage strict contre les failles XSS et échappement SQL automatique via PDO préparé."*

### Q8. Comment gérez-vous les droits et rôles des utilisateurs ?
> **Réponse type** :
> *"J'ai mis en place une matrice RBAC (Role-Based Access Control) à trois niveaux :*
> *- Le **Super-Admin** a tous les pouvoirs (création d'utilisateurs, paramétrage ministériel).*
> *- Le **Gestionnaire** peut enregistrer des biens, valider des affectations et suivre les maintenances.*
> *- L'**Agent** a une visibilité restreinte aux matériels qui lui sont personnellement assignés et peut déclarer un dysfonctionnement."*

### Q9. Comment avez-vous planifié et organisé votre travail sur 40 heures ?
> **Réponse type** :
> *"J'ai suivi une démarche rigoureuse d'ingénierie logicielle :*
> *- **Phase 1 (10h)** : Analyse des besoins, cahier des charges, modélisation UML et maquettes Figma.*
> *- **Phase 2 (14h)** : Architecture microservices Laravel et bases de données PostgreSQL.*
> *- **Phase 3 (8h)** : Interface utilisateur unifiée, intégration de Chart.js et génération des rapports.*
> *- **Phase 4 (5h)** : Conteneurisation Docker, orchestration et scripts de lancement.*
> *- **Phase 5 (3h)** : Tests d'intégration, documentation normalisée et préparation de la soutenance.*
> *De plus, j'ai respecté les bonnes pratiques Git en développant chaque lot fonctionnel sur une branche dédiée avant de la fusionner sur main."*

### Q10. Quelles améliorations futures pourriez-vous apporter à cette solution ?
> **Réponse type** :
> *"Pour la phase 2 du projet, j'ai identifié 3 évolutions majeures :*
> *1. **Application mobile Flutter / PWA** permettant aux agents sur le terrain de scanner physiquement les QR codes collés sur les biens lors des inventaires annuels.*
> *2. **Intégration d'un bus de messages asynchrone (RabbitMQ ou Kafka)** pour découpler encore plus les microservices lors des pics de charge.*
> *3. **Télématique & IoT** : Balises GPS pour la géolocalisation en temps réel des véhicules administratifs de l'État et alertes de vidange automatiques."*
