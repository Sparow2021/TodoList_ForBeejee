<?php
require_once 'process.php';
?>
<html>
    <head>
        <title>Главная</title>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
    </head>
    <body>
        <!--всплывающие сообщения-->
        <?php
if (isset($_SESSION['message'])):
?>
       
            <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                
                <?php
    echo $_SESSION['message'];
    unset($_SESSION['message']);
?>
           </div>
        <?php
endif;
?>
       
        
        
        
        
               <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <a class="navbar-brand" href="#" style="margin-left: 30px">DO IT</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                           <li class="nav-item active">
                            <a class="nav-link" href="https://github.com/Sparow2021">Github code<span class="sr-only">(current)</span></a>
                           </li>
                        </ul>
                        <?php
if ($_SESSION['logined'] == true):
?>
                       <button class="btn btn-outline-danger my-2 my-sm-0" type="button"><a style="color: white !important; text-decoration:none;" href="process.php?exit">Выход</a></button>
                        <?php
else:
?>
                       <button class="btn btn-outline-success my-2 my-sm-0" type="button"><a style="color: white !important; text-decoration:none;" href="auth.php">Авторизация</a></button>
                        <?php
endif;
?>
                   </div>
                 </nav>
        
   <h1 style="text-align: center; margin-top:20px; margin-bottom: 40px">Таблица тасков</h1>    

        

        <?php
$mysql = new mysqli('localhost', 'root', '', 'todolist') or die(mysqli_error($mysqlo));
$result = $mysqli->query("SELECT * FROM data_task") or die($mysqli->error);
?>

        <!--НАЧАЛО ПАГИНАЦИИ-->
        <?php
        
$limit    = isset($_POST["limit-records"]) ? $_POST["limit-records"] : 3;
$page     = isset($_GET['page']) ? $_GET['page'] : 1;
$start    = ($page - 1) * $limit;
$result   = $mysqli->query("SELECT * FROM data_task LIMIT $start, $limit");
$tasksAll = $result->fetch_all(MYSQLI_ASSOC);
$result1 = $mysqli->query("SELECT count(id) AS id FROM data_task") or die($mysqli->error());
$custCount = $result1->fetch_all(MYSQLI_ASSOC);
$total     = $custCount[0]['id'];
$pages     = ceil($total / $limit);

$Previous = $page - 1;
$Next     = $page + 1;
?> 
<div class="container well" style="margin-top:20px;">
 <div class="row">
   <div class="col-md-10">
      
    <!--навигация по пагинации-->
    <nav aria-label="Page navigation example">
     <ul class="pagination">
         <?php if($page==1) :?>
             <a class="page-link" href="index.php?page=1" aria-label="Previous">
                <span aria-hidden="true">« Пред.</span>
          </a>
         <?php else: ?>
        <li>
          <a class="page-link" href="index.php?page=<?= $Previous; ?>" aria-label="Previous">
            <span aria-hidden="true">« Пред.</span>
          </a>
        </li>
        <?php endif; ?>
        <?php
for ($i = 1; $i <= $pages; $i++):
    if ($i == $page) {
        $active = "class='page-item active'";
    } else {
        $active = "";
    }
?>
       <li <?php
    echo $active;
?>><a class="page-link" href="index.php?page=<?= $i; ?>"><?= $i; ?></a></li>
        <?php
endfor;
?> 
              
       <?php if($page==($pages)) :?>
         <li >
            <a class="page-link" href="index.php?page=1" aria-label="Next">
            <span aria-hidden="true">Первая</span>
            </a>
        </li> 
       <?php else:?>   
        <li >
          <a class="page-link" href="index.php?page=<?= $Next; ?>" aria-label="Next">
            <span aria-hidden="true">След. »</span>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
   </div> 
     
 <!--пагинация-->
 </div>
 <div style="overflow-y: auto;">
   <table id="" class="table  table-bordered">
          <thead>
                 <tr>                      
                        <th>Имя</th>
                        <th>Сложность</th>
                        <th>Должность</th>
                        <th>Отдел</th>
                        <th>Почта</th>
                        <th>Задание</th>
                        <th>Статус задания</th>
                        

                        <?php
if ($_SESSION['auth'] == true):
?>
                           <th colspan="3">Действия</th>
                        <?php
else:
?>
                           <th>Правки админом</th>
                        <?php
endif;
?>
                        
               </tr>
            </thead>
          
           <?php
//           !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
foreach ($tasksAll as $rowS): 
?>
             <?php
    if ($rowS['done'] == 1):
?>
                   <?php
        echo "<tr bgcolor='LightGreen'>";
?>
               <?php
    else:
?>
                   <?php
        echo "<tr>";
?>
               <?php
    endif;
?>
                   <td><?php
    echo $rowS['name'];
?></td>
                    <td><?php
                    switch ($rowS['id_slojenosti']) {
                        case "1":
                            echo "легко";
                            break;
                        case "2":
                            echo "средней сложности";
                            break;
                        case "3":
                            echo "сложно";
                            break;
                        case "4":
                            echo "очень сложно";
                            break;
                    }
                    
    // echo $rowS['id_slojenosti'];
?></td>
                    <td><?php
                    switch ($rowS['id_doljenost']) {
                        case "1":
                            echo "junior";
                            break;
                        case "2":
                            echo "middle";
                            break;
                        case "3":
                            echo "senior";
                            break;
                        case "4":
                            echo "team lead";
                            break;
                    }
    // echo $rowS['id_doljenost'];
?></td>
                    <td><?php
                    switch ($rowS['id_otdel']) {
                        case "7":
                            echo "FRONTEND";
                            break;
                        case "8":
                            echo "BACKEND";
                            break;
                        case "9":
                            echo "CEO";
                            break;
                    }
    // echo $rowS['id_otdel'];
?></td>

                    <td><?php
    echo $rowS['email'];
?></td>
                    <td><?php
    echo $rowS['task'];
?></td>
                    <td><?php
    if ($rowS['done'] == 1) {
        echo 'Готово';
    } else {
        echo 'В процессе';
    };
?></td>
                    <?php
    if ($_SESSION['auth'] == true):
?>
                   <td>
                        <a href ="index.php?edit=<?php
        echo $rowS['id'];
?>"
                           class="btn btn-info" style="margin-top: 5px">Изменить</a>
                        <a href="process.php?remove=<?php
        echo $rowS['id'];
?>"
                           class="btn btn-danger" style="margin-top: 5px">Удалить</a>
                        <?php
        if ($rowS['done'] == 0):
?>
                       <a href="process.php?done=<?php
            echo $rowS['id'];
?>"
                           class="btn btn-success" style="margin-top: 5px">Готово</a>
                        <?php
        else:
?>
                       <a href="process.php?notdone=<?php
            echo $rowS['id'];
?>"
                           class="btn btn-warning" style="margin-top: 5px">Отменить</a>    
                        <?php
        endif;
?>
                   </td>
                  
                    <?php
    else:
?> 
                       <?php
        if ($rowS['changes'] == 1):
?>
                           <td>Изменено админом.</td>
                       <?php
        else:
?>
                           <td>Не изменялось</td>
                       <?php
        endif;
?>
                   <?php
    endif;
?>
               <?php
    echo "</tr>";
?>
          <?php
endforeach;
?>
       </table>
  </div>
</div>
   <!--сортировка-->
   <h1 style="text-align: center; margin-top:20px; margin-bottom: 40px">Сортировка</h1>    
<div class="container" style="margin-top:20px;">
    <form action="process.php" method="POST">
    <div class="row"> 
    
        <div class="col-sm">
        <input  type="submit" name="ASC" value="По возрастанию"><br><br>
        </div>
        <div class="col-sm">
        <input  type="submit" name="DESC" value="По убыванию"><br><br>
        </div>
        <div class="col-sm">
        <select class="custom-select" name="fields">
            <option selected>Выберите поле</option>
            <option value="name">Имя</option>
            <option value="email">Почта</option>
            <option value="done">Статус задания</option>
        </select>
        </div>
    </div>
    </form>

<!-- пишем расширение -->
<!-- <div class="container" style="margin-top:20px;">
    <form action="process.php" method="POST">
<div class="row"> 
    <div class="col-sm">
            <select class="custom-select" name="fields">
                <option selected>Выберите отдел</option>
                <option value="name">Имя</option>
                <option value="email">Почта</option>
                <option value="done">Статус задания</option>
            </select>
    </div>
    <div class="col-sm">
            <select class="custom-select" name="fields">
                <option selected>Выберите сложность</option>
                <option value="name">Имя</option>
                <option value="email">Почта</option>
                <option value="done">Статус задания</option>
            </select>
    </div>
    <div class="col-sm">
            <select class="custom-select" name="fields">
                <option selected>Выберите должность</option>
                <option value="name">Имя</option>
                <option value="email">Почта</option>
                <option value="done">Статус задания</option>
            </select>
    </div>
</div>
    </form> -->

<!-- законченное написание расширения -->
</div>       
      <!--конец сортировки-->  
   <h1 style="text-align: center; margin-top:20px; margin-bottom: 40px">Работа с записью</h1>    

<div class="row justify-content-center">
        <form action="process.php" method="POST">
<!-- пишем расширение -->

        <div class="row"> 
            <div class="col-sm" style="width:400px">
                    <select class="custom-select" name="otdeli">
                        <option selected>Отдел</option>
                        <option value="7">Frontend</option>
                        <option value="8">Backend</option>
                        <option value="9">CEO</option>
                    </select>
            </div>
            <div class="col-sm">
                    <select class="custom-select" name="slojnosti">
                        <option selected>Сложность задачи</option>
                        <option value="1">легко</option>
                        <option value="2">средней сложности</option>
                        <option value="3">сложно</option>
                        <option value="4">очень сложно</option>
                    </select>
            </div>
            <div class="col-sm">
                    <select class="custom-select" name="doljnosti">
                        <option selected>Должность</option>
                        <option value="1">junior</option>
                        <option value="2">midle</option>
                        <option value="3">senior</option>
                        <option value="4">team lead</option>
                    </select>
            </div>
        </div>
<!-- законченное написание расширения -->
            <div class="form-group">
            <input type="hidden" name="id" value="<?php
echo $id;
?>">
            </div>
            <div class="form-group">
            <label>Имя</label>
            <input type="text" name="name" class="form-control"
                  value="<?php
echo $name;
?>" placeholder="Введите имя">
            </div>
            <div class="form-group">
            <label>Почта</label>
            <input type="email" name="email" class="form-control"
                   value="<?php
echo $email;
?>" placeholder="Введите почту">
            </div>
            <div class="form-group">
            <label>Задание</label>
            <input type="text" name="task" class="form-control"
                   value="<?php
echo $task;
?>" placeholder="Введите задание">
            </div>
            <div class="form-group">
            <?php

if ($update == true):
?>
               <button type="submit" class="btn btn-info" name="update">Обновить</button>
            <?php
else:
?>
               <button type="submit" class="btn btn-primary" name="save">Сохранить</button>
            <?php
endif;
?>
           </div>
        </form>
</div>    
<script type="text/javascript">
 $(document).ready(function(){
  $("#limit-records").change(function(){
   $('form').submit();
  })
 })
</script>
<style>
input[type=submit]{
  height: 55px;
  background-color: #4CAF50;
  border: none;
  border-radius: 10px;
  color: white;
  padding: 16px 32px;
  text-decoration: none;
  margin: 4px 2px;
  margin-top: 2px;
  cursor: pointer;
  
}    
.active {background-color: #337ab7;border-color: #337ab7;}
</style>

    </body>
</html>