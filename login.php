<?php
//login ja parool salvestatud andmebaasiga andmetega
session_start();
if (isset($_SESSION['tuvastamine'])){
    header('Location:konkurs.php');
    exit();
}
if (!empty($_POST['login']) && !empty($_POST['pass'])){
    $login=$_POST['login'];
    $pass=$_POST['pass'];

    $sool='text';
    $krypt=crypt($pass,$sool);
    //check the database for the user
    require('conf.php');
    global $connection;
    $order=$connection->prepare("SELECT nimi, onAdmin, koduleht from kasutaja where nimi=? and parool=?");
    $order->bind_param("ss", $login,$krypt);
    $order->bind_result($nimi,$onAdmin,$koduleht);
    $order->execute();
    if ($order->fetch()){
        $_SESSION['tuvastamine']='niilihtne';
        $_SESSION['kasutaja']=$nimi;
        $_SESSION['onAdmin']=$onAdmin;
        if (isset($koduleht)){
            header("Location:$koduleht");
        }else{
            header('Location: _main.php');}
        exit();
    }
    else{
        echo "Kasutaja $login või parool $krypt on vale";
    }
}?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body class="back">
<h1>Login Vorm</h1>
<form action="" method="post">
    Login:
    <input type="text" name="login" placeholder="kasutaja nimi"><br>
    Parool:
    <input type="password" name="pass">
    <br><input type="submit" value="Logi sisse">
</form>
</body>
</html>
