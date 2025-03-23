<?php
class DataBase {
    function query($sql) {
        $connection = new mysqli("localhost", "root", "root", "EduBoard");
        $result = $connection -> query($sql);
        $connection -> close();
        
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