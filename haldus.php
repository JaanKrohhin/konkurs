<?php
require_once ('conf.php');
global $connection;
$answer=false;
//null points
if (isset($_REQUEST['punkt'])){
    $order=$connection->prepare("UPDATE konkurs set punktid=0 where id=?");
    $order->bind_param("i",$_REQUEST['punkt']);
    $order->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (!empty($_REQUEST['nimi'])){
    $order=$connection->prepare("Insert into konkurs(nimi,pilt,lisamisaeg) values(?,?,Now())");
    $order->bind_param("ss",$_REQUEST['nimi'],$_REQUEST['pilt']);
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
    <a href="haldus.php">Admin Leht</a>
    <a href="konkurs.php">Kasutaja Leht</a>
</nav>
<h1>Fotokonkurss Haldusleht</h1>
<?php
//tabeli konkurss sisu näitamine
$order=$connection->prepare("SELECT id, nimi,pilt,lisamisaeg,punktid, avalik FROM konkurs");
$order->bind_result($id,$nimi,$pilt,$aeg,$punktid, $avalik);
$order->execute();
echo "<table>";
echo "<tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Lisamis Aeg</th>
        <th>Pilt</th>
        <th>Punktikd</th>
        <th>Status</th>
        <th>Tegevused</th>
        </tr>";

while($order->fetch()){
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$nimi</td>";
    echo "<td>$aeg</td>";
    echo "<td><img src='$pilt' alt='pilt'></td>";
    echo "<td>$punktid</td>";
    $avatekst="Ava";
    $param="avamine";
    $seisund="Peidetud";
    if ($avalik==1){
        $avatekst="Peida";
        $param="peitmine";
        $seisund="Avatud";
    }
    $txt='"Kas sa tahad kustutada seda fotod"';
    echo "<td>$seisund</td>";
    echo "<td><a href='?punkt=$id'>Nullita</a><br>";
    echo "<a href='?$param=$id'>$avatekst</a><br>";
    echo "<a href='?kustuta=$id' onclick='return confirm($txt)'>Kustuta</a></td>";
    echo "</tr>";
}
echo "</table>"
?>
<h2>Uue Pilti lisamine</h2>
<form action="?">
    </label><input type="text" name="nimi" placeholder="Uus nimi"><br>
    <textarea name="pilt" cols="30" rows="10"></textarea><br>
    <input type="submit" name="lisa">
</form>
</body>
</html>
