<?php
session_start();
ob_start();
require_once "classes/db.php";

$db = new DataBase();
$error = "";

if (isset($_POST['continue'])) 
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $role = $_POST['role'];

    $_SESSION['email'] = $email;
    
    $sql = $db -> query("SELECT first_name FROM users WHERE email = '$email'");

    if ($sql == "") 
    {
        if ($password == $confirm_password) 
        {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } 
        else 
        {
            $error = "Пароли должны совпадать";
        }
    }
    else 
    {
        $error = "Аккаунт с такой почтой уже существует";
    }

    if ($error == "") 
    {
        $db -> query("INSERT IGNORE INTO users SET email = '$email', password = '$password', role = '$role'");
        $_SESSION['role'] = $role;
        header("Location: register.php#two");
    }
}

if (isset($_POST['register'])) 
{
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $other_name = $_POST['other-name'];
    $identify_by = $_POST['identify-by'];

    $_SESSION['first-name'] = $first_name;
    $_SESSION['last-name'] = $last_name;
    $_SESSION['other-name'] = $other_name;
    $_SESSION['identify-by'] = $identify_by;

    if (!isset($_SESSION['role']))
    {
        $error = "Зарегистрируйтесь правильно!";
    }

    if (isset($_SESSION['email']))
        {
            $email = $_SESSION['email'];
        }
        else
        {
            $error = "Зарегистрируйтесь правильно!";
        }

    if ($error == "")
    {   
        $db -> query("UPDATE users SET first_name = '$first_name', last_name = '$last_name' WHERE email = '$email'");

        if (isset($_SESSION['identify-by'])) 
        {
            $db -> query("UPDATE users SET identify_by = '$identify_by' WHERE email = '$email'");
        }
        if (isset($_SESSION['other-name'])) 
        {
            $db -> query("UPDATE users SET other_name = '$other_name' WHERE email = '$email'");
        }
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
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="/includes/style.css">
    <style>
        body {
            overflow: hidden;
        }
        body.desktop a {
            color: #4A90E2; 
            text-decoration: none;
        }
        body.desktop div#form-div {
            width: 40vw;    
            height: 75vh;
            margin: 12.5vh 30vw;
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
        body.desktop div#form-div select {
            margin: 1vh 0.5vw;
            font-size: 2.3vh;
            width: 35vw;
            height: 5.5vh;
            padding: 1vh 0.8vw;
            border-radius: 0.8vh;
            border: 0.1vh solid black;
        }

        body.mobile a {
            color: #4A90E2;
            text-decoration: none;
        }
        body.mobile div#form-div {
            width: 85vw;
            height: 55vh;
            margin: 22.5vh 7.5vw;
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
        body.mobile div#form-div select {
            margin: 1vw 0.5vw;
            font-size: 3.6vw;
            width: 75vw;
            height: 7.5vw;
            padding: 0 2%;
            border-radius: 1.8vw;
            border: 0.1vw solid black;
        }
    </style>
</head>
<body class="colomn">
    <script src="includes/app.js"></script>
    <section id="one">
    <div class="colomn center" id="form-div">
        <h3>EduBoard</h3>
        <br>

        <form id="register-form-one" action="register.php"  method="POST">
            <input type="email" id="email" placeholder="Email" name="email" required>
            <input type="password" id="password" placeholder="Пароль" name="password" required>
            <input type="password" id="confirm-password" placeholder="Подтвердите пароль" name="confirm-password" required>
            <select id="role" name="role" required>
                <option value="user">Учащийся</option>
                <option value="teacher">Преподователь</option>
            </select>
            <div id="error-message-div">
            <?php
            if ($error != "") 
            {
                echo "<p>$error</p>";
            }
            ?>
            </div>
            <button name="continue" id="continue">Продолжить</button>
        </form>
        
        <p>Уже зарегистрированы? <a href="login.php">Вход</a></p>
        <a href="index.php">На главную</a>
    </div>
    </section>
    <section id="two">
    <div class="colomn center" id="form-div">
        <h3>EduBoard</h3>
        <br>

        <form id="register-form-two" action="register.php"  method="POST">
            <input type="text" id="first-name" placeholder="Имя" name="first-name" required>
            <input type="text" id="last-name" placeholder="Фамилия" name="last-name" required>
            <input type="text" id="other-name" placeholder="Отчество (при наличии)" name="other-name">
            <?php
            if (isset($_SESSION['role']))
            {
                if ($_SESSION['role'] == 'teacher') 
                {
                    echo '<input type="text" id="identify-by" placeholder="Номер трудовой книжки" name="identify-by">';
                } 
                else
                {
                    echo '<input type="text" id="identify-by" placeholder="Номер студенческого билета" name="identify-by">';
                }
            }
            else 
            {
                echo '<input type="text" id="identify-by" placeholder="Номер студенческого билета" name="identify-by">';
            }
            
            ?>
            <button name="register" id="register">Зарегистрироваться</button>
        </form>
        
        <p>Уже зарегистрированы? <a href="login.php">Вход</a></p>
        <a href="index.php">На главную</a>
    </div>
    </section>
    
    <script>
        document.querySelector('#email').value = <?php echo json_encode($_SESSION['email']);?>;
    </script>
</body>
</html>
