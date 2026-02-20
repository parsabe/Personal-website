<?php

session_start();
include_once "/home/hounaarc/abie-motlagh.hounaar.com/php/connection.php";

if(isset($_POST['id']) && isset($_POST['passcode'])){
   
    $username = mysqli_real_escape_string($connection, $_POST['id']);
    $password = mysqli_real_escape_string($connection, $_POST['passcode']);

    $query = $connection->query("SELECT * FROM users WHERE username='{$username}' AND password='{$password}'");
    if($query->num_rows > 0){  
        while($row = $query->fetch_assoc()){
            $_SESSION['anon_id'] = $row['anon_id'];
            echo "success";
        }
    } else {
        echo "نام کاربری یافت نشد. اول ثبت نام کنید.";
    }
}

?>