# Trame de soutenance — Gestion du Patrimoine de l’État

**Durée suggérée :** 5 à 7 minutes · **Auteur :** GOLI Amélé Rolande

## 1. Présentation orale

> Les administrations peuvent gérer leurs biens sur des registres séparés, ce qui rend les mises à jour, la consultation et la consolidation difficiles. GPE propose une interface web pour centraliser un registre de biens, organiser les sites, consulter des indicateurs et conserver des notifications. Le MVP est réalisé en Laravel, PostgreSQL et Docker, avec trois services séparés derrière une gateway Nginx.

## 2. Déroulé de démonstration

| Temps | Action | Message à faire passer |
|---:|---|---|
| 0:00–0:40 | Présenter le besoin et le périmètre | GPE vise la traçabilité et une vue consolidée du patrimoine. |
| 0:40–1:30 | Montrer la vue d’architecture et PostgreSQL | Auth, patrimoine et localisation ont chacun leur responsabilité et leur schéma. |
| 1:30–2:30 | Se connecter et ouvrir Utilisateurs | La connexion admin est réelle ; les comptes sont persistés, activables et désactivables. |
| 2:30–3:30 | Ouvrir Biens, créer puis modifier un bien de démonstration | Le code inventaire est créé par le serveur et la notification est persistée. |
| 3:30–4:10 | Ouvrir Sites et Notifications | Montrer le CRUD site et la conservation / lecture des notifications. |
| 4:10–4:50 | Ouvrir le rapport global | Montrer les agrégats et l’impression navigateur. |
| 4:50–5:40 | Présenter les limites et prochaines étapes | Inventaire physique, audit, fichiers, RBAC métier complet et déploiement durable restent à réaliser. |

Pour une soutenance propre, préparer au préalable un jeu de données fictif, vérifier que les conteneurs sont démarrés, créer un compte admin de démonstration et vérifier l’URL locale. Ne pas utiliser des informations patrimoniales réelles.

## 3. Défense des choix d’architecture

### Pourquoi trois microservices ?

Le découpage Auth / Patrimoine / Localisation sépare les responsabilités centrales du brief et reste présentable dans le délai d’un projet individuel de 40 heures. Des services supplémentaires pour les notifications, les inventaires et les rapports auraient augmenté le nombre de déploiements sans bénéfice démontrable pour ce MVP.

### Est-ce une isolation complète des données ?

Non. PostgreSQL comporte trois schémas métier, mais les services partagent aujourd’hui le même serveur et les mêmes identifiants. C’est une séparation logique pour le MVP ; une exploitation renforcée demanderait des rôles et permissions PostgreSQL distincts, des secrets séparés et des limites réseau.

### Comment la connexion admin est-elle protégée ?

Le navigateur soumet un formulaire web avec CSRF. Le service Patrimoine contacte Auth, qui valide le mot de passe et émet un jeton aléatoire expirant après huit heures. Le hachage SHA-256 du jeton est conservé en base ; le jeton en clair reste dans la session serveur. La gestion de comptes est protégée. Les APIs biens et sites doivent encore être protégées avant tout usage public.

### Pourquoi l’hébergement gratuit est-il présenté comme démo ?

Les offres gratuites peuvent mettre les services en veille, perdre le stockage local ou limiter la conservation et les sauvegardes PostgreSQL. La solution actuelle est conteneurisée pour le développement local ; un déploiement permanent avec données fiables doit prévoir une base persistante et des sauvegardes.

## 4. Questions fréquentes

**Comment le code d’inventaire est-il unique ?**  
Il est produit côté serveur à partir de la séquence PostgreSQL, au format `GPE-000001`, puis vérifié contre les codes déjà enregistrés.

**Pourquoi des notifications persistent-elles ?**  
Les événements retenus par le MVP écrivent une ligne dans `patrimoine.notifications` pendant la même transaction que l’action qui la déclenche. Elles restent consultables et leur état lu/non lu est conservé.

**Quelles améliorations sont prioritaires ?**  
Protéger toutes les APIs métier, ajouter une matrice RBAC complète et des tests d’autorisation, intégrer les catégories au formulaire de bien, implémenter l’historique des mouvements et ajouter des sauvegardes avant hébergement public.

## 5. Supports

- [Fiche technique](fiche-technique.md)
- [Analyse du besoin](analyse/besoin.md)
- [Guide utilisateur](guide-utilisation.md)
- [Diagrammes UML](uml/)
- [Planche de conception](figma/gpe-maquettes.svg)
