<?php
if (isset($_POST['img'])) {
    $data = $_POST['img'];
    var_dump($data);
    $data = str_replace('data:image/png;base64,', '', $data);
    $data = str_replace(' ', '+', $data);
    $imgData = base64_decode($data);

    $file = 'images/drawing_' . time() . '.png';
    if (file_put_contents($file, $imgData)) {
        echo "Рисунок сохранен";
    } else {
        echo "Ошибка сохранения рисунка";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Рисование</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 90%;
            height: 90%;
        }
        canvas {
            display: block;
        }
    </style>
</head>
<body>
    <script src="includes/app.js"></script>
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
        let paths = JSON.parse(localStorage.getItem('paths')) || [];
        let currentPath = [];
        let color = document.getElementById('colorPicker').value;
        let size = document.getElementById('sizePicker').value;

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
            for (let path of paths) {
                context.strokeStyle = path[0].color;
                context.fillStyle = path[0].color;
                context.lineWidth = path[0].size;
                for (let point of path) {
                    drawCircle(point.x, point.y);
                }
            }
        }

        canvas.addEventListener('mousedown', (e) => {
            drawing = true;
            currentPath = [];
            context.strokeStyle = color;
            context.fillStyle = color;
            context.lineWidth = size;
            drawCircle(e.offsetX, e.offsetY);
            currentPath.push({ x: e.offsetX, y: e.offsetY, color: color, size: size });
        });

        canvas.addEventListener('mousemove', (e) => {
            if (drawing) {
                const lastPoint = currentPath[currentPath.length - 1];
                const distance = getDistance(lastPoint.x, lastPoint.y, e.offsetX, e.offsetY);
                const steps = Math.ceil(distance / size * 2);
                for (let i = 1; i <= steps; i++) {
                    const x = lastPoint.x + (e.offsetX - lastPoint.x) * (i / steps);
                    const y = lastPoint.y + (e.offsetY - lastPoint.y) * (i / steps);
                    drawCircle(x, y);
                    currentPath.push({ x: x, y: y, color: color, size: size });
                }
            }
        });

        canvas.addEventListener('mouseup', () => {
            if (drawing) {
                paths.push(currentPath);
                drawing = false;
            }
        });

        canvas.addEventListener('mouseout', () => {
            drawing = false;
        });

        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && (e.key === 'z' || e.key === "я")) {
                undo();
            }
        });

        document.getElementById('colorPicker').addEventListener('change', (e) => {
            color = e.target.value;
        });

        document.getElementById('sizePicker').addEventListener('change', (e) => {
            size = e.target.value;
        });

        function undo() {
            if (paths.length > 0) {
                paths.pop();
                localStorage.setItem('paths', JSON.stringify(paths));
                redraw();
            }
        }

        function saveDrawing() {
            const dataURL = canvas.toDataURL('image/png');
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'save.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('img=' + encodeURIComponent(dataURL));
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Рисунок сохранен');
                } else {
                    alert('Ошибка сохранения рисунка');
                }
            };
        }

        window.addEventListener('resize', resizeCanvas);
        window.addEventListener('load', resizeCanvas);
        window.addEventListener('load', redraw);
    </script>
</body>
</html>
