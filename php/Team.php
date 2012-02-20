<?php

class Team {
	
	private $id;
	private $name;
	private $num;
	
	private $score;
	
	public function __construct($id, $num, $name, $score = array())
	{
		$this->id = $id;
		$this->name = $name;
		$this->num = $num;
		$this->score = new Score($score);
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setNumber($number)
	{
		$this->num = $number;
		return $this;
	}
	
	public function getNumber()
	{
		return $this->num;
	}
	
	public function getScore($round)
	{
		return $this->score->getScore($round);
	}
}