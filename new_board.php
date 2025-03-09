<?php 
session_start();

if (isset($_POST['create'])) {
    header("Location: board.php");
        exit;
}

if (isset($_POST['undo'])) {
    header("Location: main.php");
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
        body.desktop div#form-div textarea#discription {
            height: 22vh;
            text-align: left;
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
        body.mobile div#form-div textarea#discription {
            height: 22vw;
        }
    </style>
</head>
<body class="center">
    <script src="includes/app.js"></script>

    <div class="colomn center" id="form-div">
        <h3>Создание доски<h3>
        <form action="new_board.php" method="POST">
            <input type="text" name="title" placeholder="Название доски" required>
            <textarea type="text" name="discription" placeholder="Описание к доске" id="discription"></textarea>
            <div class="row spased" id="buttons-div">
                <span></span>
                <form action="new_board.php" method="POST">
                    <button name="create" id="create">Создать</button>
                </form>
                <form action="new_board.php" method="POST">
                    <button name="undo" id="discription">Отмена</button>
                </form>
                <span></span>
            </div>
        </form>
    </div>
</body>
</html>
