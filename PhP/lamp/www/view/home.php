<?php
$title = 'Accueil';
ob_start();
?>
<div class="row">
    <div class="col-6">
        <a href="?action=teams">
            <h1>Equipes</h1>
        </a>
    </div>
    <div class="col-6">
        <a href="?action=games">
            <h1>Matches</h1>
        </a>
    </div>
</div>
<div class="row mt-4">
    <div class="col-6">
        <a href="register.php" class="btn btn-primary btn-lg">Créer un compte</a>
    </div>
    <div class="col-6">
        <a href="login.php" class="btn btn-secondary btn-lg">Se connecter</a>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'gabarit.php';
?>
