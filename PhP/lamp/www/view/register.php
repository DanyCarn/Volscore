<?php
require_once __DIR__ . '/../model/VolscoreDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $favorite_team = trim($_POST['favorite_team']);

    // Ajout dans la base de données
    $pdo = VolscoreDB::getConnection();
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, favorite_team) VALUES (:username, :email, :password, :favorite_team)");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'favorite_team' => $favorite_team,
    ]);

    echo "Compte créé avec succès. <a href='login.php'>Connectez-vous ici</a>";
    exit;
}
?>

<h1>Créer un compte</h1>
<form method="POST" action="register.php">
    <div>
        <label>Pseudo :</label>
        <input type="text" name="username" required>
    </div>
    <div>
        <label>Email :</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label>Équipe préférée :</label>
        <input type="text" name="favorite_team">
    </div>
    <button type="submit">Créer le compte</button>
</form>
