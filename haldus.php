<?php
require_once ('conf.php');
session_start();
if (!isset($_SESSION['tuvastamine'])){
    header('Location:login.php');
    exit();
}

global $connection;
//null komment
if (isset($_REQUEST['komment'])){
    $order=$connection->prepare("UPDATE konkurs set kommentaar=' ' where id=?");
    $order->bind_param("i",$_REQUEST['komment']);
    $order->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//null points
if (isset($_REQUEST['punkt'])){
    $order=$connection->prepare("UPDATE konkurs set punktid=0 where id=?");
    $order->bind_param("i",$_REQUEST['punkt']);
    $order->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST['avamine'])){
    $order=$connection->prepare("UPDATE konkurs set avalik=1 where id=?");
    $order->bind_param("i",$_REQUEST['avamine']);
    $order->execute();
}
if (isset($_REQUEST['peitmine'])){
    $order=$connection->prepare("UPDATE konkurs set avalik=0 where id=?");
    $order->bind_param("i",$_REQUEST['peitmine']);
    $order->execute();
}
if(isset($_REQUEST ['kustuta'])) {
        $order = $connection->prepare("DELETE FROM konkurs WHERE id=?");
        $order->bind_param("i", $_REQUEST['kustuta']);
        $order->execute();

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
    <title>Fotokonkurss Haldusleht</title>
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

<h1>Fotokonkurss Haldusleht</h1>
<?php
//tabeli konkurss sisu näitamine
$order=$connection->prepare("SELECT id, nimi, pilt, lisamisaeg, punktid,kommentaar, avalik FROM konkurs");
$order->bind_result($id,$nimi,$pilt,$aeg,$punktid,$kom, $avalik);
$order->execute();
echo "<table>";
echo "<tr>
        <th>Status</th>
        <th>ID</th>
        <th>Nimi</th>
        <th>Lisamis Aeg</th>
        <th>Pilt</th>
        <th>Kommenataar</th>
        <th>Punktikd</th>
        <th>Tegevused</th>
        </tr>";

while($order->fetch()){
    $avatekst="Ava";
    $param="avamine";
    $seisund="Peidetud";
    if ($avalik==1){
        $avatekst="Peida";
        $param="peitmine";
        $seisund="Avatud";
    }
    $txt='"Kas sa tahad kustutada seda fotod"';
    echo "<tr>";
    echo "<td>$seisund<br>";
    echo "<a href='?$param=$id'>$avatekst</a><br>";
    echo "<a href='?kustuta=$id' onclick='return confirm($txt)'>Kustuta</a></td>";
    echo "<td>$id</td>";
    echo "<td>$nimi</td>";
    echo "<td>$aeg</td>";
    echo "<td><img src='$pilt' alt='pilt'></td>";
    echo "<td>$kom</td>";
    echo "<td>$punktid</td>";
    echo "<td><a href='?punkt=$id'>Nullita punktid</a><br>";
    echo "<a href='?komment=$id'>Kustuta komment</a></td>";
    echo "</tr>";
}
echo "</table>";
?>
</body>
</html>
