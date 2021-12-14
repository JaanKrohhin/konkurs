<?php
require_once ('conf.php');
session_start();
if (!isset($_SESSION['tuvastamine'])){
    header('Location:loginDT.php');
    exit();
}

global $connection;

if (!empty($_REQUEST['nimi'])){
$order=$connection->prepare("Insert into konkurs(nimi,pilt,lisamisaeg) values(?,?,Now())");
$order->bind_param("ss",$_REQUEST['nimi'],$_REQUEST['pilt']);
$order->execute();
header("Location: haldus.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Fotokonkurss Lisamis Leht</title>
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

<h1>Fotokonkurss Lisamis Leht</h1>
<?php
//tabeli konkurss sisu näitamine
$order=$connection->prepare("SELECT id, nimi, pilt, lisamisaeg, punktid,kommentaar, avalik FROM konkurs");
$order->bind_result($id,$nimi,$pilt,$aeg,$punktid,$kom, $avalik);
$order->execute();
?>
<form action="?">
    </label><input type="text" name="nimi" placeholder="Uus nimi"><br>
    <textarea name="pilt" cols="30" rows="10"></textarea><br>
    <input type="submit" name="lisa">
</form>

</body>
</html>
