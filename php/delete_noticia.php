<?php
$mysqli = mysqli_connect("localhost", "root", "", "noticias");

header("Access-Control-Allow-Origin: *");

$id = file_get_contents('php://input');

$sql = "DELETE FROM Noticias WHERE id = $id";

$rs = mysqli_query($mysqli, $sql);

if($rs){
  echo "true";
} else {
  echo "false";
}
?>