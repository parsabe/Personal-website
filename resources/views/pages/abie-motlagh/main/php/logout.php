<?php
    session_start();
    if(isset($_SESSION['anon_id'])){
        include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";
        $id= mysqli_real_escape_string($connection, $_GET['id']);
        if(isset($id)){
                session_unset();
                session_destroy();
                header("location: /");
        }else{
            header("location: /portal/");
        }
    }else{  
        header("location: /");
    }
?>