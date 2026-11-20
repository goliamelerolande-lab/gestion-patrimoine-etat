<?php

namespace AffectationService\Controllers;

use AffectationService\Database;
use PDO;

class AffectationController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAffectations(array $params = []): array
    {
        $sql = "SELECT * FROM affectations WHERE 1=1";
        $binds = [];

        if (!empty($params['statut'])) {
            $sql .= " AND statut = :statut";
            $binds[':statut'] = trim($params['statut']);
        }

        if (!empty($params['q'])) {
            $sql .= " AND (
                LOWER(beneficiaire_nom) LIKE :q1 
                OR LOWER(code_inventaire) LIKE :q2 
                OR LOWER(service_nom) LIKE :q3 
                OR LOWER(beneficiaire_matricule) LIKE :q4 
                OR LOWER(motif) LIKE :q5 
                OR LOWER(statut) LIKE :q6
            )";
            $term = '%' . strtolower(trim($params['q'])) . '%';
            $binds[':q1'] = $term;
            $binds[':q2'] = $term;
            $binds[':q3'] = $term;
            $binds[':q4'] = $term;
            $binds[':q5'] = $term;
            $binds[':q6'] = $term;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);
        return ['status' => 200, 'data' => $stmt->fetchAll()];
    }

    public function createAffectation(array $data): array
    {
        $bien_id = (int)($data['bien_id'] ?? 0);
        $code_inventaire = trim($data['code_inventaire'] ?? '');
        $beneficiaire_nom = trim($data['beneficiaire_nom'] ?? '');
        $beneficiaire_matricule = trim($data['beneficiaire_matricule'] ?? 'MAT-000');
        $service_nom = trim($data['service_nom'] ?? 'Services Généraux');
        $date_affectation = $data['date_affectation'] ?? date('Y-m-d');
        $motif = trim($data['motif'] ?? "Affectation administrative d'usage");
        $remarques = trim($data['remarques'] ?? '');

        if ($bien_id <= 0 || empty($code_inventaire) || empty($beneficiaire_nom)) {
            return ['status' => 400, 'data' => ['error' => 'Identifiant du bien, code d\'inventaire et nom du bénéficiaire requis.']];
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO affectations (
                    bien_id, code_inventaire, beneficiaire_nom, beneficiaire_matricule,
                    service_nom, date_affectation, motif, statut, remarques
                ) VALUES (
                    :bien_id, :code, :nom, :mat, :service, :date_aff, :motif, 'En cours', :remarques
                )
            ");
            $stmt->execute([
                ':bien_id' => $bien_id,
                ':code' => $code_inventaire,
                ':nom' => $beneficiaire_nom,
                ':mat' => $beneficiaire_matricule,
                ':service' => $service_nom,
                ':date_aff' => $date_affectation,
                ':motif' => $motif,
                ':remarques' => $remarques
            ]);

            return [
                'status' => 201,
                'data' => [
                    'message' => 'Affectation enregistrée avec succès.',
                    'id' => $this->db->lastInsertId()
                ]
            ];
        } catch (\Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Erreur affectation : ' . $e->getMessage()]];
        }
    }

    public function restituerAffectation(int $id): array
    {
        $date_restitution = date('Y-m-d');
        $stmt = $this->db->prepare("
            UPDATE affectations
            SET statut = 'Restitué', date_restitution = :date_restitution, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        $stmt->execute([':id' => $id, ':date_restitution' => $date_restitution]);

        return ['status' => 200, 'data' => ['message' => 'Restitution validée avec succès.']];
    }

    public function updateAffectation(int $id, array $data): array
    {
        $fields = [];
        $binds = [':id' => $id];

        $allowed = ['beneficiaire_nom', 'beneficiaire_matricule', 'service_nom', 'date_affectation', 'motif', 'statut', 'remarques'];
        foreach ($allowed as $f) {
            if (isset($data[$f])) {
                $fields[] = "{$f} = :{$f}";
                $binds[":{$f}"] = trim($data[$f]);
            }
        }

        if (empty($fields)) {
            return ['status' => 400, 'data' => ['error' => 'Aucune donnée à modifier.']];
        }

        $fields[] = "updated_at = CURRENT_TIMESTAMP";
        $sql = "UPDATE affectations SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);

        return ['status' => 200, 'data' => ['message' => 'Affectation mise à jour avec succès.']];
    }

    public function deleteAffectation(int $id): array
    {
        $stmt = $this->db->prepare("DELETE FROM affectations WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return ['status' => 200, 'data' => ['message' => 'Affectation supprimée avec succès.']];
    }

    public function getMaintenances(array $params = []): array
    {
        $sql = "SELECT * FROM maintenances WHERE 1=1";
        $binds = [];
        if (!empty($params['q'])) {
            $sql .= " AND (
                LOWER(code_inventaire) LIKE :q1 
                OR LOWER(libelle_bien) LIKE :q2 
                OR LOWER(description_panne) LIKE :q3 
                OR LOWER(COALESCE(prestataire, '')) LIKE :q4 
                OR LOWER(statut) LIKE :q5 
                OR LOWER(priorite) LIKE :q6
            )";
            $term = '%' . strtolower(trim($params['q'])) . '%';
            $binds[':q1'] = $term;
            $binds[':q2'] = $term;
            $binds[':q3'] = $term;
            $binds[':q4'] = $term;
            $binds[':q5'] = $term;
            $binds[':q6'] = $term;
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);
        return ['status' => 200, 'data' => $stmt->fetchAll()];
    }

    public function createMaintenance(array $data): array
    {
        $bien_id = (int)($data['bien_id'] ?? 0);
        $code_inventaire = trim($data['code_inventaire'] ?? '');
        $libelle_bien = trim($data['libelle_bien'] ?? '');
        $description = trim($data['description_panne'] ?? '');
        $priorite = $data['priorite'] ?? 'Moyenne';
        $prestataire = trim($data['prestataire'] ?? 'Maintenance Interne');
        $cout = (int)($data['cout_reparation_fcfa'] ?? 0);

        if (empty($code_inventaire)) {
            $code_inventaire = ($bien_id === 3 || $bien_id === 0) ? 'GPE-000015' : 'GPE-' . str_pad($bien_id, 6, '0', STR_PAD_LEFT);
        }
        if (empty($libelle_bien) || $libelle_bien === 'Équipement') {
            if ($bien_id === 3 || $code_inventaire === 'GPE-000015') {
                $libelle_bien = 'Serveur Rack Dell PowerEdge R750 64GB';
            } else {
                $libelle_bien = 'Équipement de l\'État Togolais';
            }
        }

        if (empty($description)) {
            return ['status' => 400, 'data' => ['error' => 'Description de la panne obligatoire.']];
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO maintenances (
                    bien_id, code_inventaire, libelle_bien, description_panne,
                    priorite, statut, prestataire, cout_reparation_fcfa, date_signalement
                ) VALUES (
                    :bien_id, :code, :libelle, :desc, :prio, 'En attente', :prestataire, :cout, CURRENT_DATE
                )
            ");
            $stmt->execute([
                ':bien_id' => $bien_id,
                ':code' => $code_inventaire,
                ':libelle' => $libelle_bien,
                ':desc' => $description,
                ':prio' => $priorite,
                ':prestataire' => $prestataire,
                ':cout' => $cout
            ]);

            return [
                'status' => 201,
                'data' => [
                    'message' => 'Ticket de panne/maintenance ouvert avec succès.',
                    'id' => $this->db->lastInsertId()
                ]
            ];
        } catch (\Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Erreur signalement : ' . $e->getMessage()]];
        }
    }

    public function updateMaintenance(int $id, array $data): array
    {
        $fields = [];
        $binds = [':id' => $id];

        if (isset($data['description_panne'])) {
            $fields[] = "description_panne = :description_panne";
            $binds[':description_panne'] = trim($data['description_panne']);
        }

        if (isset($data['priorite'])) {
            $fields[] = "priorite = :priorite";
            $binds[':priorite'] = trim($data['priorite']);
        }

        if (isset($data['statut'])) {
            $fields[] = "statut = :statut";
            $binds[':statut'] = $data['statut'];
            if ($data['statut'] === 'Réparé') {
                $fields[] = "date_resolution = CURRENT_DATE";
            }
        }

        if (isset($data['cout_reparation_fcfa'])) {
            $fields[] = "cout_reparation_fcfa = :cout";
            $binds[':cout'] = (int)$data['cout_reparation_fcfa'];
        }

        if (isset($data['prestataire'])) {
            $fields[] = "prestataire = :prestataire";
            $binds[':prestataire'] = $data['prestataire'];
        }

        if (empty($fields)) {
            return ['status' => 400, 'data' => ['error' => 'Aucun champ à modifier.']];
        }

        $fields[] = "updated_at = CURRENT_TIMESTAMP";
        $sql = "UPDATE maintenances SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);

        return ['status' => 200, 'data' => ['message' => 'Ticket de maintenance actualisé.']];
    }

    public function deleteMaintenance(int $id): array
    {
        $stmt = $this->db->prepare("DELETE FROM maintenances WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return ['status' => 200, 'data' => ['message' => 'Ticket de maintenance supprimé avec succès.']];
    }
}
