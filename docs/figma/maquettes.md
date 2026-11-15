# 🎨 Maquettes & Charte Graphique — GPE

**Outil** : Figma (Spécifications prêtes pour intégration)
**Lien Figma** : [Insérer le lien vers le projet Figma ici]

## 1. Charte Graphique
- **Couleur primaire** : `#1E40AF` (Bleu institutionnel)
- **Couleur secondaire** : `#D97706` (Or / Accent)
- **Fond** : `#F3F4F6` (Gris clair)
- **Typographie** : Inter (Sans-serif)
- **Style** : Sobre, institutionnel, responsive (Desktop & Mobile).

## 2. Écrans principaux (Wireframes textuels)

### A. Écran de Connexion (Login)
- **Desktop** : Carte centrée sur fond gris. Logo en haut, champs "Email" et "Mot de passe", bouton primaire bleu "Se connecter".
- **Mobile** : Carte pleine largeur avec marges de 16px.

### B. Tableau de bord (Dashboard)
- **Layout** : Sidebar à gauche (menu : Dashboard, Biens, Sites, Inventaires, Utilisateurs).
- **Contenu** :
  - 3 cartes KPI en haut : "Total Biens", "Valeur Totale", "Alertes".
  - Un graphique en donut (Répartition par catégorie).
  - Un tableau des derniers biens ajoutés.

### C. Liste des Biens (CRUD)
- **Barre de recherche** et bouton primaire "+ Nouveau Bien" en haut à droite.
- **Tableau** avec colonnes : Code, Libellé, Catégorie, Etat, Actions (Voir, Modifier, Supprimer).
- **Pagination** en bas.
- **Mobile** : Le tableau se transforme en liste de cartes (cards).

### D. Formulaire de Création/Modification
- Champs : Code Inventaire (généré auto), Libellé, Catégorie (dropdown), Date acquisition, Valeur, Etat.
- Boutons : "Annuler" (secondaire) et "Enregistrer" (primaire).