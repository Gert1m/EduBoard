<?php
class DataBase {
    function query($sql) {
        $connection = new mysqli("127.0.0.1", "mysql", "mysql", "EduBoard");
        $result = $connection -> query($sql);

        if ($result -> num_rows > 0) 
        {
            return $result;
        } 
        else 
        {
            return "";
        }

        $connection -> close();
    }


    function get_result($query_result, $value_name) {
        $result = mysqli_fetch_assoc($query_result);
        // var_dump($result);
        return $result[$value_name];
    }
}
?>