<?php

class Score {
	
	private $score;
	
	public function __construct($score)
	{
		$this->score = $score;
	}
	
	public function getScore($round)
	{
		if ($round == 'separation')
			$score = $this->score->{$round};
		else
			$score = $this->score->{'round' . $round};
			
		if ($score == null)
			return 0;
		return $score;
	}
	
	public function getTotal()
	{
		$total = 0;
		foreach($this->score as $key => $score) {
			if (strpos($key, 'round') === 0)
				$total += $score;
		}
		return $total;
	}
}