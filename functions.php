<?php

function configAI()
{
	global $board, $size, $AI, $currentPlayer;
	
	if (!isset($AI))
	{
		parseInput();
	}
	

	NodeClass::$size = $size;

	switch ($AI)
	{	
		case "AI1":
			NodeClass::$maxDepth = 3;
			break;
	
		case "AI2":
			NodeClass::$maxDepth = 6;
			break;

		case "AI3":
			NodeClass::$maxDepth = 0;
	}

	require_once 'minimax.php';
	
}


function parseInput()
{
	global $board, $size, $AI, $currentPlayer;
	

	extract($_GET);


	if ($currentPlayer == 2)
	{
		$currentPlayer = -1;
	}


	$board = preg_split('/ +/', urldecode($board));
	$brd = [];

	foreach ($board as $move)
	{
		$i = intval($move[0]);
		$j = intval($move[1]);
		$plr = intval($move[2]);
		if ($plr == 2) $plr = -1;
		$brd[$i][$j] = $plr;
	}
	

	$size = $n;
	$board = $brd;

}


?>