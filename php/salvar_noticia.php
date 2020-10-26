<?php

header("Access-Control-Allow-Origin: *");

$mysqli = mysqli_connect("localhost", "root", "", "noticias");

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$titulo = $input['titulo'];
$conteudo = $input['conteudo'];
$id = $input['id'];

$sql;

if($id) {
  $sql = "UPDATE Noticias SET titulo = '$titulo', conteudo = '$conteudo' WHERE id = '$id'";
} else {
  $sql = "INSERT INTO Noticias (titulo, conteudo) VALUES ('".$titulo."', '".$conteudo."')";
}

$rs = mysqli_query($mysqli, $sql);

if($rs){
  echo "true";
} else {
  echo "false";
}
?>