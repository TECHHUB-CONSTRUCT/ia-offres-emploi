<?php
// send_email.php - Version simplifiée (sans EmailJS pour l'instant)

require_once 'config.php';

function envoyerEmailSimple($config, $nbOffres, $urlWeb) {
    $to = $config['destinataire_email'];
    $subject = "🤖 IA Emploi - {$nbOffres} offres trouvées le " . date('d/m/Y');
    
    $message = "Bonjour,\n\n";
    $message .= "L'agent IA a trouvé {$nbOffres} offre(s) correspondant à votre profil.\n\n";
    $message .= "📊 Détails:\n";
    $message .= "- Date: " . date('d/m/Y à H:i') . "\n";
    $message .= "- Offres pertinentes: {$nbOffres}\n\n";
    $message .= "🔗 Consultez toutes les offres ici:\n";
    $message .= "{$urlWeb}\n\n";
    $message .= "📌 Conseils:\n";
    $message .= "- Postulez rapidement\n";
    $message .= "- Personnalisez chaque candidature\n";
    $message .= "- Mettez à jour votre CV régulièrement\n\n";
    $message .= "Bonne recherche !\n\n";
    $message .= "---\n";
    $message .= "Cet email est généré automatiquement par votre agent IA.\n";
    
    $headers = "From: Agent IA Emploi <agent@ia-emploi.com>\r\n";
    $headers .= "Reply-To: {$to}\r\n";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "✅ Email envoyé avec succès\n";
        echo "   (Vérifiez vos spams si vous ne le voyez pas)\n";
        return true;
    } else {
        echo "⚠️ Impossible d'envoyer l'email automatiquement\n";
        echo "   Ce n'est pas grave, l'interface web est disponible !\n";
        return false;
    }
}

// Compter les offres
$offresFile = $config['dossier_data'] . 'offres.json';
$nbOffres = 0;
$offres = [];

if (file_exists($offresFile)) {
    $offres = json_decode(file_get_contents($offresFile), true);
    $nbOffres = count($offres);
    echo "📊 {$nbOffres} offres chargées depuis le fichier\n";
} else {
    echo "⚠️ Aucune offre trouvée\n";
}

// URL GitHub Pages
$githubUser = 'TECHHUB-CONSTRUCT';  // ← À MODIFIER AVEC VOTRE PSEUDO GITHUB
$urlWeb = "https://{$githubUser}.github.io/ia-offres-emploi/";

echo "📧 Tentative d'envoi d'email...\n";
envoyerEmailSimple($config, $nbOffres, $urlWeb);

// Afficher les offres dans la console
echo "\n📋 LISTE DES OFFRES DISPONIBLES:\n";
echo "-----------------------------------\n";
if ($nbOffres > 0) {
    foreach ($offres as $i => $offre) {
        echo ($i+1) . ". " . $offre['titre'] . "\n";
        echo "   📍 " . $offre['lieu'] . "\n";
        echo "   🏢 " . $offre['entreprise'] . "\n";
        echo "   🔗 " . $offre['lien'] . "\n\n";
    }
} else {
    echo "Aucune offre disponible pour le moment.\n";
}
?>