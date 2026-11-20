<?php

namespace PatrimoineService\Controllers;

use PatrimoineService\Database;
use PDO;

class PatrimoineController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getBiens(array $params = []): array
    {
        $sql = "
            SELECT b.*, c.libelle as categorie_libelle, c.code as categorie_code
            FROM biens b
            LEFT JOIN categories c ON b.categorie_id = c.id
            WHERE 1=1
        ";
        $binds = [];

        if (!empty($params['q'])) {
            $sql .= " AND (
                LOWER(b.libelle) LIKE :q1 
                OR LOWER(b.code_inventaire) LIKE :q2 
                OR LOWER(b.localisation) LIKE :q3 
                OR LOWER(b.ministere) LIKE :q4 
                OR LOWER(COALESCE(b.fournisseur, '')) LIKE :q5 
                OR LOWER(b.etat) LIKE :q6 
                OR LOWER(b.statut) LIKE :q7 
                OR LOWER(COALESCE(c.libelle, '')) LIKE :q8
            )";
            $term = '%' . strtolower(trim($params['q'])) . '%';
            $binds[':q1'] = $term;
            $binds[':q2'] = $term;
            $binds[':q3'] = $term;
            $binds[':q4'] = $term;
            $binds[':q5'] = $term;
            $binds[':q6'] = $term;
            $binds[':q7'] = $term;
            $binds[':q8'] = $term;
        }

        if (!empty($params['categorie_id'])) {
            $sql .= " AND b.categorie_id = :cat";
            $binds[':cat'] = (int)$params['categorie_id'];
        }

        if (!empty($params['etat'])) {
            $sql .= " AND b.etat = :etat";
            $binds[':etat'] = trim($params['etat']);
        }

        if (!empty($params['ministere'])) {
            $sql .= " AND b.ministere = :min";
            $binds[':min'] = trim($params['ministere']);
        }

        $sql .= " ORDER BY b.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);
        $biens = $stmt->fetchAll();

        return ['status' => 200, 'data' => $biens];
    }

    public function getBien(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, c.libelle as categorie_libelle, c.code as categorie_code, c.duree_amortissement_ans
            FROM biens b
            LEFT JOIN categories c ON b.categorie_id = c.id
            WHERE b.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $bien = $stmt->fetch();

        if (!$bien) {
            return ['status' => 404, 'data' => ['error' => 'Bien introuvable.']];
        }

        return ['status' => 200, 'data' => $bien];
    }

    public function createBien(array $data): array
    {
        $libelle = trim($data['libelle'] ?? '');
        $categorie_id = (int)($data['categorie_id'] ?? 1);
        $date_acquisition = $data['date_acquisition'] ?? date('Y-m-d');
        $valeur = (int)($data['valeur_acquisition_fcfa'] ?? 0);
        $etat = $data['etat'] ?? 'Neuf';
        $statut = $data['statut'] ?? 'Disponible';
        $ministere = trim($data['ministere'] ?? "Ministère de l'Économie et des Finances");
        $localisation = trim($data['localisation'] ?? 'Bâtiment Central');
        $fournisseur = trim($data['fournisseur'] ?? '');
        $numero_serie = trim($data['numero_serie'] ?? '');
        $description = trim($data['description'] ?? '');

        if (empty($libelle) || $valeur <= 0) {
            return ['status' => 400, 'data' => ['error' => 'Le libellé et une valeur financière en FCFA sont obligatoires.']];
        }

        // Génération automatique du code GPE-XXXXXX suivant
        $stmtMax = $this->db->query("SELECT MAX(id) as max_id FROM biens");
        $nextId = ((int)($stmtMax->fetch()['max_id'] ?? 0)) + 1;
        $code_inventaire = sprintf("GPE-%06d", $nextId);

        try {
            $stmt = $this->db->prepare("
                INSERT INTO biens (
                    code_inventaire, libelle, categorie_id, date_acquisition,
                    valeur_acquisition_fcfa, valeur_actuelle_fcfa, etat, statut,
                    ministere, localisation, fournisseur, numero_serie, description
                ) VALUES (
                    :code, :libelle, :cat, :date_acq, :valeur, :valeur, :etat, :statut,
                    :ministere, :localisation, :fournisseur, :num_serie, :description
                )
            ");
            $stmt->execute([
                ':code' => $code_inventaire,
                ':libelle' => $libelle,
                ':cat' => $categorie_id,
                ':date_acq' => $date_acquisition,
                ':valeur' => $valeur,
                ':etat' => $etat,
                ':statut' => $statut,
                ':ministere' => $ministere,
                ':localisation' => $localisation,
                ':fournisseur' => $fournisseur,
                ':num_serie' => $numero_serie,
                ':description' => $description
            ]);

            return [
                'status' => 201,
                'data' => [
                    'message' => "Bien enregistré avec succès dans le patrimoine de l'État.",
                    'id' => $this->db->lastInsertId(),
                    'code_inventaire' => $code_inventaire
                ]
            ];
        } catch (\Exception $e) {
            return ['status' => 500, 'data' => ['error' => 'Erreur enregistrement : ' . $e->getMessage()]];
        }
    }

    public function updateBien(int $id, array $data): array
    {
        $fields = [];
        $binds = [':id' => $id];

        $allowed = [
            'libelle', 'categorie_id', 'date_acquisition', 'valeur_acquisition_fcfa',
            'valeur_actuelle_fcfa', 'etat', 'statut', 'ministere', 'localisation',
            'fournisseur', 'numero_serie', 'description'
        ];

        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
                if (in_array($field, ['categorie_id', 'valeur_acquisition_fcfa', 'valeur_actuelle_fcfa'])) {
                    $binds[":{$field}"] = (int)$data[$field];
                } else {
                    $binds[":{$field}"] = trim($data[$field]);
                }
            }
        }

        if (empty($fields)) {
            return ['status' => 400, 'data' => ['error' => 'Aucune donnée à modifier.']];
        }

        $fields[] = "updated_at = CURRENT_TIMESTAMP";

        $sql = "UPDATE biens SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($binds);

        return ['status' => 200, 'data' => ['message' => 'Mise à jour effectuée avec succès.']];
    }

    public function deleteBien(int $id): array
    {
        $stmt = $this->db->prepare("DELETE FROM biens WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return ['status' => 200, 'data' => ['message' => "Bien supprimé définitivement du registre."]];
    }

    public function getCategories(): array
    {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY id ASC");
        return ['status' => 200, 'data' => $stmt->fetchAll()];
    }

    public function getStats(): array
    {
        $totalBiens = (int)$this->db->query("SELECT COUNT(*) FROM biens WHERE statut != 'Sorti'")->fetchColumn();
        $valeurCumulee = (int)$this->db->query("SELECT SUM(valeur_acquisition_fcfa) FROM biens WHERE statut != 'Sorti'")->fetchColumn();

        // Répartition par état (comme dans Rapports - GPE.pdf)
        $stmtEtat = $this->db->query("
            SELECT etat, COUNT(*) as count, SUM(valeur_acquisition_fcfa) as valeur_totale
            FROM biens
            WHERE statut != 'Sorti'
            GROUP BY etat
        ");
        $repartitionEtat = $stmtEtat->fetchAll();

        // Répartition par catégorie
        $stmtCat = $this->db->query("
            SELECT c.libelle, COUNT(b.id) as count, COALESCE(SUM(b.valeur_acquisition_fcfa), 0) as valeur
            FROM categories c
            LEFT JOIN biens b ON c.id = b.categorie_id AND b.statut != 'Sorti'
            GROUP BY c.id, c.libelle
        ");
        $repartitionCategorie = $stmtCat->fetchAll();

        return [
            'status' => 200,
            'data' => [
                'total_biens' => $totalBiens,
                'valeur_cumulee_fcfa' => $valeurCumulee,
                'date_rapport' => date('d/m/Y'),
                'repartition_etat' => $repartitionEtat,
                'repartition_categorie' => $repartitionCategorie
            ]
        ];
    }
}
