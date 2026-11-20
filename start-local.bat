@echo off
chcp 65001 > nul
echo ====================================================================
echo   GEST-PATRIMOINE - LANCEMENT LOCAL STANDALONE (Sans Docker)
echo   Auteure : Amele Rolande GOLI
echo ====================================================================
echo.
echo [1/2] Demarrage du serveur Web Gateway sur le port 8080...
start "GEST-PATRIMOINE Gateway" php -S 0.0.0.0:8080 -t gateway-frontend/public

echo [2/2] Ouverture de la plateforme dans votre navigateur...
timeout /t 2 > nul
start http://localhost:8080

echo.
echo ====================================================================
echo   Plateforme operationnelle sur : http://localhost:8080
echo   Identifiants Demo : admin@patrimoine.gouv.tg / password123
echo   Pour arreter le serveur, fermez la fenetre de commande correspondante.
echo ====================================================================
pause
