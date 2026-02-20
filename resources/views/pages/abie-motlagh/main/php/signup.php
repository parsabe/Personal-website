<?php

session_start();
include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";

if(isset($_POST['username']) && isset($_POST['user-email']) && isset($_POST['password'])){
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['user-email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $date = date("Y-m-d H:i:s");

    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        $query = $connection->query("SELECT * FROM users WHERE username = '{$username}' AND email = '{$email}'");
        if($query->num_rows>0){
            echo " Username or email already exists";
        } else {
             
            $anon_id = rand(time(),100000000);
            $insert = $connection->query("INSERT INTO users VALUES ('{$anon_id}','{$username}','{$email}','{$password}','{$date}')");
            if($insert){
                $checker = $connection->query("SELECT * FROM users WHERE username = '{$username}' AND email = '{$email}' AND password = '{$password}'");
                if($checker->num_rows > 0){
                    while($row = $checker->fetch_assoc()){
                        $_SESSION['anon_id'] = $row['anon_id'];
                        echo "success";
                    }
                 } else {
                    echo "Something went wrong";
                }
            } else {
                echo "Something is blocking the server";
            }
        }
    } else {
        echo "Please enter a valid email";
    }
}
        

?>