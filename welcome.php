<?php
include 'connect.php';
session_start();

if ($_SESSION['check'] == 1) {
    phpAlert($_SESSION['message'] ); 
    $_SESSION['check'] = 0;
}
$_SESSION['check'] = 0;
$res = mysqli_query($conn, "SELECT * FROM product WHERE stock > 0;");



$numRows = mysqli_num_rows($res);

if (isset($_POST['return_btn'])) {
    $_SESSION['check'] = 0;
    header('Location: profile.php');
}
  
if(isset($_POST['logout_btn']))
{
    session_destroy();
    header("Location: index.php");
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
        .container {
            padding: 40px;
            text-align: center;
            background: #1abc9c;
            color: black;
            font-size: 30px;
        }

        .axc {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
    </style>
</head>

<body>
<div class="axc">
<form method="POST">
<input type="submit" name="return_btn" value="Profile">
<input type="submit" name="logout_btn" value="Logout">
    </form>
    </div>
    <div class="container">

        <div class="card mt-5">
            <div class="card-header">
                <h2>Products</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Pid</th>
                        <th>Pname</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>

                    <tr>
                        <?php

                        while ($row = mysqli_fetch_array($res)) {

                            if ($row['stock'] > 0) {
                                echo "<tr>";
                                echo "<td>" . $row['pid'] . "</td>";
                                echo "<td>" . $row['pname'] . "</td>";
                                echo "<td>" . $row['price'] . "</td>";
                                echo "<td>" . $row['stock'] . "</td>";
                                //echo "<td><input type='submit' name='submit' value=".$row['pid']."></td>";

                                //  echo "<td>";
                                //echo "<form type='POST'><input type='text' placeholder='Quantity:' name='".$numOfText."' autocomplete='off'></form>";
                                //  echo "</td>";
                                // echo "<td>";
                                // echo "<p><a href='buyproduct.php'?piid=".$row['pid']."target='_blank'>". "Buy" ."</a></p>";
                                //echo  "<a href='buyproduct.php?piid=". $row['pid']. "&countProduct=". $_POST[$row['pid']]. " class='btn btn-info'>Buy</a>";

                                //echo  "<a href='buyproduct.php?piid=". $row['pid']. "&countProduct=". $numOfText."'>Buy</a>";
                                echo "<td>" . '<form method="POST"><input type=hidden name="rowid" value='. $row['pid'].'>
                            <input type = "number" name="amount" required>
                            <input type="submit" name="buy_btn" value="Buy"></form>' . "</td>";
                   
                                //echo "</td>";
                                echo "</tr>";
                            }
                        }
                        /*
                        if (!empty($_POST['buy_btn'])) {
                            //$_SESSION['abc'] = $_POST['amount'];
                            header('Location: buyproduct.php');
                        }
                        */

                        if (isset($_POST['buy_btn'])) {
                            $_SESSION['quantity'] = $_POST['amount'];
                            $_SESSION['pidNumber'] = $_POST['rowid'];
                            header('Location: buyproduct.php');
                        }
                        ?>
                    </tr>

                </table>
            </div>
        </div>
    </div>

</body>

</html>
<?php function phpAlert($msg) { echo '<script type="text/javascript">alert("' . $msg . '")</script>'; } ?>