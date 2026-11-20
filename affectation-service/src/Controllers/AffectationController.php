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
            $sql .= " AND (LOWER(beneficiaire_nom) LIKE :q OR LOWER(code_inventaire) LIKE :q OR LOWER(service_nom) LIKE :q)";
            $binds[':q'] = '%' . strtolower(trim($params['q'])) . '%';
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

    public function getMaintenances(): array
    {
        $stmt = $this->db->query("SELECT * FROM maintenances ORDER BY id DESC");
        return ['status' => 200, 'data' => $stmt->fetchAll()];
    }

    public function createMaintenance(array $data): array
    {
        $bien_id = (int)($data['bien_id'] ?? 0);
        $code_inventaire = trim($data['code_inventaire'] ?? '');
        $libelle_bien = trim($data['libelle_bien'] ?? 'Équipement');
        $description = trim($data['description_panne'] ?? '');
        $priorite = $data['priorite'] ?? 'Moyenne';
        $prestataire = trim($data['prestataire'] ?? 'Maintenance Interne');
        $cout = (int)($data['cout_reparation_fcfa'] ?? 0);

        if ($bien_id <= 0 || empty($code_inventaire) || empty($description)) {
            return ['status' => 400, 'data' => ['error' => 'Bien et description de la panne obligatoires.']];
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
}
