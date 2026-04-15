<?php
// cron_jobs.php - Script complet pour GitHub Actions

echo "========================================\n";
echo "🤖 AGENT IA RECHERCHE D'EMPLOI\n";
echo "========================================\n\n";

// Exécuter toutes les étapes
echo "1️⃣ Extraction du CV...\n";
include 'extract_cv.php';

echo "\n2️⃣ Recherche des offres...\n";
include 'search_jobs.php';

echo "\n3️⃣ Envoi de l'email...\n";
include 'send_email.php';

echo "\n✅ Traitement terminé !\n";
?>