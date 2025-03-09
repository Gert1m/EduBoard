<?php 
session_start(); 

if (isset($_POST['logout'])) {
    $_SESSION['username'] = NULL;
    header("Location: main.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/includes/profile.css">
    <title>Профиль пользователя</title>
</head>
<body>
    <script src="includes/app.js"></script>
    <form action="profile.php" method="POST">
        <button class="logout-btn" name="logout">Выйти</button>
    </form>
</body>
</html>
