<?php

abstract class NodeClass
{
	static $size;					
									
	static $maxDepth = 0;			
	public $board = [];				
	public $availableMoves = [];	
									
	protected $currentPlayer;		
	protected $depth;				
	protected $maxPoints;			
	protected $minPoints;			

	function __construct($brd, $plr, $dpth = 0, $max = false, $min = false)
	{
		$this->board = $brd;
		$this->currentPlayer = $plr;
		$this->depth = $dpth;
		$this->maxPoints = $max;
		$this->minPoints = $min;

		for ($i = 0; $i < self::$size; $i++)
		{
			for ($j = 0; $j < self::$size; $j++)
			{
				if ($this->board[$i][$j] == 0)
				{
					$this->availableMoves[] = strval($i) . strval($j);
				}
			}
		}
	}

	public function evaluateNode()
	{

		$className = get_called_class();

		if ( (($winner = $this->winState()) === false) && (($this->depth < self::$maxDepth) || (self::$maxDepth == 0)) )
		{
			$points = false;
			foreach ($this->availableMoves as $index => $move)
			{
			
				$newBoard = $this->newBoard($index);
				$child = new $className(
						$newBoard,
						-$this->currentPlayer,
						$this->depth + 1,
						$this->maxPoints,
						$this->minPoints
						);
				$childPoints = $child->evaluateNode();
				$child = null;
				
	
				if 	(
						(($this->currentPlayer*$childPoints) > ($this->currentPlayer*$points)) || 
						($points === false)
					)
				{
					$points = $childPoints;
				}
		
				if ($this->AlphaBeta($points))
				{
					break;
				}
			}
		}

		else
		{
			$points = $this->heuristic();
		}
	
		return $points;
	}
	

	abstract protected function heuristic();
	

	protected function AlphaBeta($points)
	{
	
		if ($this->currentPlayer == 1)
		{

			if ($this->maxPoints !== false)
			{
				if ($points >= $this->maxPoints)
				{

					return true;
				}
			}				
		
			if ($this->minPoints === false)
			{
				$this->minPoints = $points;
			}
			elseif ($points > $this->minPoints)
			{
				$this->minPoints = $points;
			}
		}

		else
		{

			if ($this->minPoints !== false)
			{
				if ($points <= $this->minPoints)
				{
		
					return true;
				}
			}
		
	
			if ($this->maxPoints === false)
			{
				$this->maxPoints = $points;
			}
			elseif ($points < $this->maxPoints)
			{
				$this->maxPoints = $points;
			}
		}

		return false;	
	
	}	

	protected function newBoard($index)
	{

		if (!array_key_exists($index, $this->availableMoves))
		{
			return false;
		}
		

		$x = intval($this->availableMoves[$index][0]);
		$y = intval($this->availableMoves[$index][1]);
		

		$newBoard = [];
		for ($i = 0; $i < self::$size; $i++)
		{
			for ($j = 0; $j < self::$size; $j++)
			{
				$newBoard[$i][$j] = $this->board[$i][$j];
			}
		}
		$newBoard[$x][$y] = $this->currentPlayer;
		
		return $newBoard;
	}

	protected function winState()
	{

		for ($i = 0; $i < 2*(self::$size) + 2; $i++)
		{
			$sequences[$i] = [];
		}
	

		for ($i = 0; $i < (self::$size); $i++)
		{
		
			$sequences[0][$i] = $this->board[$i][$i];
			$sequences[1][$i] = $this->board[self::$size - $i - 1][$i];
	
			for ($j = 0; $j < (self::$size); $j++)
			{
				$sequences[2 + $i][$j] = $this->board[$i][$j];
				$sequences[2 + $i + self::$size][$j] = $this->board[$j][$i];
			}
		}
	

		$winner = 0;
		foreach ($sequences as $seq)
		{
			$max = max($seq);
			$min = min($seq);
			if (($max == $min) && ($max != 0))
			{
				$winner = $max;
				break;
			}
		}
	

		if ( ($winner == 0) && (!empty($this->availableMoves)) )
		{
			$winner = false;
		}
	
		return $winner;
	}
}
?>