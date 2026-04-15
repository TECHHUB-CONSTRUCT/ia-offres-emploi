<?php
// extract_cv.php - Extraction du CV

require_once 'config.php';

function extraireTextePDF($cheminFichier) {
    if (!file_exists($cheminFichier)) {
        return '';
    }
    
    // Tenter d'utiliser pdftotext si disponible
    $output = shell_exec("pdftotext \"" . $cheminFichier . "\" - 2>/dev/null");
    if ($output) {
        return $output;
    }
    
    // Fallback: lecture basique
    $contenu = file_get_contents($cheminFichier);
    $texte = preg_replace('/[^\p{L}\p{N}\s\.\,\-\:]/u', ' ', $contenu);
    return $texte;
}

function extraireProfil($texte) {
    $motsCles = [
        'cisco', 'juniper', 'vmware', 'windows server', 'linux', 'ubuntu', 'debian',
        'active directory', 'dns', 'dhcp', 'tcpip', 'vlan', 'firewall', 'fortinet',
        'vpn', 'python', 'bash', 'ansible', 'zabbix', 'nagios', 'wireshark'
    ];
    
    $postes = [
        'administrateur réseaux', 'administrateur réseau', 'administrateur système',
        'support it', 'technicien support', 'helpdesk', 'ingenieur système',
        'admin reseaux', 'admin système'
    ];
    
    $competencesTrouvees = [];
    foreach ($motsCles as $mot) {
        if (stripos($texte, $mot) !== false) {
            $competencesTrouvees[] = $mot;
        }
    }
    
    $postesTrouves = [];
    foreach ($postes as $poste) {
        if (stripos($texte, $poste) !== false) {
            $postesTrouves[] = $poste;
        }
    }
    
    return [
        'competences' => $competencesTrouvees,
        'postes' => $postesTrouves
    ];
}

// Chercher le CV
$cvFile = '';
$extensions = ['pdf', 'doc', 'docx'];
foreach ($extensions as $ext) {
    $fichiers = glob($config['dossier_cv'] . "*." . $ext);
    if (count($fichiers) > 0) {
        $cvFile = $fichiers[0];
        break;
    }
}

if ($cvFile && file_exists($cvFile)) {
    echo "📄 CV trouvé: " . basename($cvFile) . "\n";
    $texteCV = extraireTextePDF($cvFile);
    $profil = extraireProfil($texteCV);
    
    if (empty($profil['competences'])) {
        $profil['competences'] = $config['profil']['competences'];
        $profil['postes'] = $config['profil']['postes'];
        echo "⚠️ Utilisation des compétences par défaut\n";
    } else {
        echo "✅ Compétences trouvées: " . count($profil['competences']) . "\n";
        echo "✅ Postes trouvés: " . count($profil['postes']) . "\n";
    }
} else {
    echo "⚠️ Aucun CV trouvé, utilisation de la configuration\n";
    $profil = $config['profil'];
}

// Sauvegarder le profil
file_put_contents($config['dossier_data'] . 'profil.json', json_encode($profil, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "✅ Profil sauvegardé: " . count($profil['competences']) . " compétences\n";
?>