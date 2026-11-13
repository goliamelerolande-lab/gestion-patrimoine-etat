# Maquettes et charte graphique GPE

**Statut :** planche vectorielle prête à importer dans Figma ; le lien vers un fichier Figma partagé reste à renseigner après import dans un compte Figma.

## Import dans Figma

1. Ouvrir Figma et créer un fichier de conception.
2. Faire glisser [`gpe-maquettes.svg`](gpe-maquettes.svg) dans la page, ou choisir **File → Place image**.
3. Détacher les groupes/importer les éléments SVG si des composants séparés sont nécessaires.
4. Créer des composants réutilisables à partir du bouton primaire, des cartes KPI, des champs et des badges.
5. Compléter les variantes tablette/mobile puis ajouter l’URL du fichier Figma partagé dans cette page.

La planche représente trois écrans MVP : tableau de bord, gestion des comptes et connexion administrateur. Elle sert de base de discussion et de reproduction dans Figma, pas de preuve d’un fichier Figma déjà créé.

Les noms, adresses email et valeurs visibles dans la planche sont des données fictives destinées à l’illustration.

## Principes graphiques

| Élément | Valeur de conception |
|---|---|
| Bleu nuit / navigation | `#14263F` |
| Vert d’action / primaire | `#176B5B` |
| Accent / attention | `#D97706` |
| Fond général | `#F3F6F5` |
| Carte | `#FFFFFF` |
| Texte principal | `#1E293B` |
| Typographie | Inter, puis police système sans-serif |
| Formes | Cartes arrondies, bordures discrètes, ombres courtes |
| Accessibilité | Contraste lisible, libellés persistants, focus clavier visible, actions nommées |

## Écrans et composants

### Tableau de bord

- Navigation latérale : tableau de bord, biens, sites et localisations, rapports, utilisateurs, notifications.
- En-tête de page : titre, date et action de navigation.
- Cartes KPI : nombre de biens, valeur cumulée, nombre de biens en usage.
- Tableau : derniers biens enregistrés et actions clairement identifiées.
- Résumé par état ; le rapport complet est accessible depuis le menu.

### Gestion des utilisateurs

- En-tête avec identité de l’administrateur connecté, action « Nouvel utilisateur » et déconnexion.
- Tableau avec nom, email, rôle, statut actif/inactif et commandes.
- Formulaire modal avec nom, email, rôle, mot de passe et confirmation.
- En modification, laisser les champs du mot de passe vides conserve le mot de passe courant.
- Désactivation avec confirmation ; le statut reste visible et le compte peut être réactivé.

### Connexion administrateur

- Écran responsive en deux panneaux : identité institutionnelle et formulaire.
- Saisie email et mot de passe, erreurs présentées sans divulguer si un email existe.
- Le jeton API n’est pas affiché dans le navigateur ; la session est conservée côté serveur.

### Composants à construire comme composants Figma

`Button/Primary`, `Button/Secondary`, `Input/Default`, `Input/Error`, `Card/KPI`, `Badge/Role`, `Badge/Status`, `Table/Row`, `Modal/User`, `Alert/Inline`, `Sidebar/Item`.

## Lien vers le fichier Figma

À compléter après création/import dans Figma : **[Fichier de conception GPE — lien à renseigner]**.
