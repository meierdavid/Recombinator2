<?php
#################################################
#						#
#	Veritas.php				#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 
    namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;

    
    abstract class Veritas 
    {
	protected $_outputs		= 0;
	protected $_howManyInputs	= 0;
	
	abstract public function to_string ();
	abstract public function toHTML ();
	abstract public function inputNamesList ();
	
	public function getMinimalDisjunctiveForm ()
	{
	    return Veritas::mcCluskey($this->_outputs, $this->_howManyInputs, $this->inputNamesList());
	}
	
	public static function mcCluskey ($outputs, $howManyInputs, $inputNames)
	{
	    return new MinimalDisjunctiveForm($outputs, $howManyInputs, $inputNames);
	}
	
	public function outputToString()
	{
	    $size = pow(2, $this->_howManyInputs);
	    $output = $this->_outputs;
	    $string = "";
	    
	    for ($i = 0; $i < $size; ++$i)
	    {
		$string .= $output % 2;
		$output = $output >> 1;
	    }
	    
	    return strrev($string);
	}
	
	public function getMinimalOutput ()
	{
	    $minimal = $this->getMinimalDisjunctiveForm();
	    if ($minimal == "1" || $minimal == "0")
		return $minimal;
		
	    $logic = new BooleanFunction($minimal);
	    $veritas = new VeritasBooleanFunction($logic);
	    
	    return $veritas->outputToString();
	}
	
	public function getMinimalNbInputs ()
	{
	    $minimal = $this->getMinimalDisjunctiveForm();
	    if ($minimal == "1" || $minimal == "0")
		return $minimal;
		
	    $logic = new BooleanFunction($minimal);
	    $veritas = new VeritasBooleanFunction($logic);
	    
	    return $veritas->_howManyInputs;
	}
	
	public static function nbBitsOne ($int)
	{
            $cpt = 0;
            
            for ($i = 0; $i < PHP_INT_SIZE * 8; $i++)
            {
                if ($int % 2)
                    $cpt++;
                    
                $int = $int >> 1;
            }
            
            return $cpt;
	}
	
	public function getClass ()
	{
            $class = "";
            $size = pow(2, $this->_howManyInputs);
            $output = $this->_outputs;
            
            if ($this->_howManyInputs > 1)
            {
                $elements = array();
                
                for ($i = 0; $i < $size; ++$i)
                {
                    if (!$i)
                        $end = $output % 2;
                        
                    else if ($i < $size-1)
                    {
                        $n = $size - $i - 1;
                        if (key_exists($this->nbBitsOne($n), $elements))
                            $elements[$this->nbBitsOne($n)] += $output % 2;
                        else
                            $elements[$this->nbBitsOne($n)] = $output % 2;
                        
                    }
                    
                    else 
                    {
                        $ends = $output % 2;
                        $ends = $ends << 1;
                        $ends += $end;
                    }
                        
                    $output = $output >> 1;
                }
	    
                ksort($elements);
                foreach ($elements as $value)
                {
                    $class .= $value . "/";
                }
                
                $class = substr($class, 0,-1);
                $class .= " - " .$ends;
                
                if ($this->_howManyInputs == 3)
                {
                    if (abs($elements[1]-$elements[2]) == 3 || (($elements[1] == 0 || $elements[1] == 3) && $elements[1] == $elements[2]))
                        $class .= " (1 fonction) ";
                        
                    else if ($elements[1] == 0 || $elements[2] == 0 || $elements[1] == 3 || $elements[2] == 3)
                        $class .= " (3 fonctions) ";
                        
                    else 
                        $class .= " (3 ou 6 fonctions) ";
                }
	    
	    }
	    else
	    {
                
                for ($i = 0; $i < $size; ++$i)
                {
                    if (!$i)
                        $end = $output % 2;
                        
                    else if ($i < $size-1)
                    {
                    }
                    
                    else 
                    {
                        $ends = $output % 2;
                        $ends = $ends << 1;
                        $ends += $end;
                    }
                        
                    $output = $output >> 1;
                }
                $class .= $ends;
	    }
	    
	    return $class;
            
	}
	
	public function distanceEdition()
	{
	    $size = pow(2, $this->_howManyInputs);
	    $string = $this->outputToString();
	    
	    return levenshtein( 
                substr($string, 0, $size/2),
                substr($string, $size/2, $size/2)
	    );
            
	}
	
	public function getOutput () { return $this->_output; }
    }
