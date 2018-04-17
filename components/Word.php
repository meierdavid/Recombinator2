<?php
#################################################
#						#
#	Word.php				#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 
    namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
    
    class Word 
    {
	private $_id_w;
	private $_symbols 		= array();
	private $_inputs 		= array();
	private $_nbG 			= 0;
	private $_nbP 			= 0;
	private $_subWords		= array();
	private $_symetric		= null;
	private $_minimalLogic		= "";
	private $_length		= 0;
	private $_symbolLength 		= [Word::GF => 1000, Word::GR => 1000, Word::TF => 100, Word::TR => 100, Word::PF => 40, Word::PR => 40, Word::SF => 40, Word::SR => 40, Word::UF => 40, Word::UR => 40];
	private $_promotersPower 	= [Word::PF => 1500, Word::PR => 1500];
	private $_semantic;
	private $_dyckWord;
	private $_semanticsList;
	private $_names;
	private $_weakConstraint        = true;
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
	
	public function __construct ($symbols = null) 
	{
            if (!Word::$_definedPrimeSequences)
            {
                Word::$_definedPrimeSequences = true;
                $primes = [new Word(), // 0
                    new Word("PF"), new Word("PR"), new Word("TF"), new Word("TR"), new Word("GF"), new Word("GR"), // 1, 2, 3, 4, 5, 6
                    new Word("PF TR"), new Word("PF GF"), new Word("PF GR"), new Word("PR PF"), new Word("TF PR"), new Word("GF TR"), // 7, 8, 9, 10, 11, 12
                    new Word("GF PR"), new Word("PR GR"), new Word("TF TR"), new Word("TF GR"), new Word("GF PF"), new Word("GF GR"), // 13, 14, 15, 16, 17, 18
                    new Word("TF PR GR"), new Word("GF PF TR"), new Word("GF PF GR"), new Word("GF PR PF"), new Word("GF PR GR"), new Word("PR PF GR"), // 19, 20, 21, 22, 23, 24
                    new Word("GF PR PF GR")];
                
                foreach ($primes as $s)
                {
                    Word::$_primeSequences[$s->getSemantic()->getSemanticValue()] = $s;
                }
            }
	
            $this->_semantic = new Semantic();
	    if ($symbols != null)
	    {
		if (is_string($symbols))
		    $symbols = explode(" ", 
			preg_replace("#(?:\s{2,})#", ' ', 
			preg_replace("#(\t|/\xE2\x80\x8B/|\xE2\x80\x8B)#", ' ',
			trim($symbols))));
		
		foreach ($symbols as $value)
		    $this->push_back($value);
	    }
	}
	
	public function hydrate(array $donnees)
	{
	    foreach ($donnees as $key => $value)
	    {
		switch ($key)
		{
		    case "id_s":
		    case "id":
			$this->setId_w($value);
		    break;
		    case "weak_constraint":
			$this->_weakConstraint = (bool) intval($value);
		    break;
		    case "names":
                        $this->_names = $value;
                    break;
                    case "word":
                        $this->_dyckWord = $value;
                    break;
                    case "sequence":
                        $this->_semanticsList = $value;
                    break;
		}
	    }
	    $this->_semanticsList = fgets($this->_semanticsList);
	    
	    $nomsOuvrantUtilises = []; $nomsFermantUtilises = [];
	    $var = 97;
	    
	    for ($i = 0; $i < strlen($this->_semanticsList); ++$i)
	    {
                if (ord($this->_semanticsList[$i]) > 0)
                    foreach (explode(" ",Word::$_primeSequences[ord($this->_semanticsList[$i])]->to_string()) as $value)
                        $this->push_back($value);
                
                if ($i < strlen($this->_dyckWord))
                {
                    switch ($this->_dyckWord[$i])
                    {
                        case '(':
                            while (array_key_exists($var, $nomsOuvrantUtilises))
                                ++$var;
                            $this->addSite('SF', chr($var));
                            $nomsOuvrantUtilises[$var] = "";
                            break;
                        case ')': 
                            while (array_key_exists($var, $nomsFermantUtilises))
                                --$var;
                            $this->addSite('SR', chr($var));
                            $nomsFermantUtilises[$var] = "";
                            break;
                        case '[': 
                            while (array_key_exists($var, $nomsOuvrantUtilises))
                                ++$var;
                            $this->addSite('SF', chr($var));
                            $nomsOuvrantUtilises[$var] = "";
                            break;
                        case ']': 
                            while (array_key_exists($var, $nomsFermantUtilises))
                                --$var;
                            $this->addSite('SF', chr($var));
                            $nomsFermantUtilises[$var] = "";
                            break;
                    }
                }
	    }
	    
	    $this->setAnotherColours($donnees["names"]);
	}
	
	public function setAnotherColours ($colours)
        {
            if (strlen($colours) != count($this->_inputs))
                throw new \Exception("The number of colours doesn't equal to number of inputs");
            
            $inputs = [];
            
            for ($i = 0; $i < strlen($colours); ++$i)
                $inputs[$colours[$i]] = $this->_inputs[chr(97+$i)];
            
            $this->_inputs = $inputs;
        }
    
	public function push_back ($symbol)
	{
	    $symbol = trim(strtoupper($symbol));
	    switch ($symbol)
	    {
		case "PF" :
		    $this->_symbols[] = Word::PF;
		    $this->_length += $this->_symbolLength[Word::PF];
		    ++$this->_nbP;
		break;
		
		case "PR" :
		    $this->_symbols[] = Word::PR;
		    $this->_length += $this->_symbolLength[Word::PR];
		    ++$this->_nbP;
		break;
		
		case "TF" :
		    $this->_symbols[] = Word::TF;
		    $this->_length += $this->_symbolLength[Word::TF];
		break;
		
		case "TR" :
		    $this->_symbols[] = Word::TR;
		    $this->_length += $this->_symbolLength[Word::TR];
		break;
		
		case "GF" :
		    $this->_symbols[] = Word::GF;
		    $this->_length += $this->_symbolLength[Word::GF];
		    ++$this->_nbG;
		break;
		
		case "GR" :
		    $this->_symbols[] = Word::GR;
		    $this->_length += $this->_symbolLength[Word::GR];
		    ++$this->_nbG;
		break;
		
		default:
		    if (preg_match("/^(SF)(\w)/", $symbol, $matches) === 1)
			$this->addSite($matches[1], strtolower($matches[2]));
		    else if (preg_match("/^(SR)(\w)/", $symbol, $matches) === 1)
			$this->addSite($matches[1], strtolower($matches[2]));
		    else
			throw new \Exception('Symbole invalide : '.$symbol.'.');
	    }
	}
    
	public function addSite ($site, $input)
	{
	    switch ($site)
	    {
		case "SF" :
		    $this->_symbols[] = Word::SF;
		    $this->_length += $this->_symbolLength[Word::SF];
		break;
		
		case "SR" :
		    $this->_symbols[] = Word::SR;
		    $this->_length += $this->_symbolLength[Word::SR];
		break;
		
		default: 
		    throw new \Exception('Ce n\'est pas un site.');
	    }
	    if (key_exists($input, $this->_inputs) && count($this->_inputs[$input]) == 1)
		$this->_inputs[$input][1] = count($this->_symbols)-1;
	    else if (!key_exists($input, $this->_inputs))
		$this->_inputs[$input][0] = count($this->_symbols)-1;
	    else
		throw new \Exception('Les sites doivent être par paire.');
		
	    if (count($this->_inputs) > 6)
		throw new \Exception('Le nombre maximum d\'inputs est limité à 6.');
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
	
	public function toUnicodeSymbol ($symbol)
	{
	    switch ($symbol)
	    {
		case Word::PF : $return = "\u21B1"; break;
		case Word::PR : $return = "\u21B2"; break;
		case Word::TF : $return = "\u22A4"; break;
		case Word::TR : $return = "\u22A5"; break;
		case Word::UF : $return = "\u25B7"; break;
		case Word::UR : $return = "\u25C1"; break;
		case Word::SF : $return = "\u25B6"; break;
		case Word::SR : $return = "\u25C0"; break;
		case Word::GF : $return = "G"; break;
		case Word::GR : $return = "\u2141"; break;
	    }
	    
	    return json_decode('"'.$return.'"');
	}
	
	public function to_string()
	{
	    $word = "";
	    
	    foreach ($this->_symbols as $key => $symbol)
	    {
		/*if ($key > 0 && ($this->_symbols[$key-1] == $this->_symbols[$key]) && ($this->_symbols[$key] == Word::UF || $this->_symbols[$key] == Word::UR))
		    continue;*/
		    
		switch ($symbol)
		{
		    case Word::PF : $word .= "PF "; break;
		    case Word::PR : $word .= "PR "; break;
		    case Word::TF : $word .= "TF "; break;
		    case Word::TR : $word .= "TR "; break;
		    case Word::UF :
		    case Word::UR :
		    case Word::SF :
		    case Word::SR :
			if ($symbol == Word::UF)
			    $word .= "UF";
			
			else if ($symbol == Word::UR)
			    $word .= "UR";
			
			else if ($symbol == Word::SF)
			    $word .= "SF";
			
			else if ($symbol == Word::SR)
			    $word .= "SR";
			
			$word .= $this->findSiteName($key);
			$word .= " ";
			break;
		    case Word::GF : $word .= "GF "; break;
		    case Word::GR : $word .= "GR "; break;
		}
	    }
	    
	    return trim($word);
	}
	
	public function toHTML()
	{
	    $word = "";
	    $siteName;
	    
	    foreach ($this->_symbols as $key => $symbol)
	    {
		if ($key > 0 && 
		    ($this->_symbols[$key-1] == $this->_symbols[$key]) && 
		    ($this->_symbols[$key] == Word::UF || $this->_symbols[$key] == Word::UR) &&
		    ($this->findSiteName($key-1) == $this->findSiteName($key))
		    )
		    continue;
		    
		switch ($symbol)
		{
		    case Word::PF : $word .= '<span class="promoter">' . $this->toUnicodeSymbol(Word::PF) . "</span> "; break;
		    case Word::PR : $word .= '<span class="promoter">' . $this->toUnicodeSymbol(Word::PR) . "</span> "; break;
		    case Word::TF : $word .= '<span class="terminator">' . $this->toUnicodeSymbol(Word::TF) . "</span> "; break;
		    case Word::TR : $word .= '<span class="terminator">' . $this->toUnicodeSymbol(Word::TR) . "</span> "; break;
		    case Word::UF :
			$siteName = $this->findSiteName($key);
			$word .= '<span class="site_' . $siteName . '">' . $this->toUnicodeSymbol(Word::UF) . "</span> ";
		    break;
		    case Word::UR :
			$siteName = $this->findSiteName($key);
			$word .= '<span class="site_' . $siteName . '">' . $this->toUnicodeSymbol(Word::UR) . "</span> ";
		    break;
		    case Word::SF :
			$siteName = $this->findSiteName($key);
			$word .= '<span class="site_' . $siteName . '">' . $this->toUnicodeSymbol(Word::SF) . "</span> ";
		    break;
		    case Word::SR :
			$siteName = $this->findSiteName($key);
			$word .= '<span class="site_' . $siteName . '">' . $this->toUnicodeSymbol(Word::SR) . "</span> ";
		    break;
		    case Word::GF : $word .= '<span class="gene">' . $this->toUnicodeSymbol(Word::GF) . "</span> "; break;
		    case Word::GR : $word .= '<span class="gene">' . $this->toUnicodeSymbol(Word::GR) . "</span> "; break;
		}
	    }
	    
	    return trim($word);
	}
	
	private function reverseSymbol(&$symbols, $i)
	{
	    switch ($symbols[$i])
	    {
		case Word::PF : $symbols[$i] = Word::PR; break;
		case Word::PR : $symbols[$i] = Word::PF; break;
		case Word::TF : $symbols[$i] = Word::TR; break;
		case Word::TR : $symbols[$i] = Word::TF; break;
		case Word::UF : $symbols[$i] = Word::UR; break;
		case Word::UR : $symbols[$i] = Word::UF; break;
		case Word::SF : $symbols[$i] = Word::SR; break;
		case Word::SR : $symbols[$i] = Word::SF; break;
		case Word::GF : $symbols[$i] = Word::GR; break;
		case Word::GR : $symbols[$i] = Word::GF; break;
	    }
	}
	
	private function setSiteUsed ($inputName)
	{
	    if (key_exists($inputName, $this->_inputs))
	    {
		if ($this->_symbols[$this->_inputs[$inputName][0]] == Word::SF)
		    $this->_symbols[$this->_inputs[$inputName][0]] = Word::UF;
		
		else if ($this->_symbols[$this->_inputs[$inputName][0]] == Word::SR)
		    $this->_symbols[$this->_inputs[$inputName][0]] = Word::UR;
		
		else
		    throw new \exception($inputName . " is already an used site and cannot be reset to used.");
		
		if ($this->_symbols[$this->_inputs[$inputName][1]] == Word::SF)
		    $this->_symbols[$this->_inputs[$inputName][1]] = Word::UF;
		
		else if ($this->_symbols[$this->_inputs[$inputName][1]] == Word::SR)
		    $this->_symbols[$this->_inputs[$inputName][1]] = Word::UR;
		
		else
		    throw new \exception($inputName . " is already an used site and cannot be reset to used.");
	    }
	    else
		throw new \exception($inputName .  " does not exist and cannot be set to used.");
	}

	private function integraseReverse ($inputName)
	{
	    if (key_exists($inputName, $this->_inputs))
	    {
		array_splice($this->_symbols, 
		    $this->_inputs[$inputName][0]+1, 
		    $this->_inputs[$inputName][1]-$this->_inputs[$inputName][0]-1, 
		    array_reverse(array_slice(
			$this->_symbols, 
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
		    $this->reverseSymbol($this->_symbols, $i);
		    
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
		    if ($this->_symbols[$i] == Word::GR || $this->_symbols[$i] == Word::GF)
			--$this->_nbG;
		    
		    if ($this->_symbols[$i] == Word::PR || $this->_symbols[$i] == Word::PF)
			--$this->_nbP;
		}
		
		array_splice($this->_symbols, $begin, $length);
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
		    if (($this->_symbols[$this->_inputs[$inputName][0]] == Word::SF && $this->_symbols[$this->_inputs[$inputName][1]] == Word::SR)
			|| ($this->_symbols[$this->_inputs[$inputName][0]] == Word::SR && $this->_symbols[$this->_inputs[$inputName][1]] == Word::SF))
			$this->integraseReverse($inputName);
		    else if (($this->_symbols[$this->_inputs[$inputName][0]] == Word::SF && $this->_symbols[$this->_inputs[$inputName][1]] == Word::SF)
			|| ($this->_symbols[$this->_inputs[$inputName][0]] == Word::SR && $this->_symbols[$this->_inputs[$inputName][1]] == Word::SR))
			$this->integraseExcise($inputName);
		    else
			throw new \exception( "Impossible to activate site \"" . $inputName . "\", it is used or does not exit.");
		}
	    }
	    else
		throw new \exception( "Impossible to activate site \"" . $inputName . "\", it is used or does not exit.");
	}
	
	public function isGeneActivated()
	{
	    $activated = false; $notValidActivation = false;
	    $powerPR = 0; $powerPF = 0;
	    $hasPR = false; $hasPF = false;
	    
	    if ($this->_nbG && $this->_nbP)
	    {
		foreach ($this->_symbols as $forward)
		{
		    if ($hasPF && $forward != Word::GF)
			$powerPF -= $this->_symbolLength[$forward];
		    
		    if($forward == Word::TF)
		    {
			$powerPF = 0;
			$hasPF = false;
		    }
		    else if($forward == Word::GF)
		    {
                        if ($hasPF)
			{
			    $activated = true;
			    break;
			}
		    }
		    else if($forward == Word::PF)
		    {
			$powerPF = $this->_promotersPower[Word::PF];
			$hasPF = true;
		    }
		}
		
		if (!$activated)
		{
		    foreach (array_reverse($this->_symbols, true) as $reverse)
		    {
			if ($hasPR && $reverse != Word::GR)
			    $powerPR -= $this->_symbolLength[$reverse];
			
			if($reverse == Word::TR)
			{
			    $powerPR = 0;
			    $hasPR = false;
			}
			else if($reverse == Word::GR)
			{
			    if ($hasPR)
			    {
                                $activated = true;
                                break;
			    }
			}
			else if($reverse == Word::PR)
			{
			    $powerPR = $this->_promotersPower[Word::PR];
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
		$this->_symetric = new Word();
		$this->_symetric->_symbols = array_reverse($this->_symbols);
		$this->_symetric->_inputs = $this->_inputs;
		$this->_symetric->_nbP = $this->_nbP;
		$this->_symetric->_nbG = $this->_nbG;
		
		for ($i = 0; $i < count($this->_symetric->_symbols); ++$i)
		    $this->reverseSymbol($this->_symetric->_symbols, $i);
		
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
	    
	    foreach ($this->_symbols as $forward)
	    {
		if ($hasPF && $forward != Word::GF)
		    $powerPF -= $this->_symbolLength[$forward];
		
		if($forward == Word::PF)
		{
		    $powerPF = $this->_promotersPower[Word::PF];
		    $hasPF = true;
		}
		else if($forward == Word::TF)
		{
		    $hasPF = false;
		    $powerPF = 0;
		}
		else if($forward == Word::GF && $hasPF)
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
		else if($forward == Word::PR && $hasPF)
		{
		    $hasPF = false;
		    $powerPF = 0;
		    $smashed = true;
		}
	    }
		
	    if ($smashed)
	    {
		foreach (array_reverse($this->_symbols, true) as $reverse)
		{
		    if ($hasPR && $reverse != Word::GR)
			$powerPR -= $this->_symbolLength[$reverse];
		    
		    if($reverse == Word::PR)
		    {
			$powerPR = $this->_promotersPower[Word::PR];
			$hasPR = true;
		    }
		    
		    else if($reverse == Word::TR)
		    {
			$hasPR = false;
			$powerPR = 0;
		    }
		    
		    else if($reverse == Word::GR && $hasPR)
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
		    
		    else if($reverse == Word::PF && $hasPR)
		    {
			$hasPR = false;
			$powerPR = 0;
			$smashed = true;
		    }
		}
	    }
	    
	    return !$smashed;
	}

	private function makeSubWord ($i)
	{
	    if ($i > 0)
	    {
		$this->_subWords[$i-1] = clone $this;
		$inputs = $this->_subWords[$i-1]->inputNamesList();
		$activation = $i;
		
		for ($j = 0; $j < count($this->_inputs); ++$j)
		{
		    if ($activation % 2 == 1)
			$this->_subWords[$i-1]->integrase($inputs[count($this->_inputs)-$j-1]);
		    $activation = $activation >> 1;
		}
	    }
	}

	public function getSubWord($i)
	{
	    if (!$i)
		return $this;
	    
	    else if (key_exists($i-1, $this->_subWords))
		return $this->_subWords[$i-1];
	    
	    else
	    {
		$this->makeSubWord($i);
		return $this->_subWords[$i-1];
	    }
	}

	public function hasNoSubwordsSmashingPromoters()
	{
	    if ($this->_nbP <= 1)
		return true;
	    
	    $smashed = false;
	    $numberSub = pow(2, count($this->_inputs));
	    
	    for ($i = 1; $i < $numberSub; ++$i)
	    {
		$subWord = $this->getSubWord($i);
		if (!$subWord->hasNoSmashingPromoters())
		{
		    $smashed = true;
		    break;
		}
	    }
	    
	    return !$smashed;
	}

	public function hasNoSubwordsFadingPromoters ()
	{
	    if ($this->_nbP == 0 || $this->_nbG == 0)
		return true;
	    
	    $faded = false;
	    $numberSub = pow(2, count($this->_inputs));
	    
	    for ($i = 1; $i < $numberSub; ++$i)
	    {
		$subWord = $this->getSubWord($i);
		if ($subWord->isGeneActivated() == -1)
		{
		    $faded = true;
		    break;
		}
	    }
	    
	    return !$faded;
	}

	public function hasValidPromoters ()
	{
	    return $this->hasNoSmashingPromoters() && $this->isGeneActivated() != -1 && $this->hasNoSubwordsSmashingPromoters() && $this->hasNoSubwordsFadingPromoters();
	}
	
	public function isValid ()
	{
	    return $this->hasEnoughSites();
	}
	
	public function setMinimalLogic ($minimalLogic)
	{
	    $this->_minimalLogic = $minimalLogic;
	}
	
	public function setId_w ($id_w)
	{
	    $this->_id_w = $id_w;
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
            foreach ($this->_symbols as $it)
            {
                $sem = $this->getSemanticSymbol($it);
                
                if (!$sem->isEpsilon())
                    $this->_semantic->combine($sem);
            }
        }

        public function getSemantic()
        {
            if ($this->_semantic->isEpsilon())
                $this->buildSemantic();
            
            return $this->_semantic;
        }

        public function getSemanticSymbol($s)
        {
            $sem = new Semantic();
            
            switch ($s)
            {
                case Word::PF:
                    $sem->setPF(1);
                break;
                case Word::PR:
                    $sem->setPR(1);
                break;
                case Word::TF:
                    $sem->setTF(1);
                break;
                case Word::TR:
                    $sem->setTR(1);
                break;
                case Word::GF:
                    $sem->setGF(1);
                break;
                case Word::GR:
                    $sem->setGR(1);
                break;
                case Word::UR:
                case Word::UF:
                case Word::SR:
                case Word::SF:
                    break;
            }
                
            return $sem;
        }

	public function getNbP () { return $this->_nbP; }
	public function getNbG () { return $this->_nbG; }
	public function getSymetric () { $this->buildSymetric(); return $this->_symetric; }
	public function howManyInputs () { return count($this->_inputs); }
	public function size () { return count($this->_symbols); }
	public function getMinimalLogic () { return $this->_minimalLogic; }
	public function getLength () { return $this->_length; }
	public function getId_w () { return $this->_id_w; }
	public function getWeakConstraint () { return $this->_weakConstraint; }
	public function getNames () { return $this->_names; }
	
	static function __set_state(array $array) 
	{
	    $tmp = new Word();
	    $tmp->_id_w 		= $array['_id_w'];
	    $tmp->_symbols 		= $array['_symbols'];
	    $tmp->_inputs 		= $array['_inputs'];
	    $tmp->_nbG 			= $array['_nbG'];
	    $tmp->_nbP			= $array['_nbP'];
	    $tmp->_subWords 		= $array['_subWords'];
	    $tmp->_symetric 		= $array['_symetric'];
	    $tmp->_minimalLogic 	= $array['_minimalLogic'];
	    $tmp->_length 		= $array['_length'];
	    $tmp->_names 		= $array['_names'];
	    $tmp->_weakConstraint       = (bool) intval($array['_weakConstraint']);
	    return $tmp;
	}
    }
