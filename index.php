<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent IA - Recherche d'Emploi Administrateur Réseaux</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }
        
        .badge {
            background: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin: 5px;
        }
        
        .profil-section {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        
        .profil-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #667eea;
        }
        
        .offre {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .offre:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .offre h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .offre-entreprise {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .offre-lieu {
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .offre-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
            font-size: 14px;
        }
        
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.2s;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #764ba2;
        }
        
        .score {
            background: #4CAF50;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: white;
            font-size: 12px;
        }
        
        .refresh-btn {
            background: #764ba2;
            margin-top: 15px;
            display: inline-block;
        }
        
        .stats {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stats-number {
            font-size: 36px;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .offre {
                padding: 15px;
            }
            
            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🤖 Agent IA Recherche d'Emploi</h1>
            <p class="subtitle">Administrateur Réseaux & Support IT</p>
            <p>Dernière mise à jour: <?php echo date('d/m/Y à H:i'); ?></p>
            
            <?php
            // Charger le profil
            $profil = [];
            if (file_exists('data/profil.json')) {
                $profil = json_decode(file_get_contents('data/profil.json'), true);
            }
            ?>
            
            <div class="profil-section">
                <div class="profil-title">🎯 Votre profil</div>
                <div><strong>Postes recherchés:</strong> <?php echo implode(', ', array_slice($profil['postes'] ?? ['Administrateur Réseaux', 'Support IT'], 0, 5)); ?></div>
                <div><strong>Compétences clés:</strong> <?php echo implode(', ', array_slice($profil['competences'] ?? ['Réseaux', 'Windows Server', 'Linux', 'Cisco'], 0, 10)); ?></div>
            </div>
        </div>
        
        <?php
        // Charger les offres
        $offres = [];
        if (file_exists('data/offres.json')) {
            $offres = json_decode(file_get_contents('data/offres.json'), true);
        }
        ?>
        
        <div class="stats">
            <div class="stats-number"><?php echo count($offres); ?></div>
            <div>offres correspondant à votre profil</div>
        </div>
        
        <?php if (empty($offres)): ?>
            <div class="offre" style="text-align: center; padding: 50px;">
                <h3>🔍 Aucune offre trouvée pour le moment</h3>
                <p>L'agent IA recherche automatiquement chaque jour.</p>
                <p>Revenez bientôt !</p>
            </div>
        <?php else: ?>
            <?php foreach ($offres as $i => $offre): ?>
                <div class="offre">
                    <h3>
                        <?php echo $i+1; ?>. <?php echo htmlspecialchars($offre['titre'] ?? 'Titre non spécifié'); ?>
                        <?php if (isset($offre['score'])): ?>
                            <span class="score">Score: <?php echo $offre['score']; ?>/5</span>
                        <?php endif; ?>
                    </h3>
                    <div class="offre-entreprise">🏢 <?php echo htmlspecialchars($offre['entreprise'] ?? 'Non spécifié'); ?></div>
                    <div class="offre-lieu">📍 <?php echo htmlspecialchars($offre['lieu'] ?? 'Non spécifié'); ?> | 📅 <?php echo $offre['date'] ?? date('Y-m-d'); ?> | 🔗 <?php echo $offre['source'] ?? 'Source inconnue'; ?></div>
                    <div class="offre-description"><?php echo htmlspecialchars(substr($offre['description'] ?? 'Pas de description', 0, 300)); ?>...</div>
                    <a href="<?php echo htmlspecialchars($offre['lien'] ?? '#'); ?>" class="btn" target="_blank" rel="noopener noreferrer">📋 Voir l'offre</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="footer">
            <p>🔍 Agent IA développé avec ❤️ - Recherche quotidienne automatique</p>
            <p>Sources: Offres de démonstration (bientôt Indeed, Emploi.cm, LinkedIn)</p>
            <p>📧 Les offres sont mises à jour automatiquement chaque jour</p>
        </div>
    </div>
</body>
</html>