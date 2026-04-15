<?php
// config.php - Configuration centrale (À MODIFIER AVEC VOS IDENTIFIANTS)

$config = [
    // Votre profil professionnel
    'profil' => [
        'postes' => ['administrateur reseaux', 'support IT', 'technicien support', 'admin systeme', 'helpdesk'],
        'competences' => ['reseaux', 'windows server', 'linux', 'active directory', 'cisco', 'vmware', 'tcpip', 'vlan', 'firewall', 'dns', 'dhcp'],
        'pays' => ['Cameroun', 'France', 'Canada', 'Belgique', 'Suisse', "Côte d'Ivoire"]
    ],
    
    // EmailJS - REMPLACEZ PAR VOS IDENTIFIANTS (ou laissez vide pour désactiver l'email)
    'emailjs' => [
        'service_id' => 'service_v09mrk4',     // ← À MODIFIER
        'template_id' => 'template_sxmm6ag',   // ← À MODIFIER
        'user_id' => 'kLRmRgSRxvhv-I4fW'            // ← À MODIFIER
    ],
    
    // Votre email pour recevoir les alertes
    'destinataire_email' => 'nofemb@gmail.com',  // ← À MODIFIER
    
    // Dossiers (ne pas modifier)
    'dossier_cv' => __DIR__ . '/cv/',
    'dossier_data' => __DIR__ . '/data/'
];

// Créer les dossiers s'ils n'existent pas
if (!file_exists($config['dossier_data'])) {
    mkdir($config['dossier_data'], 0777, true);
}

return $config;
?>