<?php

session_start();
if(!isset($_SESSION['anon_id'])){
    header("location: /");
}


?>
<!DOCTYPE html>
<html lang="en-fa-IR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="پورتال - آبی مطلق">
    <meta name="description" content="">
    <meta name="theme-color" content="#090979">
    <link rel="canonical" href="https://abie-motlagh.com">
    <link rel="icon" href="/main/pic/logo/mainlogo.png">
    <link rel="stylesheet" href="/main/css/main.css">
    <link rel="stylesheet" href="/main/css/portal.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="ttps://cdnjs.cloudflare.com/ajax/libs/particlesjs/2.2.2/particles.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" crossorigin="" src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script type="text/javascript" crossorigin="" src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script type="text/javascript" crossorigin="" src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script type="text/javascript" cross="" src="https://cdn.jsdelivr.net/npm/tsparticles@1/tsparticles.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tsparticles-preset-bubbles@1/tsparticles.preset.bubbles.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <title>پورتال  - آبی مطلق</title>
</head>
<body>
<?php 
include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/connection.php";
?>
<header id="header" class="fixed-top">
        <div class="container d-flex align-items-center justify-content-between animate__animated animate__fadeInDown">
            <nav id="navbar" class="navbar">
                
                    <ul>
                       
                        <li><a href="/main/php/logout.php?id=<?php echo $_SESSION['anon_id'];?>" class="navlink scrollto" >خروج</a></li>
                        <li><a href="/portal/order/" class="navlink scrollto"  >ثبت سفارش</a></li>
                        <li><a href="/portal/" class="navlink scrollto" >پروفایل</a></li>

                        

                    </ul>

                    <i id="b-lists" class="bi bi-list mobile-nav-toggle" data-bs-toggle="modal" data-bs-target="#menu"></i>
            </nav>
                <a href=""><img src="/main/pic/logo/logo.png" alt="" width="200" height="100"></a>

        </div>
    </header>  
    <br/>
    <br/>
    <br/>
    <br/>   <br/>
    <br/>   <br/>
 <br/>
    

 <?php 
 
 $anon_id = $_SESSION['anon_id'];
 $query = $connection->query("SELECT * FROM users WHERE anon_id = '{$anon_id}'");
 if($query->num_rows>0){
    while($row = $query->fetch_assoc()){

    
 
 ?>
    <div class="container" id="container1">
        <div class="row">
            <div class="col">
                <h3>اطلاعات کاربری</h3>
                <hr/>
            </div>
        </div>

        <form action="" class="form-group">
            <h6>شناسه</h6>
            <input type="text" class="form-control" readonly placeholder="<?php echo $row['anon_id'];?>"><br/>
            <h6>نام کاربری</h6>
            <input type="text" class="form-control" readonly placeholder="<?php echo $row['username'];?>"><br/>
            <h6>ایمیل</h6>
            <input type="text" class="form-control" readonly placeholder="<?php echo $row['email'];?>"><br/>
            <?php } }?>
        </form>
        
    </div>

    <?php include_once "/home/hounaarc/abie-motlagh.hounaar.com/main/php/footer.php"; ?>

    <div class="modal fade" id="menu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                       
                        <li><a href="" class="navlink scrollto" data-bs-toggle="modal" data-bs-target="#login">
                            <i class="fa fa-user mt-3" aria-hidden="true"></i>
                       </a></li>
                  
                       <li><a href="/main/php/logout.php?id=<?php echo $_SESSION['anon_id'];?>" class="navlink scrollto" >خروج</a></li>
                        <li><a href="/portal/order/" class="navlink scrollto"  >ثبت سفارش</a></li>
                        <li><a href="/portal/" class="navlink scrollto" >پروفایل</a></li>

                    </ul>
                </div>

            </div>
        </div>
    </div>
</body>
</html>