<?php
include 'connect.php';

if(!empty($_POST["submit"]))
{    

    if(!empty($_POST['name']) && !empty($_POST['password']))
    {
        $userID = strtoupper($_POST['name']);
        $password = strtoupper($_POST['password']);
        $res = mysqli_query($conn, "SELECT * FROM customer WHERE UPPER(cname) = '$userID' AND UPPER(cid) = '$password';");
        $resRow = mysqli_fetch_array($res, MYSQLI_ASSOC);

        if($resRow != null)
        {
            session_start();
            $_SESSION['cid'] = $password; 
            $_SESSION['check'] = 0;
            $_SESSION['message'] = ""; 
            header('Location: welcome.php'); 
        }else
        {
            echo '<script language="javascript">';
            echo 'alert("wrong name or password")';
            echo '</script>';
        }
    }else
    {
        echo '<script language="javascript">';
        echo 'alert("no name or password")';
        echo '</script>';
    }

}
?>


<!doctype html>
<html lang="en">

<head>
<title>Shopping Application</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
    .headerShopping {
  padding: 40px;
  text-align: center;
  background: #1abc9c;
  color: white;
  font-size: 30px;
    }
    </style>
</head>

<body>
    <div class="headerShopping">
        <h1>Shopping Application</h1>
    </div>
    
    <div class="container my-5">
        <form method="POST">
            <div class="form-group">
                <label>User name:</label>
                <input type="text" class="form-control" placeholder="Enter user name:" name="name" autocomplete="off">
            </div>

            <div class="form-group">
                <label>password:</label>
                <input type="password" class="form-control" placeholder="Enter your password:" name="password" autocomplete="off">
            </div>

            <input type="submit" name="submit" value="submit"> 
        </form>

</body>

</html>