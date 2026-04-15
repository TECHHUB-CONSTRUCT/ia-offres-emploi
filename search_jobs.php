<?php
// search_jobs.php - Recherche des offres d'emploi (Version démonstration)

require_once 'config.php';

// ========== MODE DÉMONSTRATION ACTIVÉ ==========
// Les vraies offres seront ajoutées plus tard
// Pour l'instant, on utilise des offres exemple
// ===============================================

function rechercherOffresDemonstration() {
    return [
        [
            'titre' => 'Administrateur Réseaux et Sécurité - CDI',
            'entreprise' => 'Orange Cameroun',
            'lieu' => 'Douala, Cameroun',
            'description' => 'Nous recherchons un Administrateur Réseaux pour gérer notre infrastructure. Compétences requises : Cisco, TCP/IP, VLAN, Firewall, Windows Server, Linux. Expérience en support IT appréciée.',
            'lien' => 'https://www.orange.cm/carrieres',
            'source' => 'Orange Cameroun',
            'date' => date('Y-m-d'),
            'score' => 5
        ],
        [
            'titre' => 'Technicien Support IT Niveau 2',
            'entreprise' => 'MTN Cameroun',
            'lieu' => 'Yaoundé, Cameroun',
            'description' => 'Recherche Technicien Support IT pour assistance aux utilisateurs, maintenance du parc informatique, gestion Active Directory, déploiement Windows Server et résolution d\'incidents.',
            'lien' => 'https://www.mtn.cm/recrutement',
            'source' => 'MTN Cameroun',
            'date' => date('Y-m-d'),
            'score' => 4
        ],
        [
            'titre' => 'Ingénieur Système et Réseaux',
            'entreprise' => 'Société Générale Cameroun',
            'lieu' => 'Douala, Cameroun',
            'description' => 'Administration des serveurs Windows/Linux, gestion de la sécurité réseau, configuration VLAN, Firewall, et supervision. Certification Cisco ou équivalent souhaitée.',
            'lien' => 'https://www.societegenerale.cm/carrieres',
            'source' => 'Société Générale',
            'date' => date('Y-m-d'),
            'score' => 5
        ],
        [
            'titre' => 'Admin Réseaux - Support Technique',
            'entreprise' => 'Afriland First Bank',
            'lieu' => 'Yaoundé, Cameroun',
            'description' => 'Gestion de l\'infrastructure réseau, mise en place de politiques de sécurité, supervision des équipements Cisco, Fortinet, et support aux utilisateurs.',
            'lien' => 'https://www.afrilandfirstbank.cm/carrieres',
            'source' => 'Afriland Bank',
            'date' => date('Y-m-d'),
            'score' => 5
        ],
        [
            'titre' => 'Support IT - Helpdesk',
            'entreprise' => 'Express Exchange Cameroun',
            'lieu' => 'Douala, Cameroun',
            'description' => 'Assistance technique aux utilisateurs, résolution d\'incidents, gestion des tickets, déploiement de postes de travail, maintenance du parc informatique.',
            'lien' => 'https://www.expressexchange.cm/recrutement',
            'source' => 'Express Exchange',
            'date' => date('Y-m-d'),
            'score' => 3
        ],
        [
            'titre' => 'Administrateur Systèmes et Réseaux',
            'entreprise' => 'CAMTEL',
            'lieu' => 'Yaoundé, Cameroun',
            'description' => 'Administration des serveurs, gestion du réseau national, supervision des équipements, maintenance préventive et corrective, support technique.',
            'lien' => 'https://www.camtel.cm/recrutement',
            'source' => 'CAMTEL',
            'date' => date('Y-m-d'),
            'score' => 5
        ],
        [
            'titre' => 'Technicien Supérieur en Réseaux',
            'entreprise' => 'NEXTTEL Cameroun',
            'lieu' => 'Douala, Cameroun',
            'description' => 'Installation et configuration d\'équipements réseau, dépannage, support client, connaissance des protocoles TCP/IP, routage, switching.',
            'lien' => 'https://www.nexttel.cm/carrieres',
            'source' => 'NEXTTEL',
            'date' => date('Y-m-d'),
            'score' => 4
        ]
    ];
}

echo "\n🔍 GÉNÉRATION DES OFFRES DE DÉMONSTRATION...\n";

// Générer les offres de démonstration
$offresFinales = rechercherOffresDemonstration();

echo "✅ " . count($offresFinales) . " offres de démonstration générées\n";

// Sauvegarder
file_put_contents($config['dossier_data'] . 'offres.json', json_encode($offresFinales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "💾 Offres sauvegardées dans " . $config['dossier_data'] . "offres.json\n";

// Afficher un résumé
echo "\n📋 RÉSUMÉ DES OFFRES:\n";
echo "-----------------------------------\n";
foreach ($offresFinales as $i => $offre) {
    echo ($i+1) . ". " . $offre['titre'] . "\n";
    echo "   Entreprise: " . $offre['entreprise'] . "\n";
    echo "   Lieu: " . $offre['lieu'] . "\n\n";
}
?>