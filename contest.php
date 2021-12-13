<?php
require_once ('conf.php');
global $connection;

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
        <a href="haldus.php">Admin Leht</a>
        <a href="konkurs.php">Kasutaja Leht</a>
    </nav>
    <h1>Fotokonkurss "Orav"</h1>
    <?php
    //tabeli konkurss sisu näitamine
    $order=$connection->prepare("SELECT id, nimi,pilt,lisamisaeg,punktid, avalik FROM konkurs where avalik=1");
    $order->bind_result($id,$nimi,$pilt,$aeg,$punktid, $avalik);
    $order->execute();
    echo "<table>";
    echo "<tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Lisamis Aeg</th>
        <th>Pilt</th>
        <th>Punktikd</th>
        <th>Tegevused</th>
        </tr>";
    while($order->fetch()){
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$nimi</td>";
        echo "<td>$aeg</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>$punktid</td>";
        echo "<td><a href='?punkt=$id'>Lisa punkt</a></td>";
        echo "</tr>";
    }
    echo "</table>"
    ?>
</body>
</html>
