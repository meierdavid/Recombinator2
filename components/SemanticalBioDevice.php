<?php
namespace app\components; 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfig\Exception;

class SemanticalBioDevice 
{
	private $_id_semantics;
	private $_id_dyck_functionnal_structure;
	private $_parts 		= array();
	private $_inputs 		= array();
	private $_baseInputs 		= array();
	private $_nb_genes 			= 0;
	private $_nbP 			= 0;
	private $_subSemanticalBioDevices		= array();
	private $_symetric		= null;
	private $_minimalLogic		= "";
	private $_length		= 0;
	private $_partLength 		= [
		SemanticalBioDevice::GF => 1000, 
		SemanticalBioDevice::GR => 1000, 
		SemanticalBioDevice::TF => 100, 
		SemanticalBioDevice::TR => 100, 
		SemanticalBioDevice::PF => 40, 
		SemanticalBioDevice::PR => 40,
		SemanticalBioDevice::SF => 40, 
		SemanticalBioDevice::SR => 40, 
		SemanticalBioDevice::UF => 40, 
		SemanticalBioDevice::UR => 40, 
		SemanticalBioDevice::CE => 40, 
		SemanticalBioDevice::OE => 40, 
		SemanticalBioDevice::OI => 40, 
		SemanticalBioDevice::CI => 40, 
		SemanticalBioDevice::UOE => 40, 
		SemanticalBioDevice::UCE => 40, 
		SemanticalBioDevice::UCI => 40, 
		SemanticalBioDevice::UOI => 40];
	private $_promotersPower 	= [SemanticalBioDevice::PF => 1500, SemanticalBioDevice::PR => 1500];
	private $_semantic;
	private $_dyck_functionnal_structure;
	private $_semantics;
	private $_names;
	private $_weak_constraint;
	private $_strong_constraint;
	private $_dnf;
	private $_nb_parts = 0;
	private $_gene_at_ends;
	private $_nb_excisions = 0;
	private $_nb_inversions = 0;
	private $_permutation_class;
	private $_implementedFunctions = null;
	private static $_primeSequences;
	private static $_definedPrimeSequences = false;
	
	const PF = 0;
	const PR = 1;
	const TF = 2;
	const TR = 3;
	const UF = 4;
	const UR = 5;
	const SF = 6;
	const SR = 7;
	const GF = 8;
	const GR = 9;
	const OE = 10;
	const CE = 11;
	const OI = 12;
	const CI = 13;
	const UOE = 14;
	const UCE = 15;
	const UOI = 16;
	const UCI = 17;
	
	public function __construct ($parts = null) 
	{
		if (!SemanticalBioDevice::$_definedPrimeSequences)
		{
			SemanticalBioDevice::$_definedPrimeSequences = true;
			$primes = [new SemanticalBioDevice(),
			new SemanticalBioDevice("PF"), new SemanticalBioDevice("PR"), new SemanticalBioDevice("TF"), new SemanticalBioDevice("TR"), new SemanticalBioDevice("GF"), new SemanticalBioDevice("GR"),
			new SemanticalBioDevice("PF TR"), new SemanticalBioDevice("PF GF"), new SemanticalBioDevice("PF GR"), new SemanticalBioDevice("PR PF"), new SemanticalBioDevice("TF PR"), new SemanticalBioDevice("GF TR"),
			new SemanticalBioDevice("GF PR"), new SemanticalBioDevice("PR GR"), new SemanticalBioDevice("TF TR"), new SemanticalBioDevice("TF GR"), new SemanticalBioDevice("GF PF"), new SemanticalBioDevice("GF GR"),
			new SemanticalBioDevice("TF PR GR"), new SemanticalBioDevice("GF PF TR"), new SemanticalBioDevice("GF PF GR"), new SemanticalBioDevice("GF PR PF"), new SemanticalBioDevice("GF PR GR"), new SemanticalBioDevice("PR PF GR"),
			new SemanticalBioDevice("GF PR PF GR")];
			
			foreach ($primes as $s)
			{
				SemanticalBioDevice::$_primeSequences[$s->getSemantic()->getSemanticKey()] = $s;
			}
		}
		
		$this->_semantic = new Semantic();
		if ($parts != null)
		{
			if (is_string($parts))
				$parts = explode(" ", 
				preg_replace("#(?:\s{2,})#", ' ', 
				preg_replace("#(\t|/\xE2\x80\x8B/|\xE2\x80\x8B)#", ' ',
				trim($parts))));
			
			foreach ($parts as $value)
				$this->push_back($value);
		}
	}
	
	public function hydrate(array $donnees)
	{
		foreach ($donnees as $key => $value)
		{
			switch ($key)
			{
				case "id_semantics":
					$this->setId_semantics($value);
					break;
				case "id_dyck_functionnal_structure":
					$this->setId_dyck_functionnal_structure($value);
					break;
				case "weak_constraint":
					$this->_weak_constraint = (bool) intval($value);
					break;
				case "dyck_functionnal_structure":
					$this->_dyck_functionnal_structure = $value;
					break;
				case "semantics":
					$this->_semantics = $value;
					break;
				default:
					$methode = 'set'.ucfirst($key);
					if (method_exists($this, $methode)) $this->$methode($value);
			}
		}
		$this->_semantics = fgets($this->_semantics);
		
		$nomsOuvrantUtilises = []; $nomsFermantUtilises = [];
		$var = 97;
		
		for ($i = 0; $i < strlen($this->_semantics); ++$i)
		{
			if (ord($this->_semantics[$i]) > 0)
				foreach (explode(" ",SemanticalBioDevice::$_primeSequences[ord($this->_semantics[$i])]->to_string()) as $value)
					$this->push_back($value);
				
				if ($i < strlen($this->_dyck_functionnal_structure))
				{
					switch ($this->_dyck_functionnal_structure[$i])
					{
						case '(':
							while (array_key_exists($var, $nomsOuvrantUtilises))
								++$var;
							$this->addSite('(', chr($var));
							$nomsOuvrantUtilises[$var] = "";
							break;
						case ')': 
							while (array_key_exists($var, $nomsFermantUtilises))
								--$var;
							$this->addSite(')', chr($var));
							$nomsFermantUtilises[$var] = "";
							break;
						case '[': 
							while (array_key_exists($var, $nomsOuvrantUtilises))
								++$var;
							$this->addSite('[', chr($var));
							$nomsOuvrantUtilises[$var] = "";
							break;
						case ']': 
							while (array_key_exists($var, $nomsFermantUtilises))
								--$var;
							$this->addSite(']', chr($var));
							$nomsFermantUtilises[$var] = "";
							break;
					}
				}
		}
		
		if (isset($donnees["dnf"]))
		{
			$this->setImplementedFunction($donnees["dnf"]);
		}
	}
	
	public function setAnotherColours ($colours)
	{
		$this->_inputs = $this->_baseInputs;
		if (strlen($colours) != count($this->_inputs))
			throw new \Exception("The number of colours doesn't equal to number of inputs");
		
		$inputs = [];
		
		for ($i = 0; $i < strlen($colours); ++$i)
			$inputs[$colours[$i]] = $this->_inputs[chr(97+$i)];
		/*$i = 0;
		foreach ($this->_inputs as $key => $value)
			$inputs[$colours[$i++]] = $value;*/
		
		$this->_inputs = $inputs;
	}
	
	public function push_back ($part)
	{
		$part = trim(strtoupper($part));
		switch ($part)
		{
			case "PF" :
				$this->_parts[] = SemanticalBioDevice::PF;
				$this->_length += $this->_partLength[SemanticalBioDevice::PF];
				++$this->_nbP;
				break;
				
			case "PR" :
				$this->_parts[] = SemanticalBioDevice::PR;
				$this->_length += $this->_partLength[SemanticalBioDevice::PR];
				++$this->_nbP;
				break;
				
			case "TF" :
				$this->_parts[] = SemanticalBioDevice::TF;
				$this->_length += $this->_partLength[SemanticalBioDevice::TF];
				break;
				
			case "TR" :
				$this->_parts[] = SemanticalBioDevice::TR;
				$this->_length += $this->_partLength[SemanticalBioDevice::TR];
				break;
				
			case "GF" :
				$this->_parts[] = SemanticalBioDevice::GF;
				$this->_length += $this->_partLength[SemanticalBioDevice::GF];
				++$this->_nb_genes;
				break;
				
			case "GR" :
				$this->_parts[] = SemanticalBioDevice::GR;
				$this->_length += $this->_partLength[SemanticalBioDevice::GR];
				++$this->_nb_genes;
				break;
				
			default:
				if (preg_match("/^(SF|SR|\[|\]|\(|\))(\w)/", $part, $matches) === 1)
					$this->addSite($matches[1], strtolower($matches[2]));
				else
					throw new \Exception('Parte invalide : '.$part.'.');
		}
	}
	
	public function addSite ($site, $input)
	{
		switch ($site)
		{
			case "SF" :
				$this->_parts[] = SemanticalBioDevice::SF;
				$this->_length += $this->_partLength[SemanticalBioDevice::SF];
				break;
				
			case "SR" :
				$this->_parts[] = SemanticalBioDevice::SR;
				$this->_length += $this->_partLength[SemanticalBioDevice::SR];
				break;
				
			case "[" :
				$this->_parts[] = SemanticalBioDevice::OE;
				$this->_length += $this->_partLength[SemanticalBioDevice::OE];
				break;
				
			case "]" :
				$this->_parts[] = SemanticalBioDevice::CE;
				$this->_length += $this->_partLength[SemanticalBioDevice::CE];
				break;
			case "(" :
				$this->_parts[] = SemanticalBioDevice::OI;
				$this->_length += $this->_partLength[SemanticalBioDevice::OI];
				break;
				
			case ")" :
				$this->_parts[] = SemanticalBioDevice::CI;
				$this->_length += $this->_partLength[SemanticalBioDevice::CI];
				break;
				
			default: 
				throw new \Exception('Ce n\'est pas un site.');
		}
		if (key_exists($input, $this->_inputs) && count($this->_inputs[$input]) == 1)
			$this->_inputs[$input][1] = count($this->_parts)-1;
		else if (!key_exists($input, $this->_inputs))
			$this->_inputs[$input][0] = count($this->_parts)-1;
		else
			throw new \Exception('Les sites doivent être par paire.');
		
		if (count($this->_inputs) > 6)
			throw new \Exception('Le nombre maximum d\'inputs est limité à 6.');
		$this->_baseInputs = $this->_inputs;
	}
	
	public function findSiteName($i)
	{
		$found = false;
		$name;
		
		foreach ($this->_inputs as $key => $input)
			if ($input[0] == $i || $input[1] == $i)
			{
				$found = true;
				$name = $key;
			}
			
			if (!$found)
				throw new \Exception("There has been an attempt to access an unknown site.");
			
			return $name;
	}
	
	public function toUnicodePart ($part)
	{
		switch ($part)
		{
			case SemanticalBioDevice::PF : $return = "\u21B1"; break;
			case SemanticalBioDevice::PR : $return = "\u21B2"; break;
			case SemanticalBioDevice::TF : $return = "\u22A4"; break;
			case SemanticalBioDevice::TR : $return = "\u22A5"; break;
			case SemanticalBioDevice::UF : $return = "\u25B7"; break;
			case SemanticalBioDevice::UR : $return = "\u25C1"; break;
			case SemanticalBioDevice::SF : $return = "\u25B6"; break;
			case SemanticalBioDevice::SR : $return = "\u25C0"; break;
			case SemanticalBioDevice::GF : $return = "G"; break;
			case SemanticalBioDevice::GR : $return = "\u2141"; break;
			case SemanticalBioDevice::OI : $return = "("; break;
			case SemanticalBioDevice::CI : $return = ")"; break;
			case SemanticalBioDevice::UOI : $return = "\u2987"; break;
			case SemanticalBioDevice::UCI : $return = "\u2988"; break;
			case SemanticalBioDevice::OE : $return = "["; break;
			case SemanticalBioDevice::CE : $return = "]"; break;
			case SemanticalBioDevice::UOE : $return = "\u2AFF"; break;
		}
		
		return json_decode('"'.$return.'"');
	}
	
	public function to_string()
	{
		$semanticalBioDevice = "";
		
		foreach ($this->_parts as $key => $part)
		{
			switch ($part)
			{
				case SemanticalBioDevice::PF : $semanticalBioDevice .= "PF "; break;
				case SemanticalBioDevice::PR : $semanticalBioDevice .= "PR "; break;
				case SemanticalBioDevice::TF : $semanticalBioDevice .= "TF "; break;
				case SemanticalBioDevice::TR : $semanticalBioDevice .= "TR "; break;
				case SemanticalBioDevice::UF :
				case SemanticalBioDevice::UR :
				case SemanticalBioDevice::SF :
				case SemanticalBioDevice::SR :
					if ($part == SemanticalBioDevice::UF)
						$semanticalBioDevice .= "UF";
					
					else if ($part == SemanticalBioDevice::UR)
						$semanticalBioDevice .= "UR";
					
					else if ($part == SemanticalBioDevice::SF)
						$semanticalBioDevice .= "SF";
					
					else if ($part == SemanticalBioDevice::SR)
						$semanticalBioDevice .= "SR";
					
					$semanticalBioDevice .= $this->findSiteName($key);
					$semanticalBioDevice .= " ";
					break;
				case SemanticalBioDevice::GF : $semanticalBioDevice .= "GF "; break;
				case SemanticalBioDevice::GR : $semanticalBioDevice .= "GR "; break;
				case SemanticalBioDevice::OI : $semanticalBioDevice .= "(".$this->findSiteName($key)." "; break;
				case SemanticalBioDevice::CI : $semanticalBioDevice .= ")".$this->findSiteName($key)." "; break;
				case SemanticalBioDevice::UOI : $semanticalBioDevice .= "\u2987".$this->findSiteName($key)." "; break;
				case SemanticalBioDevice::UCI : $semanticalBioDevice .= "\u2988".$this->findSiteName($key)." "; break;
				case SemanticalBioDevice::OE : $semanticalBioDevice .= "[".$this->findSiteName($key)." "; break;
				case SemanticalBioDevice::CE : $semanticalBioDevice .= "]".$this->findSiteName($key)." "; break;
				case SemanticalBioDevice::UOE : $semanticalBioDevice .= "\u2AFF".$this->findSiteName($key)." "; break;
			}
		}
		return trim($semanticalBioDevice);
	}
	
	public function toHTML()
	{
		$semanticalBioDevice = "";
		$siteName;
		
		foreach ($this->_parts as $key => $part)
		{
			if ($key > 0 && 
				($this->_parts[$key-1] == $this->_parts[$key]) && 
				($this->_parts[$key] == SemanticalBioDevice::UF || $this->_parts[$key] == SemanticalBioDevice::UR) &&
				($this->findSiteName($key-1) == $this->findSiteName($key))
				)
				continue;
			
			switch ($part)
			{
				case SemanticalBioDevice::PF : $semanticalBioDevice .= '<span class="promoter">' . $this->toUnicodePart(SemanticalBioDevice::PF) . "</span> "; break;
				case SemanticalBioDevice::PR : $semanticalBioDevice .= '<span class="promoter">' . $this->toUnicodePart(SemanticalBioDevice::PR) . "</span> "; break;
				case SemanticalBioDevice::TF : $semanticalBioDevice .= '<span class="terminator">' . $this->toUnicodePart(SemanticalBioDevice::TF) . "</span> "; break;
				case SemanticalBioDevice::TR : $semanticalBioDevice .= '<span class="terminator">' . $this->toUnicodePart(SemanticalBioDevice::TR) . "</span> "; break;
				case SemanticalBioDevice::UF :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::UF) . "</span> ";
					break;
				case SemanticalBioDevice::UR :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::UR) . "</span> ";
					break;
				case SemanticalBioDevice::SF :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::SF) . "</span> ";
					break;
				case SemanticalBioDevice::SR :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::SR) . "</span> ";
					break;
				case SemanticalBioDevice::OI :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::OI) . "</span> ";
					break;
				case SemanticalBioDevice::CI :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::CI) . "</span> ";
					break;
				case SemanticalBioDevice::UOI :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::UOI) . "</span> ";
					break;
				case SemanticalBioDevice::UCI : 
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::UCI) . "</span> ";
					break;
				case SemanticalBioDevice::OE :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::OE) . "</span> ";
					break;
				case SemanticalBioDevice::CE : 
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::CE) . "</span> ";
					break;
				case SemanticalBioDevice::UOE :
					$siteName = $this->findSiteName($key);
					$semanticalBioDevice .= '<span class="site_' . $siteName . '">' . $this->toUnicodePart(SemanticalBioDevice::UOE) . "</span> ";
					break;
				
				case SemanticalBioDevice::GF : $semanticalBioDevice .= '<span class="gene">' . $this->toUnicodePart(SemanticalBioDevice::GF) . "</span> "; break;
				case SemanticalBioDevice::GR : $semanticalBioDevice .= '<span class="gene">' . $this->toUnicodePart(SemanticalBioDevice::GR) . "</span> "; break;
			}
		}
		
		return trim($semanticalBioDevice);
	}
	
	private function reversePart(&$parts, $i)
	{
		switch ($parts[$i])
		{
			case SemanticalBioDevice::PF : $parts[$i] = SemanticalBioDevice::PR; break;
			case SemanticalBioDevice::PR : $parts[$i] = SemanticalBioDevice::PF; break;
			case SemanticalBioDevice::TF : $parts[$i] = SemanticalBioDevice::TR; break;
			case SemanticalBioDevice::TR : $parts[$i] = SemanticalBioDevice::TF; break;
			case SemanticalBioDevice::UF : $parts[$i] = SemanticalBioDevice::UR; break;
			case SemanticalBioDevice::UR : $parts[$i] = SemanticalBioDevice::UF; break;
			case SemanticalBioDevice::SF : $parts[$i] = SemanticalBioDevice::SR; break;
			case SemanticalBioDevice::SR : $parts[$i] = SemanticalBioDevice::SF; break;
			case SemanticalBioDevice::GF : $parts[$i] = SemanticalBioDevice::GR; break;
			case SemanticalBioDevice::GR : $parts[$i] = SemanticalBioDevice::GF; break;
			case SemanticalBioDevice::OI : $parts[$i] = SemanticalBioDevice::CI; break;
			case SemanticalBioDevice::CI : $parts[$i] = SemanticalBioDevice::OI; break;
			case SemanticalBioDevice::UOI : $parts[$i] = SemanticalBioDevice::UCI; break;
			case SemanticalBioDevice::UCI : $parts[$i] = SemanticalBioDevice::UOI; break;
			case SemanticalBioDevice::OE : $parts[$i] = SemanticalBioDevice::CE; break;
			case SemanticalBioDevice::CE : $parts[$i] = SemanticalBioDevice::OE; break;
			case SemanticalBioDevice::UOE : $parts[$i] = SemanticalBioDevice::UCE; break;
			case SemanticalBioDevice::UCE : $parts[$i] = SemanticalBioDevice::UOE; break;
		}
	}
	
	private function setAMIUsed ($inputName, $i)
	{
		if ($this->_parts[$this->_inputs[$inputName][$i]] == SemanticalBioDevice::SF)
			$this->_parts[$this->_inputs[$inputName][$i]] = SemanticalBioDevice::UF;
		
		else if ($this->_parts[$this->_inputs[$inputName][$i]] == SemanticalBioDevice::SR)
			$this->_parts[$this->_inputs[$inputName][$i]] = SemanticalBioDevice::UR;
		
		else if ($this->_parts[$this->_inputs[$inputName][$i]] == SemanticalBioDevice::OI)
			$this->_parts[$this->_inputs[$inputName][$i]] = SemanticalBioDevice::UOI;
		
		else if ($this->_parts[$this->_inputs[$inputName][$i]] == SemanticalBioDevice::CI)
			$this->_parts[$this->_inputs[$inputName][$i]] = SemanticalBioDevice::UCI;
		
		else if ($this->_parts[$this->_inputs[$inputName][$i]] == SemanticalBioDevice::OE)
			$this->_parts[$this->_inputs[$inputName][$i]] = SemanticalBioDevice::UOE;
		
		else if ($this->_parts[$this->_inputs[$inputName][$i]] == SemanticalBioDevice::CE)
			$this->_parts[$this->_inputs[$inputName][$i]] = SemanticalBioDevice::UCE;
		
		else
			throw new \exception($inputName . " is already an used site and cannot be reset to used.");
	}
	
	private function setSiteUsed ($inputName)
	{
		if (key_exists($inputName, $this->_inputs))
		{
			$this->setAMIUsed ($inputName, 0);
			$this->setAMIUsed ($inputName, 1);
		}
		else
			throw new \exception($inputName .  " does not exist and cannot be set to used.");
	}
	
	private function integraseReverse ($inputName)
	{
		if (key_exists($inputName, $this->_inputs))
		{
			array_splice(
				$this->_parts, 
				$this->_inputs[$inputName][0]+1, 
				$this->_inputs[$inputName][1]-$this->_inputs[$inputName][0]-1, 
				array_reverse(
					array_slice(
						$this->_parts, 
						$this->_inputs[$inputName][0]+1, 
						$this->_inputs[$inputName][1]-$this->_inputs[$inputName][0]-1)));
				
				foreach ($this->_inputs as &$input)
				{
					if ($input[0] > $this->_inputs[$inputName][0] && $input[0] < $this->_inputs[$inputName][1])
					{
						$second = $this->_inputs[$inputName][1] - ($input[0] - $this->_inputs[$inputName][0]);
						$input[0] = $second - ($input[1] - $input[0]);
						$input[1] = $second;
					}
				}
				
				for ($i = $this->_inputs[$inputName][0]+1; $i < $this->_inputs[$inputName][1]; ++$i)
					$this->reversePart($this->_parts, $i);
				
				$this->setSiteUsed($inputName);
		}
		else
			throw new \exception($inputName .  " does not exist.");
	}
	
	private function integraseExcise ($inputName)
	{
		if (key_exists($inputName, $this->_inputs))
		{
			$begin = $this->_inputs[$inputName][0]+1;
			$length = $this->_inputs[$inputName][1]-$this->_inputs[$inputName][0]-1;
			
			$difference = $this->_inputs[$inputName][1] - $this->_inputs[$inputName][0] - 1;
			
			foreach ($this->_inputs as &$input)
			{
				if ($input[0] > $this->_inputs[$inputName][0] && $input[0] < $this->_inputs[$inputName][1])
				{
					$input[0] = -1;
					$input[1] = -1;
				}
			}
			
			foreach ($this->_inputs as &$input)
			{
				if ($input[0] > $this->_inputs[$inputName][0] && $input[0] != -1)
					$input[0] -= $difference;
				
				if ($input[1] > $this->_inputs[$inputName][0] && $input[1] != -1)
					$input[1] -= $difference;
			}
			
			for ($i = $begin; $i < $this->_inputs[$inputName][1]; ++$i)
			{
				if ($this->_parts[$i] == SemanticalBioDevice::GR || $this->_parts[$i] == SemanticalBioDevice::GF)
					--$this->_nb_genes;
				
				if ($this->_parts[$i] == SemanticalBioDevice::PR || $this->_parts[$i] == SemanticalBioDevice::PF)
					--$this->_nbP;
			}
			
			array_splice($this->_parts, $begin, $length);
			$this->setSiteUsed($inputName);
		}
		else
			throw new \exception($inputName .  " does not exist.");
	}
	
	public function integrase($inputName)
	{
		$inputName = strtolower($inputName);
		if (key_exists($inputName, $this->_inputs))
		{
			if ($this->_inputs[$inputName][0] != -1 && $this->_inputs[$inputName][1] != -1)
			{
				if ($this->isAnUnusedInversion($inputName))
					$this->integraseReverse($inputName);
				else if ($this->isAnUnusedExcision($inputName))
					$this->integraseExcise($inputName);
				else
					throw new \exception( "Impossible to activate site \"" . $inputName . "\", it is used or does not exit.");
			}
		}
		else
			throw new \exception( "Impossible to activate site \"" . $inputName . "\", it is used or does not exit.");
	}
	
	public function isAnUnusedExcision ($inputName)
	{
		return 
			($this->_parts[$this->_inputs[$inputName][0]] == SemanticalBioDevice::SF && $this->_parts[$this->_inputs[$inputName][1]] == SemanticalBioDevice::SR)
			|| ($this->_parts[$this->_inputs[$inputName][0]] == SemanticalBioDevice::SR && $this->_parts[$this->_inputs[$inputName][1]] == SemanticalBioDevice::SF)
			|| ($this->_parts[$this->_inputs[$inputName][0]] == SemanticalBioDevice::OE && $this->_parts[$this->_inputs[$inputName][1]] == SemanticalBioDevice::CE);
	}
	
	public function isAnUnusedInversion ($inputName)
	{
		return 
			($this->_parts[$this->_inputs[$inputName][0]] == SemanticalBioDevice::SF && $this->_parts[$this->_inputs[$inputName][1]] == SemanticalBioDevice::SF)
			|| ($this->_parts[$this->_inputs[$inputName][0]] == SemanticalBioDevice::SR && $this->_parts[$this->_inputs[$inputName][1]] == SemanticalBioDevice::SR)
			|| ($this->_parts[$this->_inputs[$inputName][0]] == SemanticalBioDevice::OI && $this->_parts[$this->_inputs[$inputName][1]] == SemanticalBioDevice::CI);
	}
	
	public function isGeneActivated()
	{
		$activated = false; $notValidActivation = false;
		$powerPR = 0; $powerPF = 0;
		$hasPR = false; $hasPF = false;
		
		if ($this->_nb_genes && $this->_nbP)
		{
			foreach ($this->_parts as $forward)
			{
				if ($hasPF && $forward != SemanticalBioDevice::GF)
					$powerPF -= $this->_partLength[$forward];
				
				if($forward == SemanticalBioDevice::TF)
				{
					$powerPF = 0;
					$hasPF = false;
				}
				else if($forward == SemanticalBioDevice::GF)
				{
					if ($hasPF)
					{
						$activated = true;
						break;
					}
				}
				else if($forward == SemanticalBioDevice::PF)
				{
					$powerPF = $this->_promotersPower[SemanticalBioDevice::PF];
					$hasPF = true;
				}
			}
			
			if (!$activated)
			{
				foreach (array_reverse($this->_parts, true) as $reverse)
				{
					if ($hasPR && $reverse != SemanticalBioDevice::GR)
						$powerPR -= $this->_partLength[$reverse];
					
					if($reverse == SemanticalBioDevice::TR)
					{
						$powerPR = 0;
						$hasPR = false;
					}
					else if($reverse == SemanticalBioDevice::GR)
					{
						if ($hasPR)
						{
							$activated = true;
							break;
						}
					}
					else if($reverse == SemanticalBioDevice::PR)
					{
						$powerPR = $this->_promotersPower[SemanticalBioDevice::PR];
						$hasPR = true;
					}
				}
			}
		}
		
		return (bool)$activated;
	}
	
	private function buildSymetric ()
	{
		if ($this->_symetric == null)
		{
			$this->_symetric = new SemanticalBioDevice();
			$this->_symetric->_parts = array_reverse($this->_parts);
			$this->_symetric->_inputs = $this->_inputs;
			$this->_symetric->_nbP = $this->_nbP;
			$this->_symetric->_nb_genes = $this->_nb_genes;
			
			for ($i = 0; $i < count($this->_symetric->_parts); ++$i)
				$this->reversePart($this->_symetric->_parts, $i);
			
			foreach ($this->_symetric->_inputs as &$input)
			{
				$input0 = $input[0];
				$input[0] = $this->_symetric->size()-$input[1]-1;
				$input[1] = $this->_symetric->size()-$input0-1;
			}
		}
	}
	
	public function hasEnoughSites ()
	{
		foreach ($this->_inputs as $input)
			if (!key_exists(0, $input) || !key_exists(1, $input) || $input[0] == -1 || $input[1] == -1)
				return false;
			
			return true;
	}
	
	public function hasNoSmashingPromoters ()
	{
		if ($this->_nbP <= 1)
			return true;
		
		if (!$this->isGeneActivated())
			return true;
		
		$smashed = false;
		$hasPR = false; $hasPF = false;
		$powerPR = 0; $powerPF = 0;
		
		foreach ($this->_parts as $forward)
		{
			if ($hasPF && $forward != SemanticalBioDevice::GF)
				$powerPF -= $this->_partLength[$forward];
			
			if($forward == SemanticalBioDevice::PF)
			{
				$powerPF = $this->_promotersPower[SemanticalBioDevice::PF];
				$hasPF = true;
			}
			else if($forward == SemanticalBioDevice::TF)
			{
				$hasPF = false;
				$powerPF = 0;
			}
			else if($forward == SemanticalBioDevice::GF && $hasPF)
			{
				if ($powerPF > 0)
				{
					$smashed = false;
					break;
				}
				else
				{
					$hasPF = false;
					$powerPF = 0;
				}
			}
			else if($forward == SemanticalBioDevice::PR && $hasPF)
			{
				$hasPF = false;
				$powerPF = 0;
				$smashed = true;
			}
		}
		
		if ($smashed)
		{
			foreach (array_reverse($this->_parts, true) as $reverse)
			{
				if ($hasPR && $reverse != SemanticalBioDevice::GR)
					$powerPR -= $this->_partLength[$reverse];
				
				if($reverse == SemanticalBioDevice::PR)
				{
					$powerPR = $this->_promotersPower[SemanticalBioDevice::PR];
					$hasPR = true;
				}
				
				else if($reverse == SemanticalBioDevice::TR)
				{
					$hasPR = false;
					$powerPR = 0;
				}
				
				else if($reverse == SemanticalBioDevice::GR && $hasPR)
				{
					if ($powerPR > 0)
					{
						$smashed = false;
						break;
					}
					else
					{
						$hasPR = false;
						$powerPR = 0;
					}
				}
				
				else if($reverse == SemanticalBioDevice::PF && $hasPR)
				{
					$hasPR = false;
					$powerPR = 0;
					$smashed = true;
				}
			}
		}
		
		return !$smashed;
	}
	
	private function makeSubSemanticalBioDevice ($i)
	{
		if ($i > 0)
		{
			$this->_subSemanticalBioDevices[$i-1] = clone $this;
			$inputs = $this->_subSemanticalBioDevices[$i-1]->inputNamesList();
			$activation = $i;
			
			for ($j = 0; $j < count($this->_inputs); ++$j)
			{
				if ($activation % 2 == 1)
					$this->_subSemanticalBioDevices[$i-1]->integrase($inputs[count($this->_inputs)-$j-1]);
				$activation = $activation >> 1;
			}
		}
	}
	
	public function getSubSemanticalBioDevice($i)
	{
		if (!$i)
			return $this;
		
		else if (key_exists($i-1, $this->_subSemanticalBioDevices))
			return $this->_subSemanticalBioDevices[$i-1];
		
		else
		{
			$this->makeSubSemanticalBioDevice($i);
			return $this->_subSemanticalBioDevices[$i-1];
		}
	}
	
	public function hasNoSubsemanticalBioDevicesSmashingPromoters()
	{
		if ($this->_nbP <= 1)
			return true;
		
		$smashed = false;
		$numberSub = pow(2, count($this->_inputs));
		
		for ($i = 1; $i < $numberSub; ++$i)
		{
			$subSemanticalBioDevice = $this->getSubSemanticalBioDevice($i);
			if (!$subSemanticalBioDevice->hasNoSmashingPromoters())
			{
				$smashed = true;
				break;
			}
		}
		
		return !$smashed;
	}
	
	public function hasNoSubsemanticalBioDevicesFadingPromoters ()
	{
		if ($this->_nbP == 0 || $this->_nb_genes == 0)
			return true;
		
		$faded = false;
		$numberSub = pow(2, count($this->_inputs));
		
		for ($i = 1; $i < $numberSub; ++$i)
		{
			$subSemanticalBioDevice = $this->getSubSemanticalBioDevice($i);
			if ($subSemanticalBioDevice->isGeneActivated() == -1)
			{
				$faded = true;
				break;
			}
		}
		
		return !$faded;
	}
	
	public function hasValidPromoters ()
	{
		return $this->hasNoSmashingPromoters() && $this->isGeneActivated() != -1 && $this->hasNoSubsemanticalBioDevicesSmashingPromoters() && $this->hasNoSubsemanticalBioDevicesFadingPromoters();
	}
	
	public function isValid ()
	{
		return $this->hasEnoughSites();
	}
	
	public function setMinimalLogic ($minimalLogic)
	{
		$this->_minimalLogic = $minimalLogic;
	}
	
	public function setId_semantics ($id)
	{
		$this->_id_semantics = $id;
	}
	
	public function setId_dyck_functionnal_structure ($id)
	{
		$this->_id_dyck_functionnal_structure = $id;
	}
	
	public function exceptionsIfInvalid ()
	{
		if (!$this->hasEnoughSites())
			throw new \exception ("Sites must be in pairs.");
	}
	
	public function inputNamesList() 
	{ 
		$list = array_keys($this->_inputs); 
		sort($list); 
		return $list; 
	}
	
	public function buildSemantic()
	{
		foreach ($this->_parts as $it)
		{
			$sem = $this->getSemanticPart($it);
			
			if (!$sem->isN())
				$this->_semantic->combine($sem);
		}
	}
	
	public function getSemantic()
	{
		if ($this->_semantic->isN())
			$this->buildSemantic();
		
		return $this->_semantic;
	}
	
	public function getSemanticPart($s)
	{
		$sem = new Semantic();
		switch ($s)
		{
			case SemanticalBioDevice::PF:
				$sem = new Semantic(BioFunction::P, BioFunction::N);
				break;
			case SemanticalBioDevice::PR:
				$sem = new Semantic(BioFunction::N, BioFunction::P);
				break;
			case SemanticalBioDevice::TF:
				$sem = new Semantic(BioFunction::T, BioFunction::N);
				break;
			case SemanticalBioDevice::TR:
				$sem = new Semantic(BioFunction::N, BioFunction::T);
				break;
			case SemanticalBioDevice::GF:
				$sem = new Semantic(BioFunction::G, BioFunction::N);
				break;
			case SemanticalBioDevice::GR:
				$sem = new Semantic(BioFunction::N, BioFunction::G);
				break;
			case SemanticalBioDevice::UR:
			case SemanticalBioDevice::UF:
			case SemanticalBioDevice::SR:
			case SemanticalBioDevice::SF:
				break;
		}
		
		return $sem;
	}
	
	private static function swap (&$a, $i1, $i2)
	{
		$aux = $a[$i2];
		$a[$i2] = $a[$i1];
		$a[$i1] = $aux;
		return $a;
	}

	private static function reverse (&$a, $i1, $i2)
	{
		array_splice($a, $i1, $i2, 
			array_reverse(
				array_slice($a, $i1, $i2)));
		return $a;
	}

	private static function next_permutation (&$a)
	{
		$convert = false;
		if (is_string($a))
		{
			$a = str_split($a);
			$convert = true;
		}
		
		if (count($a) <= 1)
		{
			if ($convert)
				$a = implode($a);
			return false;
		}
		
		$first = 0;
		$last = count($a);
		$i = $last-1;
		
		while (1)
		{
			$i1 = $i; $i2;
			
			if ($a[--$i] < $a[$i1])
			{
				$i2 = $last;
				while ($a[$i] >= $a[--$i2]);
				self::swap($a, $i, $i2);
				self::reverse($a, $i1, $last);
		
				if ($convert)
					$a = implode($a);
				return true;
			}
			if ($i == $first)
			{
				self::reverse($a, $first, $last);
		
				if ($convert)
					$a = implode($a);
				return false;
			}
		}
	}
	
	private function buildImplementedFunctions ()
	{
		$base = "";
		for ($i = 0; $i < $this->howManyInputs(); ++$i)
			$base .= chr($i+97);
		$perm = $base;
			$this->_subSemanticalBioDevices = [];
		
		do
		{
			$inputsSave = $this->_inputs;
			$this->setAnotherColours($perm);
			
			$veritas = new VeritasSemanticalBioDevice($this);
			$this->_implementedFunctions[$perm] = $veritas->outputToString();
			
			$this->_inputs = $inputsSave;
			$this->_subSemanticalBioDevices = [];
		} while (self::next_permutation($perm));
	}
	
	public function getImplementedFunctions()
	{
		if ($this->_implementedFunctions === null)
		{
			$this->buildImplementedFunctions();
		}
		return $this->_implementedFunctions;
	}
	
	public function setImplementedFunction($dnf)
	{	
		if ($this->_implementedFunctions === null)
		{
			$this->buildImplementedFunctions();
		}
		
		$key = array_search($dnf, $this->_implementedFunctions);
		
		if ($key !== false)
		{
			$this->setAnotherColours($key);
		}
		else 
		{
			throw new \Exception("This BioDevice can't implemente this boolean function");
		}
	}
	
	private function setStrong_constraint ($val)
	{
		$this->_strong_constraint = $val;
	}
	
	private function setNb_parts ($val)
	{
		$this->_nb_parts = $val;
	}
	
	private function setGene_at_ends ($val)
	{
		$this->_gene_at_ends = $val;
	}
	
	private function setNb_excisions ($val)
	{
		$this->_nb_excisions = $val;
	}
	
	private function setNb_inversions ($val)
	{
		$this->_nb_inversions = $val;
	}
	
	private function setDnf ($val)
	{
		$this->_dnf = $val;
	}
	
	private function setPermutationClass ($val)
	{
		$this->_permutation_class = $val;
	}
		
	public function getStrong_constraint () { return $this->_strong_constraint; }
	public function getNb_parts () { return $this->_nb_parts; }
	public function getGene_at_ends () { return $this->_gene_at_ends; }
	public function getNb_excisions () { return $this->_nb_excisions; }
	public function getNb_inversions () { return $this->_nb_inversions; }
	public function getNbP () { return $this->_nbP; }
	public function getNb_genes () { return $this->_nb_genes; }
	public function getSymetric () { $this->buildSymetric(); return $this->_symetric; }
	public function howManyInputs () { return count($this->_inputs); }
	public function size () { return count($this->_parts); }
	public function getMinimalLogic () { return $this->_minimalLogic; }
	public function getLength () { return $this->_length; }
	public function getId_semantics () { return $this->_id_semantics; }
	public function getId_dyck_functionnal_structure () { return $this->_id_dyck_functionnal_structure; }
	public function getWeak_constraint () { return $this->_weak_constraint; }
	public function getNames () { return $this->_names; }
	
	public function getModel ()
	{
		$a = [
		"id_semantics" => $this->_id_semantics,
		"id_dyck_functionnal_structure" => $this->_id_dyck_functionnal_structure,
		"nb_parts" => count($this->_parts),
		"inputs" => $this->_inputs,
		"nb_genes" => $this->_nb_genes,
		"length" => $this->_length,
		"weak_constraint" => (bool) $this->_weak_constraint,
		"strong_constraint" => (bool) $this->_strong_constraint,
        "dnf" => $this->_dnf,
        "permutation_class" => $this->_permutation_class,
        "nb_inputs" => $this->howManyInputs(),
        "semantics" => $this->_parts,
		"gene_at_ends" => $this->_gene_at_ends,
		"dyck_functionnal_structure" => $this->_dyck_functionnal_structure,
		"nb_excisions" => $this->_nb_excisions,
		"nb_inversions" => $this->_nb_inversions,
		"architecture" => $this->toHTML()
		];
		
		return $a;
	}
	
	static function __set_state(array $array) 
	{
		$tmp = new SemanticalBioDevice();
		$tmp->_id_semantics 		= $array['_id_semantics'];
		$tmp->_id_dyck_functionnal_structure 		= $array['_id_dyck_functionnal_structure'];
		$tmp->_parts 		= $array['_parts'];
		$tmp->_inputs 		= $array['_inputs'];
		$tmp->_baseInputs 		= $array['_baseInputs'];
		$tmp->_nb_genes 			= $array['_nb_genes'];
		$tmp->_nbP			= $array['_nbP'];
		$tmp->_subSemanticalBioDevices 		= $array['_subSemanticalBioDevices'];
		$tmp->_symetric 		= $array['_symetric'];
		$tmp->_minimalLogic 	= $array['_minimalLogic'];
		$tmp->_length 		= $array['_length'];
		$tmp->_names 		= $array['_names'];
		$tmp->_weak_constraint       = (bool) intval($array['_weak_constraint']);
		return $tmp;
	}
}
