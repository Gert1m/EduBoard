<?php 
session_start();
ob_start();
$error = "";
require_once "classes/db.php";
$db = new DataBase();

if (isset($_POST['create'])) 
{   
    $title = strtolower($_POST['title']);
    $description = $_POST['description'];
    $email = $_SESSION['email'];

    $sql = $db -> query("SELECT id FROM users WHERE email = '$email'");
    if (isset($_SESSION['first-name']))
    {
        $user_id = $db -> get_result($sql, "id");
        
    }
    else
    {
        $error = "Чтобы создать доску необходимо авторизоваться";
    }
    
    if (($db -> query("SELECT id FROM boards WHERE title = '$title' AND user_id = '$user_id'")) != "")
    {
        $error = "Доска с таким именем уже существует";
    }
    else
    {
        $db -> query("INSERT INTO boards SET title = '$title', user_id = $user_id");
        if ($_POST['description'] != "")
        {
            $db -> query("UPDATE boards SET description = '$description' WHERE title = '$title' AND user_id = '$user_id'");
        }
    }

    if ($error == "")
    {
        header("Location: board.php?" . http_build_query(["title" => $title, "user_id" => $user_id]));
        ob_end_flush(); 
        exit;
    }
}

if (isset($_POST['undo'])) 
{
    header("Location: main.php");
    ob_end_flush(); 
    exit;
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
        body.desktop div#form-div {
            width: 40vw;
            height: 60vh;
            border-radius: 2vh;
            box-shadow: 0.2vw 0.8vh 2vw 2vw rgba(0, 0, 0, 0.1);
        }
        body.desktop div#buttons-div button {
            width: 10vw;
        }
        body.desktop div#form-div textarea#description {
            height: 22vh;
            text-align: left;
        }
        body.desktop div#form-div p {
            color: red;
        }

        body.mobile div#form-div {
            width: 85vw;
            height: 90vw;
            border-radius: 2vh;
            box-shadow: 0.2vw 0.8vw 4vw 4vw rgba(0, 0, 0, 0.1);
        }
        body.mobile div#buttons-div button {
            width: 22vw;
        }
        body.mobile div#form-div textarea#description {
            height: 22vw;
        }
        body.mobile div#form-div p {
            color: red;
        }
    </style>
</head>
<body class="center">
    <script src="includes/app.js"></script>

    <div class="colomn center" id="form-div">
        <h3>Создание доски<h3>
        <?php
        if ($error != "") 
        {
            echo "<p>$error</p>";
        }
        ?>
        <form action="new_board.php" method="POST">
            <input type="text" name="title" placeholder="Название доски" required>
            <textarea type="text" name="description" placeholder="Описание к доске" id="description"></textarea>
            <div class="row spased" id="buttons-div">
                <span></span>
                <form action="new_board.php" method="POST">
                    <button name="create" id="create">Создать</button>
                </form>
                <form action="new_board.php" method="POST">
                    <button name="undo" id="undo">Отмена</button>
                </form>
                <span></span>
            </div>
        </form>
    </div>
</body>
</html>
