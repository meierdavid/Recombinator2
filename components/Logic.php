<?php
#################################################
#						#
#	Logic.php				#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 
               
    namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
    function insert($tab,$indice,$valeurInsert)
		    {
			$nouveauTab = array();
			foreach($tab as $cle=>$valeur)
			{
			    if ($cle == $indice)
				array_push($nouveauTab,$valeurInsert);
			
			    array_push($nouveauTab,$valeur);
			}
			
			return $nouveauTab;
		    }
    class Logic 
    {
	private $_id_fn;
	private $_logic 	= array();
	private $_inputs 	= array();
	private $_minimalLogic	= "";
	private $_output 	= "";
	private $_nbWords 	= 0;
   
	const OP_AND = '.'; 
	const OP_OR = '+'; 
	const OP_NOT = '!'; 
	
	
	public function __construct ($logic = null) 
	{
	    if ($logic != null)
		$this->loadInfix($logic);
	}
		
	public function hydrate(array $donnees)
	{
            $dnf; $nbInputs;
	    foreach ($donnees as $key => $value)
	    {
		switch ($key)
		{
		    case "id_lf":
		    case "id":
			$this->setId_fn($value);
		    break;
		    case "nb_inputs":
                        $nbInputs = $value;
		    break;
		    case "dnf":
                        $dnf = $value;
		    break;
		    
		    case "nbWords":
			$this->setNbWords($value);
		    break;
		}
	    }
	    $inputNames = [];
            for ($character = 97; $character < 97 + $nbInputs; $character++)
                $inputNames[] = chr($character);
	    $mdf = Veritas::mcCluskey($dnf, $nbInputs, $inputNames);
	    $this->loadInfix($mdf);
	    $this->setMinimalLogic($mdf);
	    $veritas = new VeritasLogic($this);
	    $this->setOutput(substr(str_pad(decbin($dnf), pow(2,$nbInputs), "0", STR_PAD_LEFT), -1 * pow(2,$nbInputs)));
	}
	
	public function isWellBracketted ($infix)
	{
	    $openedBrackets = 0;

	    for ($i = 0; $i < strlen($infix); ++$i)
	    {
		if ($openedBrackets < 0)
		    break;

		if ($infix[$i] == '(')
		    ++$openedBrackets;

		else if ($infix[$i] == ')')
		    --$openedBrackets;
	    }

	    return $openedBrackets == 0;
	}
	
	public function removePeripheralBrackets ($infix)
	{
	    $removed = false;

	    do
	    {
		if ($infix[0] == '(' && $infix[count($infix)-1] == ')')
		{
		    $tmp = substr($infix,1,-1);
		    
		    if ($this->isWellBracketted($tmp))
		    {
			$infix = $tmp;
			$removed = true;
		    }
		    else
			$removed = false;
		}
		else
		    $removed = false;
	    } while ($removed);

	    return $infix;
	}

	public function isAValidSymbol ($symbol)
	{
	    return ((ord($symbol) >= 65 && ord($symbol) <= 90) || (ord($symbol) >= 97 && ord($symbol) <= 122));
	}
	
	public function loadInfix ($infix)
	{
	    $infix = trim($infix);
	    $infix = str_replace(' ','',$infix);
	    $infix = $this->removePeripheralBrackets($infix);

	    // If the proposition is empty
	    if (strlen($infix) == 0)
		throw new \Exception("The null function is not allowed.");

	    // if it is reduced to one character
	    else if (strlen($infix) == 1)
	    {
		// if the character is a letter
		if ($this->isAValidSymbol($infix[0]))
		{
		    $this->_logic[] = strtolower($infix);
		    $this->_inputs[strtolower($infix)] = false;
		}

		else
		    throw new \Exception("There is an unauthorized symbol in the function: " . $infix);
	    }

	    else
	    {
		if (!function_exists('insert')) 
		{
		    
		}
		
		if (!$this->isWellBracketted($infix))
		    throw new \Exception("The function is not well bracketed: " . $infix);
		
		$incompletes = $bracketsToClose = $brackets = array();
		$i = $j = $incomplete = 0;
		
		for ($c = 0; $c < strlen($infix); ++$c)
		{
		    if ($infix[$c] == '(')
		    {
			$brackets[] = array($i,-1);
			$bracketsToClose[] = $i;
			
			if (count($incompletes) && $incompletes[count($incompletes)-1] > 0)
			    --$incompletes[count($incompletes)-1];
			else if (!count($incompletes) && $incomplete > 0)
			    --$incomplete;
			
			$incompletes[] = 0;
		    }
		    else if ($infix[$c] == ')')
		    {
			if (end($incompletes))
			    throw new \Exception("The function is ill-formed: " . $infix);
			    
			$iCB = count($brackets)-1;
			while ($brackets[$iCB][1] != -1)
			{
			    --$iCB;
			    if ($iCB < 0)
				throw new \Exception("The function is ill-formed: " . $infix);
			}
			$brackets[$iCB][1] = $i-1;
			
			array_pop($bracketsToClose);
			array_pop($incompletes);
		    }
		    else if ($infix[$c] == Logic::OP_OR)
		    {
			if (count($bracketsToClose))
			{
			    if (!$incompletes[count($incompletes)-1])
			    {
				$this->_logic = insert($this->_logic, end($bracketsToClose), $infix[$c]);
				++$incompletes[count($incompletes)-1];
			    }
			    else 
				throw new \Exception("The function is ill-formed: " . $infix);
			}
			else
			{
			    if (!$incomplete)
			    {
				array_unshift($this->_logic, $infix[$c]);
				++$incomplete;
			    }
			    else
				throw new \Exception("The function is ill-formed: " . $infix);
			}
			++$i;
		    }
		    else if ($infix[$c] == Logic::OP_AND)
		    {
			if (count($bracketsToClose))
			{
			    if (!end($incompletes))
			    {
				$j = $i-1;
				
				if ($j < 0 || !$this->isAValidSymbol($this->_logic[$j]))
				    throw new \Exception("The function is ill-formed: " . $infix);
				    
				$bracketBefore = false;
				
				foreach ($brackets as $b)
				{
				    if ($b[1] == $j)
				    {
					$bracketBefore = true;
					$j = $b[0];
					break;
				    }
				}
				
				if (!$bracketBefore)
				{
				    while ($j > 0 && $this->_logic[$j-1] == Logic::OP_NOT)
					--$j;
				}
				
				$this->_logic = insert($this->_logic, $j, $infix[$c]);
				++$incompletes[count($incompletes)-1];
			    }
			    else 
				throw new \Exception("The function is ill-formed: " . $infix);
			}
			else
			{
			    if (!$incomplete)
			    {
				$j = $i-1;
				
				if ($j < 0 || !$this->isAValidSymbol($this->_logic[$j]))
				    throw new \Exception("The function is ill-formed: " . $infix);
				    
				$bracketBefore = false;
				
				foreach ($brackets as $b)
				{
				    if ($b[1] == $j)
				    {
					$bracketBefore = true;
					$j = $b[0];
					break;
				    }
				}
				
				if (!$bracketBefore)
				{
				    while ($j > 0 && $this->_logic[$j-1] == Logic::OP_NOT)
					--$j;
				}
				
				$this->_logic = insert($this->_logic, $j, $infix[$c]);
				++$incomplete;
			    }
			    else
				throw new \Exception("The function is ill-formed: " . $infix);
			}
			++$i;
		    }
		    else if ($infix[$c] == Logic::OP_NOT)
		    {
			$this->_logic[] = $infix[$c];
			if (count($incompletes) && !end($incompletes))
			    ++$incompletes[count($incompletes)-1];
			else if (!count($incompletes) && !$incomplete)
			    ++$incomplete;
			++$i;
		    }
		    else if ($this->isAValidSymbol($infix[$c]))
		    {
			$this->_inputs[strtolower($infix[$c])] = false;
			$this->_logic[] = strtolower($infix[$c]);
			++$i;
			if (count($incompletes) && end($incompletes) > 0)
			    --$incompletes[count($incompletes)-1];
			else if (!count($incompletes) && $incomplete > 0)
			    --$incomplete;
		    }
		    else 
		    {
			throw new \Exception("The function is ill-formed (unauthorized character): " . $infix);
		    }
		}
		
		if ($incomplete || count($incompletes))
		    throw new \Exception("The function is ill-formed: " . $infix);
		    
                ksort($this->_inputs);
	    }
	    
	    ksort($this->_inputs);
	}

	public function to_string ()
	{
	    $i = 0;
	    return $this->removePeripheralBrackets($this->to_string_rec($i));
	}

	private function to_string_rec (&$i)
	{
	    if ($this->isAValidSymbol($this->_logic[$i]))
		return $this->_logic[$i];

	    else
	    {
		$left = "";
		$right = "";

		if ($this->_logic[$i] == Logic::OP_AND)
		{
		    ++$i;
		    $left = $this->to_string_rec($i);
		    ++$i;
		    $right = $this->to_string_rec($i);
		    return "(" . $left . Logic::OP_AND . $right . ")";
		}

		else if ($this->_logic[$i] == Logic::OP_OR)
		{
		    ++$i;
		    $left = $this->to_string_rec($i);
		    ++$i;
		    $right = $this->to_string_rec($i);
		    return "(" . $left . Logic::OP_OR .  $right . ")";
		}

		else if ($this->_logic[$i] == Logic::OP_NOT)
		{
		    ++$i;
		    return Logic::OP_NOT . $this->to_string_rec($i);
		}
	    }

	    return "";
	}

	private function isOutputTrueRec(&$i)
	{
	    if ($this->isAValidSymbol($this->_logic[$i]))
		return $this->_inputs[$this->_logic[$i]];

	    else
	    {
		$left;
		$right;

		if ($this->_logic[$i] == Logic::OP_AND)
		{
		    ++$i;
		    $left = $this->isOutputTrueRec($i);
		    ++$i;
		    $right = $this->isOutputTrueRec($i);
		    return $left && $right;
		}

		else if ($this->_logic[$i] == Logic::OP_OR)
		{
		    ++$i;
		    $left = $this->isOutputTrueRec($i);
		    ++$i;
		    $right = $this->isOutputTrueRec($i);
		    return $left || $right;
		}

		else if ($this->_logic[$i] == Logic::OP_NOT)
		{
		    ++$i;
		    return !$this->isOutputTrueRec($i);
		}
	    }

	    return false;
	}

	public function isOutputTrue($activatingList = null)
	{
	    if ($activatingList != null)
	    {
		$i = 0;
		foreach ($this->_inputs as $key => $value)
		{
		    $this->_inputs[$key] = $activatingList[$i];
		    ++$i;
		}
	    }
	    
	    $i = 0;
	    return $this->isOutputTrueRec($i);
	}
	
	public function setOutput ($output)
	{
	    $this->_output = $output;
	}
	
	public function setId_fn ($id_fn)
	{
	    $this->_id_fn = $id_fn;
	}
	
	public function setMinimalLogic ($minimalLogic)
	{
	    $this->_minimalLogic = $minimalLogic;
	}
	
	public function setNbWords ($nbWords)
	{
	    $this->_nbWords = $nbWords;
	}
	
	public function inputNamesList() 
	{ 
	    $list = array_keys($this->_inputs); 
	    sort($list); 
	    return $list; 
	}
	
	public function getTable ()
	{
            $table = new VeritasLogic($this);
            return $table;
	}
	
	public function howManyInputs () { return count($this->_inputs); }
	public function getOutput () { return $this->_output; }
	public function getId_fn () { return $this->_id_fn; }
	public function getMinimalLogic () { return $this->_minimalLogic; }
	public function getNbWords () { return $this->_nbWords; }
	
	static function __set_state(array $array) 
	{
	    $tmp = new Logic();
	    $tmp->_id_fn 	= $array['_id_fn'];
	    $tmp->_logic 	= $array['_logic'];
	    $tmp->_inputs 	= $array['_inputs'];
	    $tmp->_minimalLogic	= $array['_minimalLogic'];
	    $tmp->_output 	= $array['_output'];
	    $tmp->_nbWords 	= $array['_nbWords'];
	    return $tmp;
	}

    }
