<?php

namespace Gateway;

class ServicesClient
{
    private string $authUrl;
    private string $patrimoineUrl;
    private string $affectationUrl;

    public function __construct()
    {
        $this->authUrl        = getenv('AUTH_SERVICE_URL') ?: 'http://auth-service:8001';
        $this->patrimoineUrl   = getenv('PATRIMOINE_SERVICE_URL') ?: 'http://patrimoine-service:8002';
        $this->affectationUrl = getenv('AFFECTATION_SERVICE_URL') ?: 'http://affectation-service:8003';
    }

    private function request(string $url, string $method = 'GET', array $data = []): array
    {
        $ch = curl_init();
        $opts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 2, // 2s timeout
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
        if (!empty($res)) return $res;

        return $this->getFallbackAuthController()->login($credentials);
    }

    public function getUsers(): array
    {
        $res = $this->request("{$this->authUrl}/api/users");
        if (!empty($res)) return $res;

        return $this->getFallbackAuthController()->getUsers()['data'] ?? [];
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
    public function getAffectations(): array
    {
        $res = $this->request("{$this->affectationUrl}/api/affectations");
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->getAffectations()['data'] ?? [];
    }

    public function createAffectation(array $data): array
    {
        $res = $this->request("{$this->affectationUrl}/api/affectations", 'POST', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->createAffectation($data);
    }

    public function getMaintenances(): array
    {
        $res = $this->request("{$this->affectationUrl}/api/maintenances");
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->getMaintenances()['data'] ?? [];
    }

    public function createMaintenance(array $data): array
    {
        $res = $this->request("{$this->affectationUrl}/api/maintenances", 'POST', $data);
        if (!empty($res)) return $res;

        return $this->getFallbackAffectationController()->createMaintenance($data);
    }
}
