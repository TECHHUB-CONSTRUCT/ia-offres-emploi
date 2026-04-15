<?php
// send_email.php - Envoi d'email via EmailJS

require_once 'config.php';

function envoyerEmailEmailJS($config, $nbOffres, $urlWeb) {
    $emailjs = $config['emailjs'];
    
    $data = [
        'service_id' => $emailjs['service_id'],
        'template_id' => $emailjs['template_id'],
        'user_id' => $emailjs['user_id'],
        'template_params' => [
            'to_email' => $config['destinataire_email'],
            'subject' => "🤖 IA Emploi - {$nbOffres} offres trouvées",
            'message' => "Bonjour,\n\nL'agent IA a trouvé {$nbOffres} offres correspondant à votre profil.\n\nConsultez-les ici: {$urlWeb}\n\nDate: " . date('d/m/Y') . "\n\nBonne recherche !",
            'reply_to' => $config['destinataire_email']
        ]
    ];
    
    $ch = curl_init('https://api.emailjs.com/api/v1.0/email/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "✅ Email envoyé avec succès\n";
        return true;
    } else {
        echo "❌ Erreur EmailJS: {$httpCode}\n";
        return false;
    }
}

// Compter les offres
$offresFile = $config['dossier_data'] . 'offres.json';
$nbOffres = 0;
if (file_exists($offresFile)) {
    $offres = json_decode(file_get_contents($offresFile), true);
    $nbOffres = count($offres);
}

// URL GitHub Pages (à modifier)
$urlWeb = "https://" . getenv('GITHUB_REPOSITORY_OWNER') . ".github.io/ia-offres-emploi-php/";

envoyerEmailEmailJS($config, $nbOffres, $urlWeb);
?>