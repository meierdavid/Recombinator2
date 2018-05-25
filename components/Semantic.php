<?php

class Semantic
{
	private $_forward;
	private $_reverse;
	
	public function __construct ($firstArg = null, $secondArg = null) 
	{
		if ($firstArg === null)
		{
			$this->_forward = new OneWaySemantic();
			$this->_reverse = new OneWaySemantic();
		}
		else if ($secondArg === null)
		{
			$this->_forward = new OneWaySemantic(($firstArg - $firstArg % 6) / 6);
			$this->_reverse = new OneWaySemantic($secondArg%6);
		}
		else if (BioFunction::isValid($firstArg) && BioFunction::isValid($secondArg))
		{
			$this->_forward = new OneWaySemantic($firstArg);
			$this->_reverse = new OneWaySemantic($secondArg);
		}
		else 
		{
			$this->_forward = $firstArg;
			$this->_reverse = $secondArg;
		}
		
		if ($this->_forward->isX() && !$this->_reverse->isN() && !$this->_reverse->isX())
		{
			throw new Exception("Invalid Semantic : fXF is incompatible with ".$this->_reverse."R !");
			$this->_reverse = new OneWaySemantic(BioFunction::N);
		}
		if ($this->_reverse->isX() && !$this->_forward->isN() && !$this->_forward->isX())
		{
			throw new Exception("Invalid Semantic : fXR is incompatible with ".$this->_forward."F !");
			$this->_forward = new OneWaySemantic(BioFunction::N);
		}
	}
	
	public function excise() 
	{
		$this->_forward->setBioFunction(BioFunction::N);
		$this->_reverse->setBioFunction(BioFunction::N);
		return $this;
	}
	
	public function reverse() 
	{
		$ows = new OneWaySemantic($this->_forward);
		$this->_forward = $this->_reverse;
		$this->_reverse = $ows;
		return $this;
	}
	
	public function isPF()
	{
		return $this->_forward->isP();
	}
	
	public function isTF()
	{
		return $this->_forward->isT();
	}
	
	public function isGF()
	{
		return $this->_forward->isG();
	}
	
	public function isX()
	{
		return $this->_forward->isX() || $this->_reverse->isX();
	}
	
	public function isXF()
	{
		return $this->_forward->isX();
	}
	
	public function isXR()
	{
		return $this->_reverse->isX();
	}
	
	public function isGR()
	{
		return $this->_reverse->isG();
	}
	
	public function isTR()
	{
		return $this->_reverse->isT();
	}
	
	public function isPR()
	{
		return $this->_reverse->isP();
	}
	
	public function isN()
	{
		return $this->_reverse->isN() && $this->_forward->isN();
	}
	
	public function isMirror()
	{
		return $this->_reverse == $this->_forward;
	}
	
	public function combine(Semantic $s) 
	{
		$this->_forward->combine($s->_forward);
		$this->_reverse = OneWaySemantic::staticCombine($s->_reverse, $this->_reverse);
		
		if ($this->_forward->isX() && !$this->_reverse->isX())
		{
			$this->_reverse->setBioFunction(BioFunction::N);
		}
		else if ($this->_reverse->isX() && !$this->_forward->isX())
		{
			$this->_forward->setBioFunction(BioFunction::N);
		}
		
		return $this;
	}
	
	public function staticCombine(Semantic $s1, Semantic $s2) 
	{
		$sem = new Semantic($s1->getSemanticForward(), $s1->getSemanticReverse());
		return $s1->combine($s2);
	}
	
	public function getSemanticForward()
	{
		return $this->_forward;
	}
	
	public function getSemanticReverse()
	{
		return $this->_reverse;
	}
	
	public function getSemanticKey()
	{
		return $this->_forward->getBioFunction()*6+$this->_reverse->getBioFunction();
	}
	
	public function __tostring ()
	{
		return "(".$this->_forward.", ".$this->_reverse.")";
	}
	
	public function getMinLength()
	{
		return $this->_forward->getMinLength() + $this->_reverse->getMinLength();
	}
	
	public function getMinNbParts()
	{
		return $this->_forward->getMinNbParts() + $this->_reverse->getMinNbParts();
	}
	
	static function __set_state(array $array) 
	{
		$tmp = new Semantic();
		$tmp->_forward = $array['_forward'];
		$tmp->_reverse = $array['_reverse'];
		return $tmp;
	}
}
