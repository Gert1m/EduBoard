<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рисование</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f0f0f0;
        }
        .toolbar {
            display: flex;
            gap: 10px;
            margin: 10px 0;
            z-index: 1;
        }
        .toolbar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .toolbar button:hover {
            background-color: #45a049;
        }
        .toolbar input[type="color"], .toolbar input[type="number"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            border: none;
            background-color: white;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="undo()">Отмена</button>
        <label>Цвет: <input type="color" value="#000000" id="colorPicker"></label>
        <label>Размер: <input type="number" value="5" min="1" max="20" id="sizePicker"></label>
        <button onclick="saveDrawing()">Сохранить</button>
    </div>

    <canvas id="canvas"></canvas>

    <script>
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        let drawing = false;
        let savedImage = null;
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
            saveCanvas(); // Сохраняем содержимое перед изменением размера
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            restoreCanvas(); // Восстанавливаем содержимое после изменения размера
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

        function saveCanvas() {
            savedImage = canvas.toDataURL(); // Сохраняем содержимое canvas
        }

        function restoreCanvas() {
            if (savedImage) {
                const img = new Image();
                img.src = savedImage;
                img.onload = () => {
                    context.drawImage(img, 0, 0); // Перерисовываем canvas
                };
            }
        }

        canvas.addEventListener('mousedown', (mouse) => {
            drawing = true;
            currentPath = [];
            context.strokeStyle = color;
            context.fillStyle = color;
            context.lineWidth = size;
            drawCircle(mouse.offsetX, mouse.offsetY);
            currentPath.push({ x: mouse.offsetX, y: mouse.offsetY, color: color, size: size });
        });

        canvas.addEventListener('touchstart', (mouse) => {
            drawing = true;
            currentPath = [];
            context.strokeStyle = color;
            context.fillStyle = color;
            context.lineWidth = size;
            drawCircle(mouse.offsetX, mouse.offsetY);
            currentPath.push({ x: mouse.offsetX, y: mouse.offsetY, color: color, size: size });
        });

        canvas.addEventListener('mousemove', (mouse) => {
            if (drawing) {
                const lastPoint = currentPath[currentPath.length - 1];
                const distance = getDistance(lastPoint.x, lastPoint.y, mouse.offsetX, mouse.offsetY);
                const steps = Math.ceil(distance / size * 2);
                for (let i = 1; i <= steps; i++) {
                    const x = lastPoint.x + (mouse.offsetX - lastPoint.x) * (i / steps);
                    const y = lastPoint.y + (mouse.offsetY - lastPoint.y) * (i / steps);
                    drawCircle(x, y);
                    currentPath.push({ x: x, y: y, color: color, size: size });
                }
            }
        });

        canvas.addEventListener('touch', (mouse) => {
            if (drawing) {
                const lastPoint = currentPath[currentPath.length - 1];
                const distance = getDistance(lastPoint.x, lastPoint.y, mouse.offsetX, mouse.offsetY);
                const steps = Math.ceil(distance / size * 2);
                for (let i = 1; i <= steps; i++) {
                    const x = lastPoint.x + (mouse.offsetX - lastPoint.x) * (i / steps);
                    const y = lastPoint.y + (mouse.offsetY - lastPoint.y) * (i / steps);
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

        canvas.addEventListener('touchend', () => {
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

        // function saveDrawing() {
        //     const pathsData = JSON.stringify(paths); // Преобразуем paths в JSON
        //     fetch('save_paths.php', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json'
        //         },
        //         body: JSON.stringify({ paths: pathsData }) // Отправляем paths
        //     })
        //     .then(response => response.text())
        //     .then(data => {
        //         alert(data); // Выводим ответ от сервера
        //     })
        //     .catch(error => {
        //         console.error('Ошибка:', error);
        //     });
        // }



        function saveDrawingToLocalStorage() {
            localStorage.setItem('paths', JSON.stringify(paths));
        }

        function loadDrawingFromLocalStorage() {
            const savedPaths = JSON.parse(localStorage.getItem('paths'));
            if (savedPaths) {
                paths = savedPaths;
                redraw();
            }
        }

        window.addEventListener('resize', resizeCanvas);
        window.addEventListener('load', resizeCanvas);
        window.addEventListener('load', redraw);
        window.addEventListener('load', loadDrawingFromLocalStorage);
        window.addEventListener('beforeunload', saveDrawingToLocalStorage);
    </script>
</body>
</html>