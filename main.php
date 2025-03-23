<?php
session_start();
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    foreach ($_POST as $key) 
    {
        if ($key === '$title') 
        {

        }
    }
}

if (isset($_POST['new-board'])) 
{
    header("Location: new_board.php");
    ob_end_flush();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduBoard</title>
    <link rel="stylesheet" type="text/css" href="/includes/style.css">
    <style>
        body.desktop div#banner-div h2 {
            margin-top: 3vh;
            padding-left: 1vw;
        }
        body.desktop div#finder-div {
            margin: 8vh 0;
        }
        body.desktop div#boards-info-div h2 {
            margin-left: 0.8vw;
        }
        body.desktop div#my-boards-div button {
            height: 15vh;
            width: 15vw;
            font-size: 8vh;
            border-radius: 2vh;
            margin: 1vh 1.5vw;
        }
        body.desktop div#patterns-info-div h2 {
            margin-left: 0.8vw;
        }
        body.desktop div#my-patterns-div button {
            height: 15vh;
            width: 15vw;
            font-size: 8vh;
            border-radius: 2vh;
            margin: 1vh 1.5vw;
        }

        body.mobile div#banner-div h2 {
            margin-top: 3vw;
            margin-bottom: 1.5vw;
        }
        body.mobile div#finder-div {
            margin: 4vw 0;
        }
        body.mobile div#boards-info-div h2 {
            margin-left: 1.2vw;
        }
        body.mobile div#my-boards-div button {
            height: 13.5vw;
            width: 24vw;
            font-size: 9vw;
            border-radius: 2vw;
            margin: 2vw 3.5vw;
        }
        body.mobile div#patterns-info-div h2 {
            margin-left: 1.2vw;
        }
        body.mobile div#my-patterns-div button {
            height: 13.5vw;
            width: 24vw;
            font-size: 9vw;
            border-radius: 2vw;
            margin: 2vw 3.5vw;
        }
            .tooltip {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 5px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <script src="includes/app.js"></script>

    <header class="row spased">
        <a href="main.php"><h2>EduBoard</h2></a>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="#">Доска</a></li>
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="#">Помощь</a></li>
            </ul>
        </nav>
        <div id="user-profile-div">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") 
            {
                if (isset($_SESSION['first-name'])) 
                {
                    $first_name = $_SESSION['first-name'];
                    echo "<a href='profile.php'>$first_name</a>";
                } 
                else 
                {
                    echo "<a href='login.php'><b>Войти</b></a>";
                }   
            }
            ?>
        </div>
    </header>

    <main>
        <div class="colomn center" id="banner-div">
            <h2>Добро пожаловать на онлайн доску!</h2>
            <a href="login.php" class="a">Зарегистрируйтесь или войдите, чтобы начать.</a>
        </div>

        <div class="row center" id="finder-div">
            <input type="text" placeholder="Поиск досок">
            <button class="btn">Поиск</button>
        </div>

        <div class="colomn" id="boards-info-div">
            <h2>Ранее созданые доски</h2>
            <div class="row" id="my-boards-div">
                <form action="main.php" method="POST">
                    <button name="new-board">+</button>
                </form>
                <?php 
                if ($_SERVER["REQUEST_METHOD"] == "GET") 
                {
                    require_once "classes/db.php";
                    $db = new DataBase();
                    $email = $_SESSION['email'];
                
                    $sql = $db -> query("SELECT id FROM users WHERE email = '$email'");
                    $user_id = $db -> get_result($sql, "id");
                
                    $sql = $db -> query("SELECT * FROM boards WHERE user_id = '$user_id' ORDER BY create_at");
                    $count = 1;

                    if ($sql->num_rows > 0)
                    {
                        while($row = $sql->fetch_assoc())
                        {
                            if ($count == 5)
                            {
                                echo "<br>";
                                $count = 0;
                            }
                            echo 
                            '<form class="">
                                <button onclick="window.location.href=\'board.php\'"></button>
                                <p style="text-align:center">' . $row["title"] . '</p>
                                <span class="tooltip">' . $row["discription"] . '</span>
                            </form>';
                            $count += 1;    
                        }
                    }
                }                
                ?>
            </div>
        </div>

        <!-- <div class="colomn" id="patterns-info-div">
            <h2>Ваши шаблоны</h2>
            <div class="row" id="my-patterns-div">
            <form action="main.php" method="POST">
                    <button name="new-pattern">+</button>
                </form>
                Здесь будет динамически добавляться список шаблонов
            </div>
        </div> -->
    </main>

    <footer class="center">
        <p>© 2025 Онлайн Доска. Права защищены.</p>
    </footer>

</body>
</html>
