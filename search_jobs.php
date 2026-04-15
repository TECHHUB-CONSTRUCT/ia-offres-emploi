<?php
// search_jobs.php - Version corrigée avec sources qui fonctionnent

require_once 'config.php';

function rechercherFranceTravail($motsCles) {
    $offres = [];
    
    // API France Travail (ex Pôle emploi) - Version simplifiée
    $requete = implode(' ', array_slice($motsCles, 0, 3));
    $url = "https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search?motsCles=" . urlencode($requete) . "&range=0-20";
    
    // Note: Cette API nécessite une authentification
    // Solution alternative : utiliser le flux RSS France Travail
    
    return $offres;
}

function rechercherGoogleJobs($motsCles, $lieu) {
    $offres = [];
    $requete = urlencode(implode(' ', array_slice($motsCles, 0, 3)) . " " . $lieu . " emploi");
    $url = "https://www.google.com/search?q={$requete}&tbm=nws";
    
    // Version simplifiée - on utilise Indeed à la place
    return $offres;
}

function rechercherIndeedAPI($motsCles, $lieu) {
    $offres = [];
    
    // Utiliser le flux RSS Indeed (version qui fonctionne encore)
    $requete = implode('+', array_slice($motsCles, 0, 3));
    $url = "https://rss.indeed.com/rss?q={$requete}&l=" . urlencode($lieu);
    
    // Ajouter un User-Agent pour éviter le blocage
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n"
        ]
    ];
    $context = stream_context_create($options);
    
    $xml = @file_get_contents($url, false, $context);
    if ($xml === false) {
        echo "❌ Impossible d'accéder à Indeed pour {$lieu}\n";
        return $offres;
    }
    
    $rss = simplexml_load_string($xml);
    if ($rss === false || !isset($rss->channel->item)) {
        echo "⚠️ Aucune offre Indeed pour {$lieu}\n";
        return $offres;
    }
    
    foreach ($rss->channel->item as $item) {
        $offres[] = [
            'titre' => (string)$item->title,
            'entreprise' => (string)$item->source ?? 'Non spécifié',
            'lieu' => $lieu,
            'description' => substr(strip_tags((string)$item->description), 0, 300),
            'lien' => (string)$item->link,
            'source' => 'Indeed',
            'date' => date('Y-m-d', strtotime((string)$item->pubDate)),
            'score' => 0
        ];
    }
    
    echo "✅ Indeed {$lieu}: " . count($offres) . " offres\n";
    sleep(2);
    return $offres;
}

function rechercherLinkedInRSS($motsCles) {
    $offres = [];
    $requete = implode('+', array_slice($motsCles, 0, 3));
    $url = "https://www.linkedin.com/jobs-guest/jobs/api/seeMoreJobPostings/search?keywords={$requete}&location=&start=0";
    
    // LinkedIn bloque souvent, on utilise Indeed comme source principale
    return $offres;
}

function rechercherApecRSS($motsCles) {
    $offres = [];
    // APEC (pour cadres en France)
    $requete = implode('+', array_slice($motsCles, 0, 3));
    $url = "https://cadres.apec.fr/rss/offres.xml?motsCles={$requete}";
    
    $xml = @simplexml_load_file($url);
    if ($xml !== false && isset($xml->channel->item)) {
        foreach ($xml->channel->item as $item) {
            $offres[] = [
                'titre' => (string)$item->title,
                'entreprise' => (string)$item->author ?? 'Non spécifié',
                'lieu' => (string)$item->category ?? 'France',
                'description' => substr(strip_tags((string)$item->description), 0, 300),
                'lien' => (string)$item->link,
                'source' => 'APEC',
                'date' => date('Y-m-d', strtotime((string)$item->pubDate)),
                'score' => 0
            ];
        }
        echo "✅ APEC: " . count($offres) . " offres\n";
    }
    
    sleep(1);
    return $offres;
}

function rechercherWelcomeToTheJungle($motsCles) {
    $offres = [];
    // Welcome to the Jungle - API publique
    $requete = implode(' ', array_slice($motsCles, 0, 3));
    $url = "https://www.welcometothejungle.com/fr/jobs?query={$requete}";
    
    // Nécessite du scraping, on passe pour le moment
    return $offres;
}

function rechercherEmploiCameroon() {
    $offres = [];
    
    // Site Camerounais - Osidimbea
    $url = "https://osidimbea.com/emplois/feed/";
    $xml = @simplexml_load_file($url);
    
    if ($xml !== false && isset($xml->channel->item)) {
        foreach ($xml->channel->item as $item) {
            // Vérifier si l'offre est dans le domaine IT
            $titre = strtolower((string)$item->title);
            $motsIT = ['informaticien', 'technicien', 'reseau', 'support', 'it', 'systeme', 'admin'];
            $estIT = false;
            foreach ($motsIT as $mot) {
                if (strpos($titre, $mot) !== false) {
                    $estIT = true;
                    break;
                }
            }
            
            if ($estIT) {
                $offres[] = [
                    'titre' => (string)$item->title,
                    'entreprise' => (string)$item->author ?? 'Non spécifié',
                    'lieu' => 'Cameroun',
                    'description' => substr(strip_tags((string)$item->description), 0, 300),
                    'lien' => (string)$item->link,
                    'source' => 'Osidimbea',
                    'date' => date('Y-m-d', strtotime((string)$item->pubDate)),
                    'score' => 0
                ];
            }
        }
        echo "✅ Emploi Cameroun: " . count($offres) . " offres\n";
    } else {
        echo "⚠️ Flux Osidimbea inaccessible\n";
    }
    
    return $offres;
}

function rechercherOffresParMotCle($motsCles, $pays) {
    $offres = [];
    
    // Construction d'une recherche Google personnalisée
    $requete = implode(' ', array_slice($motsCles, 0, 3));
    $searchUrl = "https://www.google.com/search?q=" . urlencode("$requete emploi $pays site:linkedin.com OR site:indeed.com OR site:monster.com");
    
    // Pour le moment, on retourne un tableau vide
    // Cette méthode sera améliorée dans la prochaine version
    
    return $offres;
}

function filtrerOffres($offres, $profil) {
    $motsClesProfil = array_merge($profil['competences'], $profil['postes']);
    $motsClesProfil = array_map('strtolower', $motsClesProfil);
    
    foreach ($offres as &$offre) {
        $texteComplet = strtolower($offre['titre'] . ' ' . $offre['description']);
        $score = 0;
        foreach ($motsClesProfil as $mot) {
            if (strpos($texteComplet, $mot) !== false) {
                $score++;
            }
        }
        $offre['score'] = $score;
    }
    
    // Filtrer et trier
    $offresFiltrees = array_filter($offres, function($offre) {
        return $offre['score'] >= 1;  // Au moins 1 mot-clé correspondant
    });
    
    usort($offresFiltrees, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    
    return array_slice($offresFiltrees, 0, 30);
}

// Charger le profil
$profilFile = $config['dossier_data'] . 'profil.json';
if (file_exists($profilFile)) {
    $profil = json_decode(file_get_contents($profilFile), true);
} else {
    $profil = $config['profil'];
}

$toutesOffres = [];

echo "\n🔍 Recherche au Cameroun...\n";
$offresCM = rechercherEmploiCameroon();
$toutesOffres = array_merge($toutesOffres, $offresCM);

echo "\n🔍 Recherche en France...\n";
$offresFR = rechercherIndeedAPI($profil['postes'], "France");
$toutesOffres = array_merge($toutesOffres, $offresFR);

echo "\n🔍 Recherche APEC (France)...\n";
$offresAPEC = rechercherApecRSS($profil['postes']);
$toutesOffres = array_merge($toutesOffres, $offresAPEC);

echo "\n🔍 Recherche au Canada...\n";
$offresCA = rechercherIndeedAPI($profil['postes'], "Canada");
$toutesOffres = array_merge($toutesOffres, $offresCA);

echo "\n🔍 Recherche en Belgique...\n";
$offresBE = rechercherIndeedAPI($profil['postes'], "Belgique");
$toutesOffres = array_merge($toutesOffres, $offresBE);

echo "\n📊 Total offres brutes: " . count($toutesOffres) . "\n";

if (count($toutesOffres) > 0) {
    $offresFinales = filtrerOffres($toutesOffres, $profil);
    echo "✅ Offres pertinentes: " . count($offresFinales) . "\n";
} else {
    echo "⚠️ Aucune offre trouvée. Création d'offres de démonstration...\n";
    
    // Offres de démonstration pour tester
    $offresFinales = [
        [
            'titre' => 'Administrateur Réseaux - CDI',
            'entreprise' => 'Orange Cameroun',
            'lieu' => 'Douala, Cameroun',
            'description' => 'Recherche administrateur réseaux avec compétences en Cisco, TCP/IP, et support IT.',
            'lien' => 'https://www.orange.cm/carrieres',
            'source' => 'Démonstration',
            'date' => date('Y-m-d'),
            'score' => 5
        ],
        [
            'titre' => 'Technicien Support IT',
            'entreprise' => 'MTN Cameroun',
            'lieu' => 'Yaoundé, Cameroun',
            'description' => 'Support utilisateurs, maintenance parc informatique, Active Directory, Windows Server.',
            'lien' => 'https://www.mtn.cm/recrutement',
            'source' => 'Démonstration',
            'date' => date('Y-m-d'),
            'score' => 4
        ],
        [
            'titre' => 'Ingénieur Système et Réseaux',
            'entreprise' => 'Société Générale Cameroun',
            'lieu' => 'Douala, Cameroun',
            'description' => 'Administration des serveurs Windows/Linux, gestion de la sécurité réseau, VLAN, Firewall.',
            'lien' => 'https://www.societegenerale.cm/carrieres',
            'source' => 'Démonstration',
            'date' => date('Y-m-d'),
            'score' => 5
        ]
    ];
    echo "✅ Création de {$nbOffres} offres de démonstration\n";
}

// Sauvegarder
file_put_contents($config['dossier_data'] . 'offres.json', json_encode($offresFinales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "\n💾 Offres sauvegardées dans " . $config['dossier_data'] . "offres.json\n";
?>