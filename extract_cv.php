<?php
// extract_cv.php - Extraction du CV (version PHP)

require_once 'config.php';

function extraireTextePDF($cheminFichier) {
    // Solution simple sans librairie externe
    // Note: Pour un vrai parsing PDF, il faudrait installer "pdftotext"
    // Version simplifiée qui lit le texte basique
    
    if (!file_exists($cheminFichier)) {
        return '';
    }
    
    // Tenter d'utiliser pdftotext si disponible
    $output = shell_exec("pdftotext \"$cheminFichier\" - 2>/dev/null");
    if ($output) {
        return $output;
    }
    
    // Fallback: extraction basique avec des regex
    $contenu = file_get_contents($cheminFichier);
    // Nettoyer les caractères non textuels
    $texte = preg_replace('/[^\p{L}\p{N}\s\.\,\-\:]/u', ' ', $contenu);
    return $texte;
}

function extraireProfil($texte) {
    $motsCles = [
        'cisco', 'juniper', 'vmware', 'windows server', 'linux',
        'active directory', 'dns', 'dhcp', 'tcp/ip', 'vlan',
        'firewall', 'vpn', 'python', 'bash', 'ansible'
    ];
    
    $postes = [
        'administrateur réseaux', 'administrateur système',
        'support IT', 'technicien support', 'helpdesk'
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
    $fichiers = glob($config['dossier_cv'] . "*.{$ext}");
    if (count($fichiers) > 0) {
        $cvFile = $fichiers[0];
        break;
    }
}

if ($cvFile && file_exists($cvFile)) {
    echo "📄 CV trouvé: " . basename($cvFile) . "\n";
    $texteCV = extraireTextePDF($cvFile);
    $profil = extraireProfil($texteCV);
    
    // Si aucune compétence trouvée, utiliser la config par défaut
    if (empty($profil['competences'])) {
        $profil['competences'] = $config['profil']['competences'];
        $profil['postes'] = $config['profil']['postes'];
        echo "⚠️ Utilisation des compétences par défaut\n";
    }
} else {
    echo "⚠️ Aucun CV trouvé, utilisation de la configuration\n";
    $profil = $config['profil'];
}

// Sauvegarder le profil
file_put_contents($config['dossier_data'] . 'profil.json', json_encode($profil, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "✅ Profil sauvegardé: " . count($profil['competences']) . " compétences\n";
?>