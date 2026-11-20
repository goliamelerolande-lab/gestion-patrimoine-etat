# CAHIER DES CHARGES FONCTIONNEL & TECHNIQUE
## PROJET : GEST-PATRIMOINE (Gestion du Patrimoine de l'État)

---

### Informations Générales
- **Projet** : Plateforme Numérique Intégrée de Gestion du Patrimoine de l'État (GPE)
- **Auteure / Développeuse Full Stack** : Amele Rolande GOLI
- **Destinataires** : Ministère de l'Économie et des Finances / Direction Générale du Patrimoine de l'État
- **Version** : 1.0.0 (Production & Soutenance)
- **Date** : Novembre 2026

---

## 1. Contexte & Problématique

### 1.1 Contexte Institutionnel
L'État dispose d'un patrimoine mobilier et immobilier colossal réparti à travers l'ensemble des ministères, directions générales, préfectures et représentations régionales. Ce patrimoine comprend :
- Les **immeubles et terrains** administratifs.
- Le **parc automobile et engins roulants** (véhicules de fonction, de service, utilitaires).
- Les **équipements informatiques et bureautiques** (serveurs, ordinateurs, imprimantes).
- Le **mobilier de bureau et matériels spécialisés**.

### 1.2 Problématique constatée
Dans la majorité des services publics, ce patrimoine est géré de façon fragmentée :
- Utilisation disparate de classeurs physiques et de tableurs Excel non synchronisés.
- Perte de traçabilité lors des mutations d'agents ou des réorganisations ministérielles.
- Difficulté à localiser un équipement et méconnaissance de son détenteur effectif.
- Absence d'anticipation des cycles de maintenance et des coûts d'entretien.
- Incapacité à produire en temps réel un bilan comptable certifié de la valeur globale du patrimoine étatique en Francs CFA (FCFA).

### 1.3 Objectifs de la solution GEST-PATRIMOINE
1. **Centraliser et unifier** le recensement de l'ensemble des biens publics dans un référentiel unique.
2. **Attribuer un identifiant unique national normalisé** à chaque bien (ex: `GPE-000013`).
3. **Assurer la traçabilité intégrale** de chaque mouvement (affectation, transfert, restitution, mise au rebut).
4. **Piloter la maintenance et les garanties** pour optimiser les dépenses de réparation de l'État.
5. **Produire des tableaux de bord et rapports d'inventaire instantanés** (statistiques globales, valorisation financière, exports PDF/Excel).

---

## 2. Périmètre & Rôles Utilisateurs

### 2.1 Matrice des Rôles (RBAC)

| Rôle | Description | Droits & Accès |
| :--- | :--- | :--- |
| **Super-Administrateur** | Direction Générale du Patrimoine / DSI | Gestion totale, configuration des ministères, gestion des comptes utilisateurs, audit trail, accès multi-services |
| **Gestionnaire de Patrimoine** | Responsable logistique ministériel ou régional | Enregistrement des biens, affectations, transferts, gestion des pannes et maintenances, inventaires |
| **Agent / Détenteur** | Fonctionnaire ou employé de l'État | Consultation des biens qui lui sont personnellement affectés, signalement d'une panne ou anomalie |
| **Auditeur / Contrôleur d'État** | Inspection Générale des Finances | Accès en lecture seule, consultation des rapports financiers et historiques de mouvements |

---

## 3. Spécifications Fonctionnelles Détaillées

### Module 1 : Authentification & Sécurité (Auth Service)
- Connexion sécurisée par email institutionnel et mot de passe chiffré (Bcrypt / Argon2).
- Authentification par jetons API (Sanctum / JWT) inter-microservices.
- Déconnexion et révocation instantanée des sessions.
- Gestion hiérarchique des ministères, directions régionales et services rattachés.

### Module 2 : Gestion du Registre des Biens (Patrimoine Service)
- Fiche signalétique complète par bien :
  - **Code d'inventaire unique** auto-généré au format `GPE-XXXXXX`.
  - **Libellé & désignation précise**.
  - **Catégorie** (Bâtiment, Véhicule, Informatique, Mobilier).
  - **Date d'acquisition et fournisseur**.
  - **Valeur d'acquisition (en FCFA)** et valeur nette comptable après amortissement.
  - **État d'usure** : *Neuf*, *Bon état*, *Passable*, *En panne*, *Hors d'usage / Réformé*.
  - **Localisation physique** (Bâtiment, Étage, Numéro de bureau).
- Filtres multi-critères et moteur de recherche instantané.

### Module 3 : Affectations & Mouvements (Affectation Service)
- Affectation d'un bien à un agent détenteur ou à un service administratif.
- Enregistrement de l'historique chronologique inviolable :
  - Date de prise en charge et date de restitution.
  - Motif du mouvement (Nouvelle affectation, Mutation, Prêt temporaire).
  - Fiche de décharge signée électroniquement.

### Module 4 : Maintenance & Réparations (Maintenance Service)
- Signalement d'incident ou panne avec niveau de gravité (*Faible*, *Moyen*, *Critique*).
- Suivi du cycle de vie de réparation : *Signalé* ➔ *En cours d'intervention* ➔ *Réparé* ➔ *Irréparable*.
- Saisie des coûts de réparation en FCFA et identification du prestataire technique.

### Module 5 : Rapports Globaux & Exportations (Reporting Service)
- Tableau de bord en temps réel avec indicateurs clés :
  - Nombre total de biens inventoriés.
  - Valeur cumulée du parc en FCFA.
  - Répartition par catégorie et par état de conservation.
  - Alertes pour biens non inventoriés depuis plus de 12 mois.
- Génération et impression directe de rapports conformes aux normes administratives (ex: format du document officiel `Rapports - GPE.pdf`).

---

## 4. Exigences Non-Fonctionnelles

1. **Architecture** : Microservices indépendants avec bases de données dédiées PostgreSQL pour garantir la scalabilité et l'autonomie.
2. **Conteneurisation** : Déploiement reproductible en une seule commande via `docker compose up -d`.
3. **Sécurité** : Protection CSRF, injections SQL via Eloquent ORM, validation stricte des formulaires, politique CORS sécurisée.
4. **Ergonomie** : Interface web responsive, lisible sur mobile et desktop, conforme aux codes visuels institutionnels.
5. **Performance** : Temps de réponse inférieur à 200ms sur les requêtes d'inventaire grâce à l'indexation PostgreSQL.
