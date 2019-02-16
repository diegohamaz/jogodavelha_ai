<?php


class Node extends NodeClass
{

	protected function heuristic()
	{
		$total = 0;
		$diagonal1 = [];
		$diagonal2 = [];
		$total = 0;

		for ($i = 0; $i < self::$size; $i++)
		{
			$row = [];
			$column = [];
	
			for ($j = 0; $j < self::$size; $j++)
			{
				$row[] 		= $this->board[$i][$j];
				$column[] 	= $this->board[$j][$i];
			}
			$total += self::pointsForRow($row);
			$total += self::pointsForRow($column);
		
			$diagonal1[] = $this->board[$i][$i];
			$diagonal2[] = $this->board[self::$size - $i - 1][$i];
		}
		

		$total += self::pointsForRow($diagonal1);
		$total += self::pointsForRow($diagonal2);

		return $total;
	}
	

	private static function pointsForRow($row)
	{

		if (count($row) != self::$size)
		{
			return false;
		}

		$foundP1 = false;
		$foundP2 = false;
		$partial = 0;
		
		for ($i = 0; $i < self::$size; $i++)
		{
		
			if ($row[$i] == 1)
			{
				$foundP1 = true;
				if ($partial == 0)
				{
					$partial = 1;
					continue;					
				}
			}
		
			if ($row[$i] == -1)
			{
				$foundP2 = true;
				if ($partial == 0)
				{
					$partial = -1;
					continue;					
				}
			}

			if ($foundP1 && $foundP2)
			{
				$partial = 0;
				break;
			}
		
			elseif (($foundP1 xor $foundP2) && ($row[$i] != 0))
			{
				$partial *= 10 * $row[$i];
			}

		}
		return $partial;
	}
}

?>