<?php session_start();

// $mysqli = new mysqli('localhost', 'root','','todolist') or die(mysqli_error($mysqli));
$mysqli = new mysqli('localhost', 'root','','todolistwithlines') or die(mysqli_error($mysqli));


$name = '';
$email = '';
$task = '';
$update = false;
$id = 0;



if(isset($_GET['remove'])){
    if($_SESSION['logined']==true){
    $id = $_GET['remove'];
    $mysqli->query("DELETE FROM data_task WHERE id=$id") or die($mysqli->error);
    
    $_SESSION['message'] = "Запись удалена!";
    $_SESSION['msg_type'] = "danger";
    
    header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Вы не авторизованы!";
        $_SESSION['msg_type'] = "danger";
        header("location: auth.php"); //redirect скрытый
    }
}




if(isset($_GET['done'])){
    if($_SESSION['logined']==true){
    $id = $_GET['done'];
    $mysqli->query("UPDATE data_task SET done='1' WHERE id=$id;") or
            die($mysqli -> error);
    
    header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Вы не авторизованы!";
        $_SESSION['msg_type'] = "danger";
        header("location: auth.php"); //redirect скрытый
    }
}

if(isset($_GET['notdone'])){
    if($_SESSION['logined']==true){
    $id = $_GET['notdone'];
    $mysqli->query("UPDATE data_task SET done='0' WHERE id=$id;") or
            die($mysqli -> error);
    
    header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Вы не авторизованы!";
        $_SESSION['msg_type'] = "danger";
        header("location: auth.php"); //redirect скрытый
    }
}

if(isset($_POST['auth'])){
    $auth_password = htmlspecialchars($_POST['auth_password']);
    $auth_login = htmlspecialchars($_POST['auth_login']);
    if($auth_login=='admin'&& $auth_password==123){
        // даём права доступа администратора
        $_SESSION['auth'] = true;
        
        $_SESSION['logined'] = true;
        $_SESSION['message'] = "Добро пожаловать!";
        $_SESSION['msg_type'] = "success";
        
        header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Неправильный пароль или логин!";
        $_SESSION['msg_type'] = "danger";
        
        header("location: auth.php");
    }
}

if(isset($_GET['exit'])){
    $_SESSION['auth'] = false;
    $_SESSION['logined']=false;
    unset($_SESSION['logined']);
    header("location: index.php");
}
// сортирвки
if(isset($_POST['ASC'])){
    $field = $_POST['fields'];

    if($field==null){
        $_SESSION['log_Error'] = "Неправильный пароль или логин!";
        $_SESSION['msg_type'] = "danger";
        header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Неправильный пароль или логин!";
        $_SESSION['msg_type'] = "danger";
        header("location: index.php");
    }

    $result = $mysqli -> query("SELECT * FROM data_task ORDER BY $field ASC") or die($mysqli->error());
    $mysqli->query("TRUNCATE TABLE data_task") or
            die($mysqli -> error);
    while($row = $result -> fetch_assoc()){
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $task = $row['task'];
        $done = $row['done'];
        $changes = $row['changes'];
        $mysqli->query("INSERT INTO data_task (name, email, task, done, changes) VALUES ('$name', '$email', '$task','$done','$changes')") or
            die($mysqli -> error);
    }
    header("location: index.php");
}

if(isset($_POST['DESC'])){
    $field = $_POST['fields'];

    if($field==null){
        $_SESSION['log_Error'] = "Неправильный пароль или логин!";
        $_SESSION['msg_type'] = "danger";
        header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Неправильный пароль или логин!";
        $_SESSION['msg_type'] = "danger";
        header("location: index.php");
    }

    $result = $mysqli -> query("SELECT * FROM data_task ORDER BY $field DESC") or die($mysqli->error());
    $mysqli->query("TRUNCATE TABLE data_task") or
            die($mysqli -> error);
    while($row = $result -> fetch_assoc()){
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $task = $row['task'];
        $done = $row['done'];
        $changes = $row['changes'];
        $mysqli->query("INSERT INTO data_task (name, email, task, done, changes) VALUES ('$name', '$email', '$task','$done','$changes')") or
            die($mysqli -> error);
    }
    header("location: index.php");
}


if(isset($_POST['save'])){
    $otdel = $_POST['otdeli'];
    $doljnost = $_POST['doljnosti'];
    $slojnost = $_POST['slojnosti'];
    //тут пока что остановился чекнуть через консоль что приходит
    
    $name = htmlspecialchars($_POST['name']);
    $email =htmlspecialchars($_POST['email']);
    $task = htmlspecialchars($_POST['task']);
    if(empty($name) or empty($email)  or empty($task)){
        $_SESSION['message'] = "Введите все данные для записи!";
        $_SESSION['msg_type'] = "danger";
        
        header("location: index.php");
    } else {
        // $mysqli->query("INSERT INTO data_task (name, email, task) VALUES ('$name', '$email', '$task')") or
        //     die($mysqli -> error);
        $mysqli->query("INSERT INTO data_task ( id, name, email, task, id_otdel, id_doljenost, id_slojenosti) VALUES (NULL, '$name', '$email', '$task', '$otdel', '$doljnost', '$slojnost');") or
        die($mysqli -> error);
        // INSERT INTO `data_task` (`id`, `name`, `email`, `task`, `done`, `changes`, `id_otdel`, `id_doljenost`, `id_slojenosti`) VALUES (NULL, 'asf', 'asf', 'asf', '1', '1', '8', '1', '1');
        $_SESSION['message'] = "Запись сохранена!";
        $_SESSION['msg_type'] = "success";
    
        header("location: index.php");
    }  
}

// if(isset($_GET['remove'])){
//     if($_SESSION['logined']==true){
//     $id = $_GET['remove'];
//     $mysqli->query("DELETE FROM data_task WHERE id=$id") or die($mysqli->error);
    
//     $_SESSION['message'] = "Запись удалена!";
//     $_SESSION['msg_type'] = "danger";
    
//     header("location: index.php");
//     }else{
//         $_SESSION['log_Error'] = "Вы не авторизованы!";
//         $_SESSION['msg_type'] = "danger";
//         header("location: auth.php"); //redirect скрытый
//     }
// }

if(isset($_GET['edit'])){
    if($_SESSION['logined']==true){
   $id=$_GET['edit'];
   $update = true;
   $result = $mysqli -> query("SELECT * FROM data_task WHERE id=$id") or die($mysqli->error());
   if(count($result)==1){
       $row = $result->fetch_array();
       $id = $row['id'];
       $name = $row['name'];
       $email = $row['email'];
       $task = $row['task'];
       //изменения
       $otdel = $row['otdeli'];
       $doljnost = $row['doljnosti'];
       $slojnost = $row['slojnosti'];
   }
   }else{
       $_SESSION['log_Error'] = "Вы не авторизованы!";
       $_SESSION['msg_type'] = "danger";
       header("location: auth.php"); //redirect скрытый
   }
}


if(isset($_POST['update'])){
    if($_SESSION['logined']==true){
        $id = htmlspecialchars($_POST['id']);
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $task = htmlspecialchars($_POST['task']);
        //изменения
        $otdel = $_POST['otdeli'];
        $doljnost = $_POST['doljnosti'];
        $slojnost = $_POST['slojnosti'];
        // print "Это займет
        // несколько строк. Переводы строки тоже
        // выводятся $otdel $doljnost $slojnost ";
        // if($otdel== 'Отдел' || $doljnost== 'Должность' || $slojnosti=='Сложность' ){
        //     $_SESSION['message'] = "Введите все данные для записи!";
        //     $_SESSION['msg_type'] = "danger";
        //     header("location: index.php");
        // }
      
        //проверка меняли ли тело если меняли ставим изменения
        $result = $mysqli -> query("SELECT task FROM data_task WHERE id=$id") or die($mysqli->error());
        if(count($result)==1){
        $row = $result->fetch_array();
            $task_on_bd = $row['task'];
            if($task_on_bd!=$task){
                $mysqli->query("UPDATE data_task SET changes='1' WHERE id=$id;") or
            die($mysqli -> error);
            }
        }
        
        if($otdel== 'Отдел' || $doljnost== 'Должность' || $slojnosti=='Сложность' ){
            $_SESSION['message'] = "Введите все данные для записи!";
            $_SESSION['msg_type'] = "danger";
            header("location: index.php");
        }
        //обновление данных
        $mysqli->query("UPDATE data_task SET name='$name', email='$email', task='$task',id_otdel='$otdel', id_doljenost='$doljnost', id_slojenosti='$slojnost'  WHERE id=$id;") or
            die($mysqli -> error);
        
        $_SESSION['message'] = "Запись обновлена!";
        $_SESSION['msg_type'] = "warning";
    
        header("location: index.php"); //redirect скрытый
    }else{
        $_SESSION['log_Error'] = "Вы не авторизованы!";
        $_SESSION['msg_type'] = "danger";
        header("location: auth.php"); //redirect скрытый
    }
}