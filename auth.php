<?php
require_once 'process.php';
?>
<html>
    <head>
        <title>Авторизация</title>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>
    <body>
        
        
    <?php
if (isset($_SESSION['log_Error'])):
?>
       
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                
                <?php
    echo $_SESSION['log_Error'];
    unset($_SESSION['log_Error']);
?>
           </div>
        <?php
endif;
?>
 
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">DO IT</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <!-- <a class="nav-link" href="https://github.com/Sparow2021">Github code<span class="sr-only">(current)</span></a> -->
                    </li>
                </ul>
                <button class="btn btn-outline-success my-2 my-sm-0" type="button"><a style="color: white !important; text-decoration:none;" href="index.php">Главная</a></button>
            </div>
    </nav>      
        
 <div class="container" style="margin-top:20px;"> 
    <form action="process.php" method="POST">
    <div class="form-group">
        <label for="exampleInputLogin">Логин</label>
        <input type="text" class="form-control" id="exampleInputLogin"  placeholder="Введите логин" name="auth_login">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Пароль</label>
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Введите пароль" name="auth_password">
    </div>
    <button type="submit" class="btn btn-primary" name="auth">Подтвердить</button>
    </form>
</div>
</body>
</html>