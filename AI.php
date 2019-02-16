<?php
$board = [];
$size = 0;
$AI = "";
$currentPlayer = 0;

require_once('functions.php');
require_once('nodeclass2.php');			

parseInput();
configAI();
require_once('rootclass2.php');


$root = new Root($board, $currentPlayer);
$root->analyseMoves();
$root->outputMove();


?>