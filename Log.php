<?php
require_once('connectionBD.php');

$db = Database::getInstance();
$mysqli = $db->getConnection(); 
$sql = "INSERT INTO logs_jogo(log , data_atualizacao)VALUES ('".$_POST['log']."' , now())";
$result =$mysqli->query($sql);

?>