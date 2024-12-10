<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="/node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/gamesheet.css">
</head>
<body>
<header class="text-center">
    <a href="/" style="text-decoration:none;"><h1>VolScore</h1></a>
</header>
<div class="container">
<?= $content ?>
</div>
<footer class="text-center">
    <p>© ETML 2023</p>
</footer>
</body>
</html>
