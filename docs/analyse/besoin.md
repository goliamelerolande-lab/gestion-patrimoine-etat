# 📋 Analyse du Besoin — Gestion du Patrimoine de l'État

**Auteur** : [TON NOM]
**Date** : Novembre 2026
**Version** : 1.0

---

## 1. Contexte et problématique

### 1.1 Contexte
Les administrations publiques gèrent un patrimoine considérable : terrains, bâtiments administratifs, véhicules, mobilier, équipements informatiques et matériels techniques. Aujourd'hui, cette gestion est souvent :
- **Fragmentée** : chaque ministère/service tient ses propres registres (souvent sur Excel ou papier).
- **Non traçable** : les transferts, affectations et mises en réforme ne sont pas historisés.
- **Non valorisée** : aucune vision consolidée de la valeur du patrimoine national.
- **Sujette aux pertes** : aucun inventaire périodique systématique.

### 1.2 Problématique
> Comment centraliser, tracer et valoriser le patrimoine de l'État sur une plateforme numérique unique, tout en permettant à chaque entité de gérer son périmètre local ?

### 1.3 Opportunité
Une solution microservices moderne, conteneurisée, accessible via navigateur, permet de :
- Unifier les données au niveau national
- Déléguer la saisie à chaque entité
- Offrir une vision décisionnelle en temps réel
- Préparer la transition vers la dématérialisation complète

---

## 2. Objectifs

### 2.1 Objectifs fonctionnels
| # | Objectif | Indicateur de succès |
|---|----------|----------------------|
| OF1 | Inventaire centralisé et unique des biens | 100% des biens saisis avec code inventaire unique |
| OF2 | Traçabilité complète des mouvements | Historique conservé sur chaque bien |
| OF3 | Affectation biens ↔ sites ↔ agents | Formulaire d'affectation opérationnel |
| OF4 | Suivi de l'amortissement comptable | Valeur résiduelle calculée automatiquement |
| OF5 | Inventaire physique périodique | Campagnes d'inventaire avec écarts identifiés |
| OF6 | Tableaux de bord décisionnels | Dashboard par région / ministère / catégorie |
| OF7 | Gestion des documents attachés | Upload factures, photos, actes |

### 2.2 Objectifs techniques
| # | Objectif |
|---|----------|
| OT1 | Architecture microservices scalable |
| OT2 | Conteneurisation complète via Docker |
| OT3 | Base PostgreSQL unique avec isolation par schéma |
| OT4 | API REST sécurisée (authentification par token) |
| OT5 | Interface web responsive (mobile + desktop) |
| OT6 | Séparation stricte des responsabilités par service |

---

## 3. Acteurs et rôles

| Acteur | Rôle | Actions principales |
|--------|------|---------------------|
| **Administrateur** | Super-utilisateur | Gestion des comptes, rôles, paramétrage global |
| **Gestionnaire de patrimoine** | Opérationnel | Création/modification des biens, affectations, transferts |
| **Agent de maintenance** | Technique | Suivi des interventions, mise à jour de l'état |
| **Agent d'inventaire** | Contrôle | Campagnes d'inventaire physique, saisie des écarts |
| **Direction / Décideur** | Stratégique | Consultation des tableaux de bord, rapports |
| **Auditeur** | Contrôle externe | Lecture seule, export des données |

### Matrice de permissions (RBAC)

| Action | Admin | Gestionnaire | Maintenance | Inventaire | Direction | Auditeur |
|--------|:-----:|:------------:|:-----------:|:----------:|:---------:|:--------:|
| Créer un bien | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Modifier un bien | ✅ | ✅ | ⚠️ état | ❌ | ❌ | ❌ |
| Affecter un bien | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Lancer un inventaire | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| Consulter dashboard | ✅ | ✅ | ❌ | ❌ | ✅ | ✅ |
| Gérer utilisateurs | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Exporter données | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ |

---

## 4. Cas d'utilisation détaillés

### CU-01 — S'authentifier
- **Acteur** : Tous
- **Précondition** : Compte créé par l'administrateur
- **Scénario nominal** :
  1. L'utilisateur saisit email + mot de passe
  2. Le système vérifie les identifiants
  3. Le système retourne un token d'accès
  4. L'utilisateur est redirigé vers son tableau de bord selon son rôle
- **Exceptions** : Identifiants invalides → message d'erreur

### CU-02 — Créer un bien
- **Acteur** : Gestionnaire, Admin
- **Précondition** : Authentifié, rôle autorisé
- **Scénario nominal** :
  1. L'utilisateur ouvre le formulaire "Nouveau bien"
  2. Il saisit : libellé, catégorie, date d'acquisition, valeur, durée d'amortissement
  3. Il peut attacher des documents (facture, photo)
  4. Le système génère un code inventaire unique
  5. Le bien est enregistré avec état "neuf"
- **Postcondition** : Bien visible dans la liste, historique créé

### CU-03 — Affecter un bien
- **Acteur** : Gestionnaire
- **Précondition** : Bien existant, site existant, agent existant
- **Scénario nominal** :
  1. L'utilisateur sélectionne un bien
  2. Il choisit un site et un agent bénéficiaire
  3. Il saisit la date de début
  4. Le système vérifie que le bien n'est pas déjà affecté
  5. L'affectation est enregistrée, l'historique mis à jour

### CU-04 — Transférer un bien
- **Acteur** : Gestionnaire
- **Précondition** : Bien actuellement affecté
- **Scénario** : Clôture de l'affectation actuelle (date de fin = jour) + création d'une nouvelle affectation

### CU-05 — Réaliser un inventaire
- **Acteur** : Agent d'inventaire
- **Précondition** : Campagne d'inventaire ouverte pour un site
- **Scénario nominal** :
  1. L'agent ouvre la campagne du site
  2. Le système liste les biens théoriquement présents
  3. L'agent marque chaque bien : présent / absent / endommagé
  4. Il ajoute des observations
  5. Il clôt l'inventaire
- **Postcondition** : Rapport d'écart disponible

### CU-06 — Consulter le tableau de bord
- **Acteur** : Direction, Admin
- **Scénario** : Vue agrégée avec filtres (région, ministère, catégorie, état) affichant : valeur totale, nombre de biens, répartition par catégorie, alertes

### CU-07 — Gérer les utilisateurs
- **Acteur** : Admin uniquement
- **Scénario** : CRUD complet sur les comptes, attribution des rôles, désactivation

---

## 5. Exigences fonctionnelles

### 5.1 Module Patrimoine
- EF-01 : Créer / modifier / supprimer un bien
- EF-02 : Catégoriser les biens (arborescence)
- EF-03 : Attacher des documents (PDF, images, max 10 Mo)
- EF-04 : Calculer la valeur résiduelle (amortissement linéaire)
- EF-05 : Historiser toutes les modifications
- EF-06 : Rechercher / filtrer (libellé, catégorie, état, code)
- EF-07 : Exporter en CSV/PDF

### 5.2 Module Localisation
- EF-08 : Gérer les sites (CRUD)
- EF-09 : Affecter un bien à un site + agent
- EF-10 : Transférer un bien entre sites
- EF-11 : Consulter l'historique des affectations d'un bien

### 5.3 Module Inventaire
- EF-12 : Créer une campagne d'inventaire
- EF-13 : Saisir les lignes d'inventaire
- EF-14 : Clôturer et générer un rapport d'écarts

### 5.4 Module Auth
- EF-15 : Authentification (email + mot de passe)
- EF-16 : Gestion des rôles (RBAC)
- EF-17 : Déconnexion

### 5.5 Module Reporting
- EF-18 : Dashboard avec KPIs (nb biens, valeur totale, répartition)
- EF-19 : Rapports exportables

---

## 6. Exigences non fonctionnelles

| Catégorie | Exigence | Cible |
|-----------|----------|-------|
| **Performance** | Temps de réponse API | < 300 ms (P95) |
| **Performance** | Chargement page | < 2 s |
| **Sécurité** | Authentification | Token Bearer (Sanctum) |
| **Sécurité** | Mots de passe | Bcrypt (cost 12) |
| **Sécurité** | Transmission | HTTPS en prod |
| **Sécurité** | Traçabilité | Audit log des actions critiques |
| **Disponibilité** | Taux de service | ≥ 99% (hors maintenance) |
| **Scalabilité** | Ajout de service | Sans impact sur les autres |
| **Maintenabilité** | Couverture de tests | ≥ 60% sur services critiques |
| **UX** | Responsive | Mobile, tablette, desktop |

---

## 7. Périmètre

### 7.1 Dans le périmètre (MVP — 5 jours)
- Authentification + RBAC
- CRUD Biens + Catégories
- CRUD Sites
- Affectations simples
- Dashboard basique
- API REST documentée
- Docker + PostgreSQL
- Déploiement sur hébergeur gratuit

### 7.2 Hors périmètre (v2+)
- Notifications temps réel
- Signature électronique de documents
- Intégration comptable (SIGFIP, etc.)
- Application mobile native
- OCR sur factures
- Workflow de validation multi-niveaux
- Reporting avancé (BI)
- Gestion des fournisseurs et marchés publics
- QR codes / RFID sur les biens

---

## 8. Contraintes techniques

| # | Contrainte |
|---|------------|
| CT1 | Stack imposée : Laravel 11 + PostgreSQL 16 |
| CT2 | Conteneurisation obligatoire (Docker) |
| CT3 | Architecture microservices |
| CT4 | Conception UML |
| CT5 | Maquettes Figma |
| CT6 | Hébergement gratuit (Render / Railway / Fly.io) |
| CT7 | Délai : 5 jours |
| CT8 | Travail individuel |

---

## 9. Critères de succès du projet

✅ **Technique**
- Les 3 microservices démarrent via `docker compose up`
- Login fonctionnel via la gateway
- CRUD complet sur les biens
- Déploiement accessible publiquement

✅ **Documentation**
- README avec normalisation BDD (1NF→3NF)
- Guide utilisateur illustré
- Diagrammes UML produits
- Maquettes Figma disponibles

✅ **Soutenance**
- Architecture défendable
- Démonstration live
- Réponses aux questions techniques

---

## 10. Conclusion

Le projet GPE répond à un besoin réel de centralisation et de traçabilité du patrimoine de l'État. La solution microservices proposée est adaptée à la contrainte de temps (MVP en 5 jours), tout en posant les bases d'une plateforme scalable et maintenable. Le choix de **3 microservices** (auth, patrimoine, localisation) plutôt que 8+ services permet de **démontrer la maîtrise de l'architecture** sans sacrifier la qualité ni les délais.

**Prochaine étape** : Conception UML (cas d'utilisation, classes, séquence) puis maquettes Figma.