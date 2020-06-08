<?php
require_once 'process.php';
?>
<html>
    <head>
        <title>TodoList</title>
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
                    <a class="navbar-brand" href="#">TaskBook</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                           <li class="nav-item active">
                            <a class="nav-link" href="https://github.com/Sparow2021">Application code github<span class="sr-only">(current)</span></a>
                           </li>
                        </ul>
                        <?php
if ($_SESSION['logined'] == true):
?>
                       <button class="btn btn-outline-danger my-2 my-sm-0" type="button"><a style="color: white !important; text-decoration:none;" href="process.php?exit">Exit</a></button>
                        <?php
else:
?>
                       <button class="btn btn-outline-success my-2 my-sm-0" type="button"><a style="color: white !important; text-decoration:none;" href="auth.php">Authorization</a></button>
                        <?php
endif;
?>
                   </div>
                 </nav>
        
        

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
                <span aria-hidden="true">« Previous</span>
          </a>
         <?php else: ?>
        <li>
          <a class="page-link" href="index.php?page=<?= $Previous; ?>" aria-label="Previous">
            <span aria-hidden="true">« Previous</span>
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
            <span aria-hidden="true">to first page</span>
            </a>
        </li> 
       <?php else:?>   
        <li >
          <a class="page-link" href="index.php?page=<?= $Next; ?>" aria-label="Next">
            <span aria-hidden="true">Next »</span>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Task</th>
                        <th>Status</th>
                        <?php
if ($_SESSION['auth'] == true):
?>
                           <th colspan="3">Action</th>
                        <?php
else:
?>
                           <th>Remark</th>
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
    echo $rowS['email'];
?></td>
                    <td><?php
    echo $rowS['task'];
?></td>
                    <td><?php
    if ($rowS['done'] == 1) {
        echo 'Done';
    } else {
        echo 'Not done';
    };
?></td>
                    <?php
    if ($_SESSION['auth'] == true):
?>
                   <td>
                        <a href ="index.php?edit=<?php
        echo $rowS['id'];
?>"
                           class="btn btn-info">Edit</a>
                        <a href="process.php?remove=<?php
        echo $rowS['id'];
?>"
                           class="btn btn-danger">Delete</a>
                        <?php
        if ($rowS['done'] == 0):
?>
                       <a href="process.php?done=<?php
            echo $rowS['id'];
?>"
                           class="btn btn-success">Done</a>
                        <?php
        else:
?>
                       <a href="process.php?notdone=<?php
            echo $rowS['id'];
?>"
                           class="btn btn-warning">Esc</a>    
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
                           <td>Changed by admin.</td>
                       <?php
        else:
?>
                           <td>Task did not change</td>
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
<div class="container" style="margin-top:20px;">
    <form action="process.php" method="POST">
    <div class="row"> 
    
        <div class="col-sm">
        <input  type="submit" name="ASC" value="Ascending"><br><br>
        </div>
        <div class="col-sm">
        <input  type="submit" name="DESC" value="Descending"><br><br>
        </div>
        <div class="col-sm">
        <select class="custom-select" name="fields">
            <option selected>Choose your option</option>
            <option value="name">Name</option>
            <option value="email">Email</option>
            <option value="done">Status</option>
        </select>
        </div>
    </div>
    </form>

</div>       
      <!--конец сортировки-->  
<div class="row justify-content-center">
        <form action="process.php" method="POST">
            <div class="form-group">
            <input type="hidden" name="id" value="<?php
echo $id;
?>">
            </div>
            <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control"
                  value="<?php
echo $name;
?>" placeholder="Enter your name">
            </div>
            <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php
echo $email;
?>" placeholder="Enter your email">
            </div>
            <div class="form-group">
            <label>Task text</label>
            <input type="text" name="task" class="form-control"
                   value="<?php
echo $task;
?>" placeholder="Enter your task">
            </div>
            <div class="form-group">
            <?php
if ($update == true):
?>
               <button type="submit" class="btn btn-info" name="update">Update</button>
            <?php
else:
?>
               <button type="submit" class="btn btn-primary" name="save">Save</button>
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