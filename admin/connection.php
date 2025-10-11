<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "toybrigade";
    $conn = "";
try{
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error($conn));
}
catch(mysqli_sql_exception){
    echo"database not open";
}
    
?>