<?php
$title = 'Accueil'; // Titre de la page
ob_start(); // Démarre la mise en mémoire tampon
?>
<div style="display: flex; justify-content: center; gap: 30px; margin-top: 50px;">
    <!-- Lien vers la liste des équipes -->
    <div style="text-align: center;">
        <a href="?action=teams" style="text-decoration: none; color: black;">
            <div style="border: 2px solid #000; padding: 20px; border-radius: 8px;">
                <h1>Équipes</h1>
            </div>
        </a>
    </div>

    <!-- Lien vers la liste des matchs -->
    <div style="text-align: center;">
        <a href="?action=games" style="text-decoration: none; color: black;">
            <div style="border: 2px solid #000; padding: 20px; border-radius: 8px;">
                <h1>Matchs</h1>
            </div>
        </a>
    </div>
</div>
<?php
$content = ob_get_clean(); // Récupère le contenu mis en mémoire tampon
require_once 'gabarit.php'; // Inclut le gabarit
?>
