# pip install pymysql
import pymysql.cursors

connection = pymysql.connect(host='127.0.0.1', user='mysql', password='mysql', database='eduboard', cursorclass=pymysql.cursors.DictCursor)

with connection:
    with connection.cursor() as cursor:
        sql = """CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    role VARCHAR(255) NOT NULL
                ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"""

        cursor.execute(sql)

        sql = """CREATE TABLE IF NOT EXISTS boards (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            user_id INT NOT NULL,
                            create_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"""

        cursor.execute(sql)

    connection.commit()
