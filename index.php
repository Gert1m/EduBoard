<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
    if (isset($_SESSION['first-name'])) 
    {
        header("Location: main.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/includes/style.css">
    <title>EduBoard</title>
    <style>
        body.desktop main div p {
            font-size: 3.2vh;
            width: 60%;
            padding: 3vh 0;
        }
        body.desktop main h2 {
            font-size: 5.2vh;
        }
        body.desktop button {
            font-size: 3.2vh;
            padding: 0 2vw;
        }

        body.mobile main h2 {
            font-size: 5.7vw;
        }
        body.mobile main div p {
            width: 80vw;
            font-size: 3.4vw;
        }
    </style>
</head>
<body>
    <script src="includes/app.js"></script>
    <header class="row spased">
        <h2>EduBoard</h2>
        <nav>
            <ul>
                <li><a href="login.php">Вход</a></li>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="about.php">О нас</a></li>
            </ul>
        </nav>
    </header>
    <main class="center">
        <div class="colomn center">
            <h2>Онлайн-доска с интерактивным взаимодействием</h2>
            <p>
                EduBoard - это инновационный сервис для образовательных организаций, который позволяет создавать интерактивные доски для учебных целей.
            </p>
            <button class="btn" onclick="window.location.href='main.php'">Начать использование</button>
        </div>
    </main>
    <footer class="center">
        <p>© 2025 Онлайн Доска. Права защищены.</p>
    </footer>
</body>
</html>