<?php
// config.php - Configuration centrale

$config = [
    // Votre profil
    'profil' => [
        'postes' => ['administrateur reseaux', 'support IT', 'technicien support', 'admin systeme'],
        'competences' => ['reseaux', 'windows server', 'linux', 'active directory', 'cisco', 'vmware', 'tcpip'],
        'pays' => ['Cameroun', 'France', 'Canada', 'Belgique', 'Suisse', 'Côte d\'Ivoire']
    ],
    
    // EmailJS (remplacez par vos identifiants)
    'emailjs' => [
        'service_id' => 'service_v09mrk4',
        'template_id' => 'service_v09mrk4',
        'user_id' => 'kLRmRgSRxvhv-I4fW'
    ],
    
    // Votre email pour recevoir les alertes
    'destinataire_email' => 'votre_email@exemple.com',
    
    // Dossiers
    'dossier_cv' => __DIR__ . '/cv/',
    'dossier_data' => __DIR__ . '/data/'
];

// Créer les dossiers s'ils n'existent pas
if (!file_exists($config['dossier_data'])) {
    mkdir($config['dossier_data'], 0777, true);
}

return $config;
?>