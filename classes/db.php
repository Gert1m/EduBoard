<?php
class DataBase {
    function query($sql) {
        $connection = new mysqli("localhost", "mysql", "mysql", "eduboard");
        $result = $connection -> query($sql);
        $connection -> close();

        // echo "$sql ---> ";
        // var_dump($result);
        // echo "<br>";
        
        if ($connection->connect_error) {
            die("Ошибка подключения: " . $connection->connect_error);
        }
        
        if (is_bool($result))
        {
            return $result;
        }
        elseif ($result -> num_rows > 0) 
        {
            return $result;
        } 
        else 
        {
            return "";
        }
    }


    function get_result($query_result, $value_name) {
        $result = mysqli_fetch_assoc($query_result);
        // var_dump($result);
        return $result[$value_name];
    }
}
?>