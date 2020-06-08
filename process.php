<?php session_start();

$mysqli = new mysqli('localhost', 'root','','todolist') or die(mysqli_error($mysqli));

$name = '';
$email = '';
$task = '';
$update = false;
$id = 0;

if(isset($_POST['save'])){
    
    $name = htmlspecialchars($_POST['name']);
    $email =htmlspecialchars($_POST['email']);
    $task = htmlspecialchars($_POST['task']);
    if(empty($name) or empty($email)  or empty($task)){
        $_SESSION['message'] = "Invalid record. Please enter the correct data!";
        $_SESSION['msg_type'] = "danger";
        
        header("location: index.php");
    } else {
        $mysqli->query("INSERT INTO data_task (name, email, task) VALUES ('$name', '$email', '$task')") or
            die($mysqli -> error);
    
        $_SESSION['message'] = "Record has been saved!";
        $_SESSION['msg_type'] = "success";
    
        header("location: index.php");
    }  
}

if(isset($_GET['remove'])){
    if($_SESSION['logined']==true){
    $id = $_GET['remove'];
    $mysqli->query("DELETE FROM data_task WHERE id=$id") or die($mysqli->error);
    
    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['msg_type'] = "danger";
    
    header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "You are not authorized!";
        $_SESSION['msg_type'] = "danger";
        header("location: auth.php"); //redirect скрытый
    }
}
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
    }
    }else{
        $_SESSION['log_Error'] = "You are not authorized!";
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
        
        //обновление данных
        $mysqli->query("UPDATE data_task SET name='$name', email='$email', task='$task' WHERE id=$id;") or
            die($mysqli -> error);
        
        $_SESSION['message'] = "Record has been updated!";
        $_SESSION['msg_type'] = "warning";
    
        header("location: index.php"); //redirect скрытый
    }else{
        $_SESSION['log_Error'] = "You are not authorized!";
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
        $_SESSION['log_Error'] = "You are not authorized!";
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
        $_SESSION['log_Error'] = "You are not authorized!";
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
        $_SESSION['message'] = "Welcome!";
        $_SESSION['msg_type'] = "success";
        
        header("location: index.php");
    }else{
        $_SESSION['log_Error'] = "Wrong password or login!";
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

if(isset($_POST['ASC'])){
    $field = $_POST['fields'];
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

