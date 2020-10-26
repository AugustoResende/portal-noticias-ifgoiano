<?php
$mysqli = mysqli_connect("localhost", "root", "", "noticias");

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");

$array = array();
if ($result = $mysqli->query("SELECT * FROM Noticias")) {

    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $array[] = $row;
    }
    echo json_encode($array);
}

$result->close();
$mysqli->close();
