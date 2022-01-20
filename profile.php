<?php
include 'connect.php';
session_start();
$ciid = $_SESSION['cid'];
$moneyCustomer =  mysqli_query($conn, "SELECT * FROM customer WHERE cid = '$ciid';");
$rowCust = mysqli_fetch_array($moneyCustomer);
$money = $rowCust['wallet'];
$res = mysqli_query($conn, "SELECT product.pid AS pid, product.pname AS pname, product.price AS price, buy.quantity AS quantity FROM buy, product WHERE '$ciid' = buy.cid AND buy.pid = product.pid;");

if (isset($_POST['return_btn'])) {
    $quantityReturn = $_POST['amount'];
    $rowID = $_POST['rowid'];

    $boughtByCustomer =  mysqli_query($conn, "SELECT * FROM buy WHERE cid = '$ciid' AND pid = '$rowID';");
    $rowBought = mysqli_fetch_array($boughtByCustomer);

    if ($rowBought['quantity'] < $quantityReturn) {
        phpAlert("You chosen the quantity more than you have.");
    } else {
        $product =  mysqli_query($conn, "SELECT * FROM product WHERE pid = '$rowID';");
        $rowProduct = mysqli_fetch_array($product);

        $newWage = $money + $rowProduct['price'] * $quantityReturn;
        mysqli_query($conn, "UPDATE customer SET wallet = '$newWage' WHERE cid = '$ciid';");
        $newProductCount = $rowProduct['stock'] + $quantityReturn;
        mysqli_query($conn, "UPDATE product SET stock = '$newProductCount' WHERE pid = '$rowID';");

        if ($rowBought['quantity'] == $quantityReturn) {
            mysqli_query($conn, "DELETE FROM buy WHERE pid = '$rowID' AND cid = '$ciid';");
        } else {
            $newProductBuy = $rowBought['quantity'] - $quantityReturn;
            mysqli_query($conn, "UPDATE buy set quantity = '$newProductBuy' WHERE pid = '$rowID' AND cid = '$ciid';");
        }
        $moneyCustomer =  mysqli_query($conn, "SELECT * FROM customer WHERE cid = '$ciid';");
        $rowCust = mysqli_fetch_array($moneyCustomer);
        $money = $rowCust['wallet'];
        $res = mysqli_query($conn, "SELECT product.pid AS pid, product.pname AS pname, product.price AS price, buy.quantity AS quantity FROM buy, product WHERE '$ciid' = buy.cid AND buy.pid = product.pid;");

        phpAlert("You have returned the product successfully.");
    }


}

if(isset($_POST['main_btn']))
{
    $_SESSION['check'] = 0;
    $_SESSION['message'] = "from profile";
    header('Location: welcome.php');
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
            <input type="submit" name="main_btn" value="Return to Main">
            <input type="submit" name="logout_btn" value="Logout">
        </form>
    </div>
    <div class="container">

        <div class="card mt-5">
            <h2> Wallet : <?php echo "$money"; ?></h2>
            <div class="card-header">
                <h2>Products</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Pid</th>
                        <th>Pname</th>
                        <th>Price</th>
                        <th>quantity</th>
                    </tr>

                    <tr>
                        <?php

                        while ($row = mysqli_fetch_array($res)) {

                            echo "<tr>";
                            echo "<td>" . $row['pid'] . "</td>";
                            echo "<td>" . $row['pname'] . "</td>";
                            echo "<td>" . $row['price'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            //echo "<td><input type='submit' name='submit' value=".$row['pid']."></td>";

                            //  echo "<td>";
                            //echo "<form type='POST'><input type='text' placeholder='Quantity:' name='".$numOfText."' autocomplete='off'></form>";
                            //  echo "</td>";
                            // echo "<td>";
                            // echo "<p><a href='buyproduct.php'?piid=".$row['pid']."target='_blank'>". "Buy" ."</a></p>";
                            //echo  "<a href='buyproduct.php?piid=". $row['pid']. "&countProduct=". $_POST[$row['pid']]. " class='btn btn-info'>Buy</a>";

                            //echo  "<a href='buyproduct.php?piid=". $row['pid']. "&countProduct=". $numOfText."'>Buy</a>";
                            echo "<td>" . '<form method="POST"><input type=hidden name="rowid" value=' . $row['pid'] . '>
                        <input type = "number" name="amount" required>
                        <input type="submit" name="return_btn" value="Return"></form>' . "</td>";

                            //echo "</td>";
                            echo "</tr>";
                        }

                        ?>
                    </tr>

                </table>
            </div>
        </div>
    </div>

</body>

</html>

<?php function phpAlert($msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
} ?>