# SPÉCIFICATIONS FIGMA & DESIGN SYSTEM INSTITUTIONNEL
## GEST-PATRIMOINE — Guide d'Implémentation UI/UX

---

## 1. Principes Directeurs & Identité Visuelle

L'interface de **GEST-PATRIMOINE** respecte les codes esthétiques et ergonomiques des systèmes d'information gouvernementaux modernes :
- **Autorité & Sérénité** : Tons bleus républicains et typographies nettes.
- **Transparence & Lisibilité** : Présentation claire des chiffres financiers en FCFA et des codes d'inventaire.
- **Ergonomie Efficace** : Actions rapides, statuts identifiables en un coup d'œil, zéro encombrement visuel.

---

## 2. Palette Chromatique (Design Tokens)

| Rôle | Nom du Token | Valeur HEX | Utilisation |
| :--- | :--- | :--- | :--- |
| **Primaire** | `brand-navy` | `#0D3B66` | Barre latérale, en-têtes officiels, boutons d'action prioritaires |
| **Secondaire** | `brand-blue-accent` | `#1D70B8` | Liens actifs, sélections, icônes principales |
| **Fond & Surfaces** | `bg-app` | `#F4F6F9` | Arrière-plan global de l'application |
| **Carte / Surface** | `surface-card` | `#FFFFFF` | Conteneurs des tableaux, cartes métriques, modales |
| **Texte Principal** | `text-primary` | `#1E293B` | Titres, données financières, codes d'inventaire |
| **Texte Sourd** | `text-muted` | `#64748B` | Labels, métadonnées, sous-titres |
| **Validation / Neuf** | `status-success` | `#15803D` | Statut "Neuf", "Disponible", validation d'affectation |
| **Alerte / Panne** | `status-danger` | `#B91C1C` | Statut "En panne", niveau de panne critique |
| **Avertissement / Moyen** | `status-warning` | `#D97706` | Statut "Passable", maintenance en attente |
| **Réforme / Neutre** | `status-neutral` | `#6B7280` | Statut "Réformé / Rebut", archives |

---

## 3. Typographie

- **Police Principale** : `Plus Jakarta Sans` ou `Inter` (sans-serif moderne, excellente lisibilité sur écran).
- **Échelle Typographique** :
  - **H1 (Titres de pages)** : 28px, Semibold (600), line-height 1.25.
  - **H2 (Sous-sections, Cartes)** : 20px, Semibold (600).
  - **KPI Chiffres (Valeurs FCFA)** : 32px, Bold (700).
  - **Corps de texte (Body)** : 14px / 15px, Regular (400), line-height 1.5.
  - **Badges de statut** : 12px, Bold (700), text-transform uppercase, tracking 0.5px.
  - **Code d'inventaire (GPE-XXXXXX)** : 14px, Bold (700), Monospace / High contrast.

---

## 4. Structure des Écrans Maquettes (Wireframes & UI)

### Écran 1 : Authentification (`/login`)
- **Structure** : Carte centrée sur fond bleu institutionnel avec armoiries / logo de l'État.
- **Composants** :
  - Champ identifiant email institutionnel.
  - Champ mot de passe avec bascule afficher/masquer.
  - Bouton "Se connecter à la plateforme".
  - Mention légale de sécurité informatique et support technique.

### Écran 2 : Tableau de Bord Général (`/dashboard`)
- **Structure** : Sidebar de navigation repliable à gauche, barre supérieure avec profil utilisateur, zone de contenu principale.
- **Composants** :
  - 4 cartes KPI en haut : Total Biens, Valeur Globale FCFA, En service, Alertes pannes.
  - Graphique circulaire : Répartition par état (Neuf, Bon état, Passable, En panne).
  - Tableau des 5 derniers biens enregistrés avec bouton rapide d'affectation.

### Écran 3 : Registre des Biens (`/biens`)
- **Structure** : Barre d'outils avec recherche en temps réel, filtres par ministère et bouton modal `+ Nouveau Bien`.
- **Tableau de données** :
  - Colonnes : Code Inventaire, Libellé, Catégorie, Date d'acquisition, Valeur (FCFA), État, Actions (Voir, Affecter, Panne, Modifier).
  - Pagination et tri par colonne.

### Écran 4 : Fiche Détail & Décharge d'Affectation (`/biens/{id}`)
- **Structure** : Deux colonnes (Informations signalétiques à gauche, Historique des affectations et maintenances à droite).
- **Actions contextuelles** : Imprimer la fiche signalétique, Générer QR Code, Changer d'affectation.

### Écran 5 : Rapports Globaux & Exportations (`/rapports`)
- **Structure** : Reproduction fidèle du format certifié vu dans le document officiel `Rapports - GPE.pdf`.
- **Composants** :
  - En-tête officiel horodaté : `Rapports Globaux - Analyse détaillée et exportation des données`.
  - Bloc résumé : Total des biens (1), Valeur cumulée (120 000 FCFA), Date du rapport.
  - Bloc "Répartition globale par état".
  - Tableau des 20 biens les plus récents avec formatage monétaire clair.
  - Bouton d'exportation PDF et impression directe.
