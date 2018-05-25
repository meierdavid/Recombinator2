<?php 

namespace app\components; 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class BioFunction // Enumeration
{
	const N = 0;
	const P = 1;
	const T = 2;
	const G = 3;
	const GP = 4;
	const X = 5;
	
	private static $validBF = [
		BioFunction::G, 
		BioFunction::T, 
		BioFunction::P, 
		BioFunction::X, 
		BioFunction::GP,
		BioFunction::N];
		
	public static function isValid($b)
	{
		return in_array($b, self::$validBF);
	}
}

class OneWaySemantic
{
	private $_bioFunction = BioFunction::N;
	
	private static $minLengthBF = [
		BioFunction::G => 1000, 
		BioFunction::T => 100, 
		BioFunction::P => 40, 
		BioFunction::X => 1040, 
		BioFunction::GP => 1040,
		BioFunction::N => 0];
		
	private static $minNbPartsBF = [
		BioFunction::G => 1,
		BioFunction::T => 1,
		BioFunction::P => 1,
		BioFunction::X => 2,
		BioFunction::GP => 2,
		BioFunction::N => 0];
	
	public function __construct ($b = null)
	{
		if ($b != null && BioFunction::isValid($b))
			$this->_bioFunction = $b;
	}
	
	public function setBioFunction($b)
	{
		if (BioFunction::isValid($b))
			$this->_bioFunction = $b;
		
		return $this;
	}
	
	public function getBioFunction()
	{
		return $this->_bioFunction;
	}
	
	public function isP() 
	{
		return $this->_bioFunction == BioFunction::P || $this->_bioFunction == BioFunction::GP;
	}
	
	public function isT() 
	{
		return $this->_bioFunction == BioFunction::T;
	}
	
	public function isG() 
	{
		return $this->_bioFunction == BioFunction::G || $this->_bioFunction == BioFunction::GP;
	}
	
	public function isX() 
	{
		return $this->_bioFunction == BioFunction::X;
	}
	
	public function isN() 
	{
		return $this->_bioFunction == BioFunction::N;
	}
	
	public function combine(OneWaySemantic $s) 
	{
		if ($this->_bioFunction == BioFunction::X)
		{
			return $this;
		}
		
		if ($s->_bioFunction == BioFunction::X)
		{
			$this->_bioFunction = BioFunction::X;
			return $this;
		}
		
		switch ($this->_bioFunction)
		{
			case BioFunction::N:
				$this->_bioFunction = $s->_bioFunction;
				break;
				
			case BioFunction::P:
				if ($s->_bioFunction == BioFunction::T)
				{
					$this->_bioFunction = BioFunction::T;
				}
				else if ($s->_bioFunction == BioFunction::G || $s->_bioFunction == BioFunction::GP)
				{
					$this->_bioFunction = BioFunction::X;
				}
				break;
				
			case BioFunction::T:
				if ($s->_bioFunction == BioFunction::P || $s->_bioFunction == BioFunction::GP)
				{
					$this->_bioFunction = BioFunction::P;
				}
				break;
				
			case BioFunction::G:
				if ($s->_bioFunction == BioFunction::P || $s->_bioFunction == BioFunction::GP)
				{
					$this->_bioFunction = BioFunction::GP;
				}
				break;
				
			case BioFunction::GP:
				if ($s->_bioFunction == BioFunction::T)
				{
					$this->_bioFunction = BioFunction::G;
				}
				else if ($s->_bioFunction == BioFunction::G || $s->_bioFunction == BioFunction::GP)
				{
					$this->_bioFunction = BioFunction::X;
				}
				break;
			default:
				return $this;
		}
		
		return $this;
	}
	
	public function getMinLength()
	{
		return self::$minLengthBF[$this->_bioFunction];
	}
	
	public function getMinNbParts()
	{
		return self::$minNbPartsBF[$this->_bioFunction];
	}
	
	public static function staticCombine(OneWaySemantic $s1, OneWaySemantic $s2) 
	{
		$s3 = new OneWaySemantic($s1->getBioFunction());
		return $s3->combine($s2);
	}
	
	public function __tostring ()
	{
		switch ($this->_bioFunction)
		{
			case BioFunction::N:
				return "fN";
				break;
				
			case BioFunction::P:
				return "fP";
				break;
				
			case BioFunction::GP:
				return "fGP";
				break;
				
			case BioFunction::T:
				return "fT";
				break;
				
			case BioFunction::X:
				return "fX";
				break;
				
			case BioFunction::G:
				return "fG";
				break;
		}
		return "";
	}
	
	static function __set_state(array $array) 
	{
		$tmp = new OneWaySemantic();
		$tmp->_bioFunction = $array['_bioFunction'];
		return $tmp;
	}
}
