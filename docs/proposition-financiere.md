# Proposition financière prévisionnelle — GPE

**Version :** 1.0 · **Date :** 13 novembre 2026 · **Devise :** FCFA (XOF) · **Statut :** estimation pédagogique, à valider par le commanditaire

## 1. Hypothèses

- Le brief prévoit environ **40 heures** de travail individuel du 10 au 15 novembre 2026.
- Une journée de travail est estimée à 8 heures, soit 5 jours ouvrés.
- Le taux journalier retenu pour construire cette proposition est une **hypothèse de chiffrage** de 60 000 FCFA/jour (7 500 FCFA/heure). Ce taux n’est pas une offre commerciale vérifiée ni un tarif officiel.
- L’estimation couvre un MVP de démonstration, pas un système d’information prêt pour une exploitation nationale.
- Les taxes éventuelles, achats et coûts de service tiers ne sont pas inclus.

## 2. Estimation de la réalisation

| Phase | Travaux | Heures | Jours | Montant estimatif |
|---|---|---:|---:|---:|
| Analyse | Besoin, acteurs, périmètre et critères | 4 h | 0,50 | 30 000 FCFA |
| Conception | Modèle UML et maquettes / design system | 6 h | 0,75 | 45 000 FCFA |
| Infrastructure | Docker, gateway, PostgreSQL et schémas | 6 h | 0,75 | 45 000 FCFA |
| Services métier | Authentification, biens, sites et notifications | 12 h | 1,50 | 90 000 FCFA |
| Interface | Tableau de bord, formulaires et écrans métier | 8 h | 1,00 | 60 000 FCFA |
| Recette et livrables | Vérifications, README, fiche technique et guide | 4 h | 0,50 | 30 000 FCFA |
| **Total** |  | **40 h** | **5,00** | **300 000 FCFA** |

Ce chiffrage est cohérent avec le temps indicatif du brief ; il ne prétend pas valoriser l’effort réel d’une version de production, qui nécessite un périmètre, des tests et des exigences de sécurité supplémentaires.

## 3. Coûts d’hébergement

| Poste | Démonstration | Exploitation durable |
|---|---|---|
| Application web et API | 0 FCFA possible sur des offres gratuites compatibles | À chiffrer selon ressources, trafic et nombre de services |
| PostgreSQL | Une offre gratuite peut convenir à une démonstration temporaire | Prévoir une base payante avec sauvegardes et rétention adaptées |
| Nom de domaine | Sous-domaine fourni par l’hébergeur | Domaine personnalisé en supplément |
| TLS/HTTPS | Généralement inclus par la plateforme d’hébergement | À confirmer selon le fournisseur retenu |
| Stockage de fichiers | Non utilisé dans ce MVP | Stockage objet persistant à chiffrer si ajout de pièces jointes |

À titre d’exemple, l’offre gratuite Render documentée actuellement met les services web en veille après 15 minutes sans requête, avec un redémarrage à froid pouvant prendre environ une minute. Les bases PostgreSQL gratuites sont limitées à 1 Go, expirent 30 jours après leur création et ne bénéficient pas de sauvegardes. Le « coût hébergement : 0 FCFA » ne convient donc qu’à une présentation temporaire, pas à la conservation durable de données patrimoniales. Consulter les [limites Render Free](https://render.com/docs/free) avant de choisir un hébergeur ; ces conditions peuvent changer.

Le `docker-compose.yml` actuel est conçu pour le développement local. Le déploiement sur une plateforme gratuite demandera un pipeline et une configuration distincts pour les trois services, la gateway et PostgreSQL ; un fichier Compose local n’est pas à lui seul une preuve de déploiement.

## 4. Livrables prévus

- Code source versionné sur GitHub avec README et consignes d’installation.
- Application de démonstration hébergée si une plateforme compatible est sélectionnée.
- Analyse du besoin, fiche technique et guide utilisateur.
- Diagrammes UML PlantUML.
- Planche vectorielle importable dans Figma ; URL de fichier Figma à ajouter après création et partage effectifs.

## 5. Conditions indicatives

- 50 % à la validation du périmètre et des maquettes ; 50 % à la livraison, si la proposition est utilisée comme devis.
- Une courte période de correction des défauts bloquants est à convenir avant engagement.
- Les évolutions de périmètre (inventaire, audit, pièces jointes, RBAC complet, haute disponibilité, migration de données) font l’objet d’une estimation distincte.

## Sources de coûts d’hébergement

- [Render — Deploy for Free : limites des web services et PostgreSQL gratuits](https://render.com/docs/free) (consulté le 13 novembre 2026).
- [Render — Pricing](https://render.com/pricing) (tarification fournisseur, à reconsulter au moment du déploiement).
