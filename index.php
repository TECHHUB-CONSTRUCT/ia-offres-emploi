<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent IA - Recherche d'Emploi</title>
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
        
        .offre {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: transform 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .offre:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .offre h3 {
            color: #333;
            margin-bottom: 10px;
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
        }
        
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.2s;
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
        }
        
        .refresh-btn {
            background: #764ba2;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .offre {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🤖 Agent IA Recherche d'Emploi</h1>
            <p>Dernière mise à jour: <?php echo date('d/m/Y à H:i'); ?></p>
            
            <?php
            // Charger le profil
            $profil = [];
            if (file_exists('data/profil.json')) {
                $profil = json_decode(file_get_contents('data/profil.json'), true);
            }
            ?>
            
            <div class="profil-section">
                <strong>🎯 Votre profil:</strong><br>
                Postes: <?php echo implode(', ', array_slice($profil['postes'] ?? $config['profil']['postes'] ?? [], 0, 5)); ?><br>
                Compétences clés: <?php echo implode(', ', array_slice($profil['competences'] ?? $config['profil']['competences'] ?? [], 0, 10)); ?>
            </div>
            
            <a href="cron_jobs.php" class="btn refresh-btn" style="display: inline-block; margin-top: 15px;">🔄 Lancer une recherche manuelle</a>
        </div>
        
        <h2 style="color: white; margin-bottom: 15px;">📊 Offres pertinentes</h2>
        
        <?php
        // Charger les offres
        $offres = [];
        if (file_exists('data/offres.json')) {
            $offres = json_decode(file_get_contents('data/offres.json'), true);
        }
        
        if (empty($offres)): ?>
            <div class="offre" style="text-align: center; padding: 50px;">
                <h3>Aucune offre trouvée pour le moment</h3>
                <p>Cliquez sur "Lancer une recherche manuelle" pour rechercher des offres.</p>
            </div>
        <?php else: ?>
            <?php foreach ($offres as $i => $offre): ?>
                <div class="offre">
                    <h3><?php echo $i+1; ?>. <?php echo htmlspecialchars($offre['titre'] ?? 'Titre non spécifié'); ?>
                        <span class="score">Score: <?php echo $offre['score'] ?? 0; ?></span>
                    </h3>
                    <div class="offre-entreprise">🏢 <?php echo htmlspecialchars($offre['entreprise'] ?? 'Non spécifié'); ?></div>
                    <div class="offre-lieu">📍 <?php echo htmlspecialchars($offre['lieu'] ?? 'Non spécifié'); ?> | 📅 <?php echo $offre['date'] ?? 'Date non spécifiée'; ?> | 🔗 <?php echo $offre['source'] ?? 'Source inconnue'; ?></div>
                    <div class="offre-description"><?php echo htmlspecialchars(substr($offre['description'] ?? 'Pas de description', 0, 200)); ?>...</div>
                    <a href="<?php echo htmlspecialchars($offre['lien'] ?? '#'); ?>" class="btn" target="_blank">Voir l'offre</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="footer">
            <p>🔍 Agent IA développé avec ❤️ - Recherche quotidienne automatique</p>
            <p>Sources: Indeed RSS, Emploi.cm</p>
        </div>
    </div>
</body>
</html>