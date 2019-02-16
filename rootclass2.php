<?php

class Root extends Node
{

	public $points = [];
	

	function analyseMoves()
	{

		foreach ($this->availableMoves as $index => $move)
		{
			$newBoard = $this->newBoard($index);
			$child = new Node(
					$newBoard, 
					-$this->currentPlayer, 
					1, 
					$this->maxPoints,
					$this->minPoints
			);
			$this->points[] = $child->evaluateNode();
			$child = null;

			$points = $this->currentPlayer == 1 ? max($this->points) : min($this->points);
			$this->AlphaBeta($points);
			
		}
	}
	

	function outputMove()
	{

		if (empty($this->points))
		{
			$this->analyseMoves();
		}

		$points = $this->currentPlayer == 1 ? max($this->points) : min($this->points);
		$index = array_search($points, $this->points);
		$output = ["move" => $this->availableMoves[$index], "rootnode", $this];

		echo json_encode($output, JSON_PRETTY_PRINT);
		exit;
	}
}
?>