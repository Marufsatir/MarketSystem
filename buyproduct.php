<?php
    include 'connect.php';
    session_start();
    
    $ciid = $_SESSION['cid'];
    $piid = $_SESSION['pidNumber'];
    $quantityBuy =$_SESSION['quantity'];

   // echo "$ciid, $piid, $quantityBuy";

    $res = mysqli_query($conn, "SELECT * FROM customer WHERE UPPER(cid) = '$ciid';") or die( mysqli_error($conn));
    $resRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $res1 = mysqli_query($conn, "SELECT * FROM product WHERE pid = '$piid';") or die( mysqli_error($conn));
    $res1Row = mysqli_fetch_array($res1, MYSQLI_ASSOC);
    
    if($res1Row != null && $res1Row['stock'] - $quantityBuy >= 0)
    {
    if($resRow['wallet'] < $quantityBuy * $res1Row['price'])
    {
        $_SESSION['check'] = 1;
        $_SESSION['message'] = "not enough money";
    }else
    {
        $_SESSION['check'] = 1;
        $_SESSION['message'] = "Producted has been purchased.";

        $newWage = $resRow['wallet'] - $quantityBuy * $res1Row['price'];
        $sql = "UPDATE customer SET wallet = '$newWage' WHERE UPPER(cid) = '$ciid';";
        mysqli_query($conn, $sql);
        $newStock = $res1Row['stock'] - $quantityBuy;
        $sql = "UPDATE product SET stock = '$newStock' WHERE pid = '$piid';";
        mysqli_query($conn, $sql);
        
        $sql = "SELECT * FROM buy WHERE UPPER(cid) = '$ciid' AND pid = '$piid';";
        $rowData = mysqli_query($conn, $sql);

        if(mysqli_num_rows($rowData)==0)
        {
            $resCustom = mysqli_query($conn, "SELECT * FROM customer WHERE UPPER(cid) = '$ciid';");
            $resCustomRow = mysqli_fetch_array($resCustom, MYSQLI_ASSOC);
            $customerRealCid = $resCustomRow['cid'];

            $quant = $quantityBuy;
            $sql = "INSERT buy VALUES('$customerRealCid', '$piid', '$quant' )";
            mysqli_query($conn, $sql);

        }else
        {
            echo "row 1";

            $resQuantity = mysqli_query($conn,  "SELECT * FROM buy WHERE UPPER(cid) = '$ciid' AND pid = '$piid';");
            $resquantiyRow = mysqli_fetch_array($resQuantity, MYSQLI_ASSOC);
            $quantityNow = $resquantiyRow['quantity'] + $quantityBuy;
 

            mysqli_query($conn, "UPDATE buy SET quantity = '$quantityNow' WHERE UPPER(cid) = '$ciid' AND pid = '$piid';");
        }

        $_SESSION['check'] = 1;
        $_SESSION['message'] = "Products has been purchased successfully.";


    }
}
else
{
    $_SESSION['check'] = 1;
    $_SESSION['message'] = "There is not enough product.";
}
    header('Location: welcome.php');
?>