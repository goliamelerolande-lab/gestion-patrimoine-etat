<?php

namespace Gateway;

class ServicesClient
{
    private string $authUrl;
    private string $patrimoineUrl;
    private string $affectationUrl;
    private bool $isStandalone;

    public function __construct()
    {
        $this->isStandalone   = empty(getenv('AUTH_SERVICE_URL'));
        $this->authUrl        = getenv('AUTH_SERVICE_URL') ?: 'http://127.0.0.1:8001';
        $this->patrimoineUrl   = getenv('PATRIMOINE_SERVICE_URL') ?: 'http://127.0.0.1:8002';
        $this->affectationUrl = getenv('AFFECTATION_SERVICE_URL') ?: 'http://127.0.0.1:8003';
    }

    private function request(string $url, string $method = 'GET', array $data = []): array
    {
        if ($this->isStandalone) {
            return [];
        }

        $ch = curl_init();
        $opts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT_MS => 200,
            CURLOPT_TIMEOUT_MS => 600,
            CURLOPT_NOSIGNAL => 1,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json']
        ];

        if ($method === 'POST') {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_POSTFIELDS] = json_encode($data);
        } elseif ($method === 'PUT') {
            $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $opts[CURLOPT_POSTFIELDS] = json_encode($data);
        } elseif ($method === 'DELETE') {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response && $httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true) ?? [];
        }

        return [];
    }

    // --- Méthodes de secours directes (Local Fallback si microservices non démarrés en HTTP) ---
    private function getFallbackPatrimoineController(): \PatrimoineService\Controllers\PatrimoineController
    {
        require_once __DIR__ . '/../../patrimoine-service/src/Database.php';
        require_once __DIR__ . '/../../patrimoine-service/src/Controllers/PatrimoineController.php';
        return new \PatrimoineService\Controllers\PatrimoineController();
    }

    private function getFallbackAuthController(): \AuthService\Controllers\AuthController
    {
        require_once __DIR__ . '/../../auth-service/src/Database.php';
        require_once __DIR__ . '/../../auth-service/src/Controllers/AuthController.php';
        return new \AuthService\Controllers\AuthController();
    }

    private function getFallbackAffectationController(): \AffectationService\Controllers\AffectationController
    {
        require_once __DIR__ . '/../../affectation-service/src/Database.php';
        require_once __DIR__ . '/../../affectation-service/src/Controllers/AffectationController.php';
        return new \AffectationService\Controllers\AffectationController();
    }

    // =========================================================================
    // API PATRIMOINE
    // =========================================================================
    public function getBiens(array $params = []): array
    {
        $queryString = http_build_query($params);
        $res = $this->request("{$this->patrimoineUrl}/api/biens?{$queryString}");
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->getBiens($params)['data'] ?? [];
    }

    public function getBien(int $id): array
    {
        $res = $this->request("{$this->patrimoineUrl}/api/biens/{$id}");
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->getBien($id)['data'] ?? [];
    }

    public function createBien(array $data): array
    {
        $res = $this->request("{$this->patrimoineUrl}/api/biens", 'POST', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->createBien($data);
    }

    public function updateBien(int $id, array $data): array
    {
        $res = $this->request("{$this->patrimoineUrl}/api/biens/{$id}", 'PUT', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->updateBien($id, $data);
    }

    public function deleteBien(int $id): array
    {
        $res = $this->request("{$this->patrimoineUrl}/api/biens/{$id}", 'DELETE');
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->deleteBien($id);
    }

    public function getStats(): array
    {
        $res = $this->request("{$this->patrimoineUrl}/api/stats");
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->getStats()['data'] ?? [];
    }

    public function getCategories(): array
    {
        $res = $this->request("{$this->patrimoineUrl}/api/categories");
        if (!empty($res)) return $res;

        return $this->getFallbackPatrimoineController()->getCategories()['data'] ?? [];
    }

    // =========================================================================
    // API AUTH & UTILISATEURS
    // =========================================================================
    public function login(array $credentials): array
    {
        $res = $this->request("{$this->authUrl}/api/auth/login", 'POST', $credentials);
        if (!empty($res)) {
            $user = $res['data']['user'] ?? $res['user'] ?? null;
            if ($user !== null) {
                return [
                    'status' => 200,
                    'data' => [
                        'user' => $user,
                        'token' => $res['data']['token'] ?? $res['token'] ?? 'jwt_token'
                    ]
                ];
            }
        }

        return $this->getFallbackAuthController()->login($credentials);
    }

    public function getUsers(): array
    {
        $res = $this->request("{$this->authUrl}/api/users");
        if (!empty($res)) return $res;

        return $this->getFallbackAuthController()->getUsers()['data'] ?? [];
    }

    public function updateUser(int $id, array $data): array
    {
        $res = $this->request("{$this->authUrl}/api/users/{$id}", 'PUT', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAuthController()->updateUser($id, $data);
    }

    public function getMinisteres(): array
    {
        $res = $this->request("{$this->authUrl}/api/ministeres");
        if (!empty($res)) return $res;

        return $this->getFallbackAuthController()->getMinisteres()['data'] ?? [];
    }

    // =========================================================================
    // API AFFECTATIONS & MAINTENANCE
    // =========================================================================
    public function getAffectations(array $params = []): array
    {
        $queryString = http_build_query($params);
        $res = $this->request("{$this->affectationUrl}/api/affectations?{$queryString}");
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->getAffectations($params)['data'] ?? [];
    }

    public function createAffectation(array $data): array
    {
        $res = $this->request("{$this->affectationUrl}/api/affectations", 'POST', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->createAffectation($data);
    }

    public function updateAffectation(int $id, array $data): array
    {
        $res = $this->request("{$this->affectationUrl}/api/affectations/{$id}", 'PUT', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->updateAffectation($id, $data);
    }

    public function deleteAffectation(int $id): array
    {
        $res = $this->request("{$this->affectationUrl}/api/affectations/{$id}", 'DELETE');
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->deleteAffectation($id);
    }

    public function getMaintenances(array $params = []): array
    {
        $queryString = http_build_query($params);
        $res = $this->request("{$this->affectationUrl}/api/maintenances?{$queryString}");
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->getMaintenances($params)['data'] ?? [];
    }

    public function createMaintenance(array $data): array
    {
        $res = $this->request("{$this->affectationUrl}/api/maintenances", 'POST', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->createMaintenance($data);
    }

    public function updateMaintenance(int $id, array $data): array
    {
        $res = $this->request("{$this->affectationUrl}/api/maintenances/{$id}", 'PUT', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->updateMaintenance($id, $data);
    }

    public function deleteMaintenance(int $id): array
    {
        $res = $this->request("{$this->affectationUrl}/api/maintenances/{$id}", 'DELETE');
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->deleteMaintenance($id);
    }
}
