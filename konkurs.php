<?php
require_once ('conf.php');
session_start();
if (!isset($_SESSION['tuvastamine'])){
    header('Location:login.php');
    exit();
}

global $connection;
//new comment
if (isset($_REQUEST['uus_komment'])){
    $order=$connection->prepare("UPDATE konkurs set kommentaar=Concat(kommentaar,?) where id=?");
    $adddd=$_REQUEST['komment']."\n";
    $order->bind_param("si",$adddd, $_REQUEST['uus_komment']);
    $order->execute();
    header("Location: $_SERVER[PHP_SELF]");}

//add points
if (isset($_REQUEST['punkt'])){
    $order=$connection->prepare("UPDATE konkurs set punktid=punktid+1 where id=?");
    $order->bind_param("i",$_REQUEST['punkt']);
    $order->execute();
    header("Location: $_SERVER[PHP_SELF]");}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Fotokonkurss</title>
</head>
<body>
<nav>
    <?php
    if ($_SESSION['onAdmin']==1){
        echo '<a href="haldus.php">Admin Leht</a>
    <a href="lisamine.php">Lisamis Leht</a>';
    }
    ?>
    <a href="konkurs.php">Kasutaja Leht</a>
    <a href="https://github.com/JaanKrohhin/konkurs.git" target="_blank">Github</a>
</nav>
    <div class="user">
        <p><?=$_SESSION["kasutaja"]?> on sisse logitud</p>
        <form action="logout.php" method="post">
            <input type="submit" value="Logi välja" name="logout">
        </form>
    </div>

    <h1>Fotokonkurss "Orav"</h1>
    <?php
    //tabeli konkurss sisu näitamine
    $order=$connection->prepare("SELECT id, nimi,pilt,kommentaar,punktid, avalik FROM konkurs where avalik=1");
    $order->bind_result($id,$nimi,$pilt,$kom,$punktid, $avalik);
    $order->execute();
    echo "<table>";
    if ($_SESSION["onAdmin"]==0){
        echo "<tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Pilt</th>
        <th>Kommentaarid</th>
        <th>Lisa komment</th>
        <th>Punktikd</th>
        <th>Tegevused</th>
        </tr>";
    }else{
        echo "<tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Pilt</th>
        <th>Kommentaarid</th>
        <th>Punktikd</th>
        </tr>";
    }
    while($order->fetch()){
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$nimi</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>".nl2br($kom)."</td>";
        if ($_SESSION["onAdmin"]==0){
            echo "<td>
        <form action='?'>
            <input type='hidden' name='uus_komment' value='$id'>
            <input type='text' name='komment'>
            <input type='submit' value='OK'>
        </form>
        </td>";
        }

        echo "<td>$punktid</td>";
        if ($_SESSION["onAdmin"]==0){
            echo "<td><a href='?punkt=$id'>Lisa punkt</a></td>";
        }
        echo "</tr>";
    }
    echo "</table>"
    ?>
</body>
</html>
