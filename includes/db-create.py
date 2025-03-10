# pip install pymysql
import pymysql.cursors

connection = pymysql.connect(host='103.88.241.91', user='mysql', password='mysql', database='eduboard', cursorclass=pymysql.cursors.DictCursor)

with connection:
    with connection.cursor() as cursor:
        sql = """CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(255) NOT NULL,
                    last_name VARCHAR(255) NOT NULL,
                    other_name VARCHAR(255) DEFAULT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    role VARCHAR(255) NOT NULL,
                    identify_by VARCHAR(255) DEFAULT NULL,
                    verify TINYINT(1) DEFAUNT '0'
                ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"""

        cursor.execute(sql)

        sql = """CREATE TABLE IF NOT EXISTS boards (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            user_id INT NOT NULL,
                            create_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            data LONGTEXT NOT NULL,
                            description VARCHAR(255) DEFAULT NULL
                        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"""

        cursor.execute(sql)

    connection.commit()
