@echo off
chcp 65001 > nul
echo ====================================================================
echo   GEST-PATRIMOINE - LANCEMENT DOCKER MICROSERVICES & POSTGRESQL
echo   Auteure : Amele Rolande GOLI
echo ====================================================================
echo.
echo [1/3] Verification et demarrage des conteneurs Docker...
docker compose up -d

echo.
echo [2/3] Verification de l'etat des services...
docker compose ps

echo.
echo [3/3] Ouverture du Dashboard sur http://localhost:8080...
timeout /t 3 > nul
start http://localhost:8080

echo.
echo ====================================================================
echo   Architecture Microservices Docker operationnelle !
echo   - Gateway / Frontend : http://localhost:8080
echo   - Auth Service       : http://localhost:8001
echo   - Patrimoine Service : http://localhost:8002
echo   - Affectation Service: http://localhost:8003
echo   - PostgreSQL 16      : localhost:5432 (auth_db, patrimoine_db, affectation_db)
echo ====================================================================
pause
