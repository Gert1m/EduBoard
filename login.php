<?php 
session_start();
ob_start();
require_once 'classes/db.php';

$db = new DataBase();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email'] = $email;

    $sql = $db -> query("SELECT password FROM users WHERE email = '$email'");

    if ($sql == "") 
    {
        $error = "Неверная почта или пароль.";
    } 
    else 
    {
        $password_in_db = $db -> get_result($sql, "password");

        if (password_verify($password, $password_in_db)) 
        {
            $sql = $db -> query("SELECT first_name FROM users WHERE email = '$email'");
            $first_name = $db -> get_result($sql, "first_name");
        } 
        else 
        {
            $error = "Неверная почта или пароль";
        }
    }

    if ($error == "") 
    {   
        $_SESSION['first-name'] = $first_name;
        header("Location: main.php");
        ob_end_flush();
        exit;
    } 
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" type="text/css" href="/includes/style.css">
    <style>
        body.desktop a {
            color: #4A90E2;
            text-decoration: none;
        }
        body.desktop div#form-div {
            width: 40vw;
            height: 60vh;
            border-radius: 2vh;
            box-shadow: 0.2vw 0.8vh 2vw 2vw rgba(0, 0, 0, 0.1);
        }
        body.desktop div#error-message-div p {
            color: red;
        }
        body.desktop form#login-form button {
            width: 10vw;
        }
        body.desktop div#form-div p {
            margin-top: 2vh;
        }

        body.mobile a {
            color: #4A90E2;
            text-decoration: none;
        }
        body.mobile div#form-div {
            width: 85vw;
            height: 90vw;
            border-radius: 2vh;
            box-shadow: 0.2vw 0.8vw 4vw 4vw rgba(0, 0, 0, 0.1);
        }
        body.mobile div#error-message-div p {
            color: red;
        }
        body.mobile form#login-form button {
            width: 22vw;
        }
        body.mobile div#form-div p {
            margin-top: 2vh;
        }
    </style>
</head>
<body class="center">
    <script src="includes/app.js"></script>
    <div class="colomn center" id="form-div">
        <h3>EduBoard<h3>
        <br>
        
        <form id="login-form" action="login.php" method="POST">
            <input type="email" id="email" placeholder="Email" name="email" required>
            <input type="password" id="password" placeholder="Пароль" name="password" required>
            <div id="error-message-div">
            <?php
            if ($error != "") 
            {
                echo "<p>$error</p>";
            }
            ?>
            </div>
            <button type="submit">Войти</button>
        </form>

        <p>Еще не зарегистрированы? <a href="register.php">Регистрация</a></p>
        <a href="index.php">На главную</a>
        <script>
            document.querySelector('#email').value = <?php echo json_encode($_SESSION['email']);?>;
        </script>
    </div>
</body>
</html>
