<?php
require_once "classes/db.php";
$db = new DataBase();
$current_data = [];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рисование</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
        }

        canvas {
            position: absolute;
            display: block;
            touch-action: none;
        }
    </style>
</head>
<body>
    <canvas id="canvas"></canvas>
    <button onclick="undo()">Отменить последнее действие</button>
    <label for="colorPicker">Цвет:</label>
    <input type="color" id="colorPicker" value="#000000">
    <label for="sizePicker">Размер:</label>
    <input type="number" id="sizePicker" value="5" min="1" max="50">
    <button onclick="saveDrawing()">Сохранить рисунок</button>

    <script>
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        let drawing = false;

        function getDistance(x1, y1, x2, y2) {
            return Math.sqrt((x2 - x1) * (x2 - x1) + (y2 - y1) * (y2 - y1));
        }

        function drawCircle(x, y) {
            context.beginPath();
            context.arc(x, y, size / 2, 0, 2 * Math.PI);
            context.fill();
        }

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        function redraw() {
            context.clearRect(0, 0, canvas.width, canvas.height);
            let data = <?php $_SESSION['data'];?>

            for (let path of data) {
                context.strokeStyle = path[0].color;
                context.fillStyle = path[0].color;
                context.lineWidth = path[0].size;
                for (let point of path) {
                    drawCircle(point.x, point.y);
                }
            }
        }
    </script>
</body>
</html>
