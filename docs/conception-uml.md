# MODÉLISATION & CONCEPTION UML DU PROJET
## GEST-PATRIMOINE — Architecture Logicielle & Modèles Conceptuels

Ce document rassemble l'ensemble des diagrammes UML exigés pour la soutenance professionnelle et l'évaluation de la solution.

---

## 1. Diagramme de Cas d'Utilisation (Use Case Diagram)

Ce diagramme illustre les interactions des différents acteurs avec le système GEST-PATRIMOINE.

```mermaid
flowchart LR
    subgraph Acteurs
        A["👤 Agent Public"]
        G["👔 Gestionnaire Patrimoine"]
        SA["🛡️ Super-Administrateur"]
        AUD["📊 Auditeur / IGF"]
    end

    subgraph "Système GEST-PATRIMOINE"
        UC1["S'authentifier (Login / JWT)"]
        UC2["Consulter ses biens affectés"]
        UC3["Signaler une panne / incident"]
        UC4["Enregistrer un nouveau bien (GPE-XXXX)"]
        UC5["Affecter / Réaffecter un bien"]
        UC6["Gérer les maintenances & réparations"]
        UC7["Générer rapports & bilans financiers FCFA"]
        UC8["Gérer utilisateurs et ministères"]
        UC9["Consulter l'historique d'audit"]
    end

    A --> UC1
    A --> UC2
    A --> UC3

    G --> UC1
    G --> UC4
    G --> UC5
    G --> UC6
    G --> UC7

    SA --> UC1
    SA --> UC4
    SA --> UC5
    SA --> UC6
    SA --> UC7
    SA --> UC8
    SA --> UC9

    AUD --> UC1
    AUD --> UC7
    AUD --> UC9
```

---

## 2. Diagramme de Classes Métier (Class Diagram)

Ce diagramme représente la structure relationnelle des données du domaine du patrimoine.

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string role
        +string matricule
        +login()
        +logout()
    }

    class Ministere {
        +int id
        +string code
        +string nom
        +string sigle
    }

    class Categorie {
        +int id
        +string code
        +string libelle
        +string duree_amortissement
    }

    class Bien {
        +int id
        +string code_inventaire
        +string libelle
        +date date_acquisition
        +float valeur_acquisition_fcfa
        +string etat
        +string localisation
        +int categorie_id
        +int ministere_id
        +calculerAmortissement()
        +changerStatut()
    }

    class Affectation {
        +int id
        +int bien_id
        +int user_id
        +date date_debut
        +date date_fin
        +string motif
        +string statut
        +cloturer()
    }

    class Maintenance {
        +int id
        +int bien_id
        +string description_panne
        +string priorite
        +string statut
        +float cout_reparation_fcfa
        +date date_signalement
        +date date_resolution
        +assignerTechnicien()
        +resoudre()
    }

    Ministere "1" -- "0..*" User : emploie
    Ministere "1" -- "0..*" Bien : possède
    Categorie "1" -- "0..*" Bien : classe
    Bien "1" -- "0..*" Affectation : fait l'objet de
    User "1" -- "0..*" Affectation : est affecté à
    Bien "1" -- "0..*" Maintenance : subit
```

---

## 3. Diagramme de Séquence : Enregistrement et Affectation d'un Bien

Ce diagramme décrit la chaîne des échanges asynchrones entre l'utilisateur, l'API Gateway et les microservices.

```mermaid
sequenceDiagram
    autonumber
    actor G as Gestionnaire
    participant GW as API Gateway (Frontend)
    participant PS as Patrimoine-Service (8002)
    participant AS as Affectation-Service (8003)
    participant DB as PostgreSQL

    G->>GW: 1. Remplir formulaire nouveau bien
    GW->>PS: 2. POST /api/biens (Libellé, Catégorie, Valeur FCFA)
    PS->>PS: 3. Génération du Code National GPE-XXXXXX
    PS->>DB: 4. INSERT INTO biens
    DB-->>PS: 5. Bien enregistré (ID: 14)
    PS-->>GW: 6. 201 Created + DTO Bien

    G->>GW: 7. Assigner à l'agent (ID: 14, Agent: Amadou)
    GW->>AS: 8. POST /api/affectations (bien_id: 14, user_id: 5)
    AS->>DB: 9. INSERT INTO affectations
    AS->>PS: 10. PUT /api/biens/14/statut (statut: "Affecté")
    PS->>DB: 11. UPDATE biens SET statut = 'Affecté'
    AS-->>GW: 12. 200 OK (Affectation validée)
    GW-->>G: 13. Notification de succès & Décharge générée
```

---

## 4. Diagramme d'Activité : Cycle de Vie d'un Bien de l'État

```mermaid
flowchart TD
    Start(["Début : Réception du Bien"]) --> Acq["Enregistrement de la Facture & Bon de Livraison"]
    Acq --> GenCode["Génération Code Inventaire GPE-XXXXXX"]
    GenCode --> Stock["Mise en Stock / Magasin Central"]
    Stock --> DemandeAffectation{"Demande d'Affectation reçue ?"}
    
    DemandeAffectation -- Oui --> CheckDispo["Vérification Disponibilité"]
    CheckDispo --> EditDecharge["Édition Fiche de Décharge"]
    EditDecharge --> EnService["Bien En Service Actif"]
    
    DemandeAffectation -- Non --> Stock
    
    EnService --> Usage{"Événement en cours d'usage"}
    
    Usage -- "Fonctionnement Normal" --> Amort["Calcul Annuel de l'Amortissement"]
    Amort --> EnService
    
    Usage -- "Panne / Dysfonctionnement" --> SignalPanne["Signalement Ticket Maintenance"]
    SignalPanne --> Diagnostic{"Diagnostic Réparable ?"}
    Diagnostic -- Oui --> Rep["Intervention & Règlement Facture FCFA"] --> EnService
    Diagnostic -- Non --> Reforme["Proposition de Réforme / Rebut"]
    
    Usage -- "Mutation / Départ Agent" --> Restit["Restitution au Magasin & PV d'État"] --> Stock
    
    Reforme --> SortieInventaire["Commission de Réforme & Sortie d'Inventaire"]
    SortieInventaire --> End(["Fin : Sortie Définitive du Patrimoine"])
```

---

## 5. Diagramme de Composants : Architecture Microservices

```mermaid
graph TD
    Client["Client Web (Navigateur / Tablette)"]
    
    subgraph "Couche Entrée & Présentation"
        Gateway["API Gateway & Frontend Dashboard (Port 8080)<br/>Routage, Vues Blade, Tableaux Chart.js"]
    end
    
    subgraph "Couche Microservices Métier (Laravel Core)"
        AuthSvc["Auth Service (Port 8001)<br/>JWT, Rôles, Ministères, Utilisateurs"]
        PatrimoineSvc["Patrimoine Service (Port 8002)<br/>Biens, Catégories, Valeurs FCFA, Inventaires"]
        AffectationSvc["Affectation Service (Port 8003)<br/>Mouvements, Décharges, Pannes & Maintenances"]
    end
    
    subgraph "Couche Persistance (PostgreSQL Conteneurisé)"
        AuthDB[("auth_db")]
        PatrimoineDB[("patrimoine_db")]
        AffectationDB[("affectation_db")]
    end
    
    Client -->|HTTP / REST| Gateway
    Gateway -->|Internal HTTP| AuthSvc
    Gateway -->|Internal HTTP| PatrimoineSvc
    Gateway -->|Internal HTTP| AffectationSvc
    
    AffectationSvc -.->|REST sync| PatrimoineSvc
    
    AuthSvc -->|TCP 5432| AuthDB
    PatrimoineSvc -->|TCP 5432| PatrimoineDB
    AffectationSvc -->|TCP 5432| AffectationDB
```

---

## 6. Diagramme de Déploiement : Conteneurisation Docker

```mermaid
graph TB
    subgraph "Hôte Docker (WSL2 / Linux Engine)"
        subgraph "Réseau Interne Isolé : gest_patrimoine_net"
            subgraph "Conteneur : app_gateway"
                GW_App["Laravel Gateway / Dashboard<br/>Exposé sur : 0.0.0.0:8080"]
            end
            
            subgraph "Conteneur : auth_service"
                Auth_App["PHP 8.2 FPM / Artisan<br/>Port interne : 8001"]
            end
            
            subgraph "Conteneur : patrimoine_service"
                Pat_App["PHP 8.2 FPM / Artisan<br/>Port interne : 8002"]
            end
            
            subgraph "Conteneur : affectation_service"
                Aff_App["PHP 8.2 FPM / Artisan<br/>Port interne : 8003"]
            end
            
            subgraph "Conteneur : postgres_db"
                PG_Engine["PostgreSQL 16 Server<br/>Port interne : 5432"]
                PG_Vol[("Volume Docker : postgres_data")]
            end
        end
    end

    Navigateur["Poste de Travail Utilisateur"] -->|http://localhost:8080| GW_App
    GW_App --> Auth_App
    GW_App --> Pat_App
    GW_App --> Aff_App
    Auth_App --> PG_Engine
    Pat_App --> PG_Engine
    Aff_App --> PG_Engine
    PG_Engine --- PG_Vol
```
