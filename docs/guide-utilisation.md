# GUIDE D'UTILISATION COMPLET DE LA SOLUTION
## GEST-PATRIMOINE — Manuel à l'usage des Gestionnaires & Administrateurs

---

## 1. Introduction & Prérequis
Le présent guide accompagne les agents publics et administrateurs dans l'utilisation quotidienne de la plateforme **GEST-PATRIMOINE**.

### Prérequis d'Accès
- Un navigateur web moderne (Google Chrome, Mozilla Firefox, Microsoft Edge, Safari).
- Une connexion au réseau administratif ou à Internet.
- Les identifiants transmis par l'administrateur système.

---

## 2. Authentification & Connexion

1. Accédez à l'adresse de la plateforme (ex: `http://localhost:8080` ou URL de production).
2. Saisissez votre adresse email institutionnelle (ex: `admin@patrimoine.gouv` ou `gestionnaire@patrimoine.gouv`).
3. Saisissez votre mot de passe sécurisé.
4. Cliquez sur **« Se Connecter »**.
5. Vous êtes automatiquement dirigé vers le Tableau de Bord correspondant à vos privilèges (Super-Admin, Gestionnaire ou Agent).

---

## 3. Le Tableau de Bord Général (Dashboard)

Le tableau de bord offre une vision synthétique en temps réel :
- **Total des Biens** : Nombre total de matériels recensés dans la base.
- **Valeur Cumulée en FCFA** : Somme totale des valeurs d'acquisition des biens de l'État.
- **Répartition par État** : Taux de matériels *Neufs*, en *Bon état*, *En panne* ou *Réformés*.
- **Alertes de Maintenance** : Nombre de pannes en attente d'intervention.
- **Derniers Biens Enregistrés** : Liste des 5 acquisitions les plus récentes.

---

## 4. Gestion du Registre des Biens

### 4.1 Enregistrer un Nouveau Bien
1. Dans le menu latéral, cliquez sur **« Registre des Biens »** puis sur le bouton **« + Nouveau Bien »**.
2. Remplissez le formulaire obligatoire :
   - **Libellé du bien** : ex: *Toyota Hilux Double Cabine 4x4* ou *Ordinateur Portable Dell Latitude*.
   - **Catégorie** : Choisissez parmi *Véhicules*, *Immobilier / Bâtiments*, *Matériel Informatique*, *Mobilier de Bureau*.
   - **Date d'acquisition** : Date d'achat figurant sur le bon de commande / facture.
   - **Valeur d'acquisition (FCFA)** : ex: *18 500 000 FCFA*.
   - **État d'origine** : Généralement *Neuf*.
   - **Ministère / Affectation initiale** : ex: *Ministère de l'Économie et des Finances*.
   - **Localisation** : Bâtiment A, Bureau 204.
3. Cliquez sur **« Enregistrer le bien »**.
4. Le système génère automatiquement un **Code d'Inventaire National** unique (ex: `GPE-000014`) et enregistre le bien de manière sécurisée.

### 4.2 Rechercher et Filtrer
- Utilisez la barre de recherche textuelle pour filtrer par mot-clé, code d'inventaire ou localisation.
- Filtrez instantanément par état (*Tous*, *Neuf*, *Bon état*, *En panne*, *Réformé*).

---

## 5. Gestion des Affectations & Mouvements

1. Rendez-vous dans la rubrique **« Affectations »**.
2. Cliquez sur **« Nouvelle Affectation »**.
3. Sélectionnez le bien concerné (dans la liste des biens disponibles).
4. Choisissez l'agent détenteur ou le service bénéficiaire.
5. Indiquez la date de mise à disposition et le motif (affectation permanente, mission, prêt).
6. Validez. Le système met à jour automatiquement le statut du bien et consigne la décharge dans l'historique inviolable.

---

## 6. Déclaration et Suivi de Maintenance

1. Accédez à l'onglet **« Maintenance »**.
2. Cliquez sur **« Signaler une Panne »**.
3. Choisissez le matériel présentant une anomalie.
4. Précisez la description du dysfonctionnement (ex: *Courroie d'alternateur rompue* ou *Écran noir*).
5. Définissez le niveau d'urgence (*Basse*, *Normale*, *Critique*).
6. Le gestionnaire technique peut ensuite mettre à jour l'intervention :
   - Assigner un prestataire ou technicien.
   - Saisir le coût réel de réparation en FCFA.
   - Clôturer le ticket de maintenance pour remettre le bien en statut *Bon état*.

---

## 7. Édition et Exportation des Rapports Globaux

1. Cliquez sur **« Rapports Globaux »** dans le menu de navigation.
2. Le système synthétise la vue certifiée du patrimoine conforme au modèle officiel :
   - Nombre de biens et valeur totale en FCFA.
   - Répartition détaillée par état d'usage.
   - Tableau des 20 biens les plus récents avec code inventaire, libellé, date et valeur.
3. Cliquez sur le bouton **« Imprimer / Exporter PDF »** pour éditer le document officiel à transmettre à la hiérarchie ou aux corps d'inspection.
