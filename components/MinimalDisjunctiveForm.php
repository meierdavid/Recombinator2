<?php
 namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
/**
 * @author Guillaume Kihli
 * @abstract Building of the minimal disjunctive form of a boolean function with the Quine-McCluskey method
 */
class MinimalDisjunctiveForm
{    
    /**
     * @var int
     */
   
    
    private $_nbVariables;
    /**
     * @var array of bits
     */
    private $_output;
    /**
     * @var array of array of integers
     * Array of terms grouped by number of ones in their binary form
     */
    private $_terms             = array();
    /**
     * @var array of char
     */
    private $_varNames          = array('a', 'b', 'c', 'd', 'e', 'f', 
                                    'g', 'h', 'i', 'j', 'k', 'l', 'n', 
                                    'm', 'o', 'p', 'q', 'r', 's', 't',
                                    'u', 'v', 'x', 'y', 'z');
    /**
     * @var array
     */
    private $_primeImplicants   = array();   
    /**
     * @var string
     */
    private $_minimizedFunction;
    
    const LOGICAL_OR = '+';
    const LOGICAL_AND = '.';
    const LOGICAL_NOT = '!';
    const USE_PARENTHESIS = false;
    
    public function __construct($output, $nbVariables, $varNames = null)
    {
        $outputLength = pow(2, $nbVariables);
        if (is_string($output))
        {
            if (strlen($output) != $outputLength){
                throw new \Exception("The length of output is invalid");
            }
            $output=str_split($output);
            for(  $i=0; $i<count($output);$i++){
                if($output[$i]==='0'){
                    $output[$i]=0;
                }
                elseif($output[$i]==='1'){
                    $output[$i]=1;
                }
                else{
                    throw new \Exception("The contents of output is invalid");
                }
            }
            
            
            
            $this->_output = $output;
        }
        else if (is_array($output))
        {
            if (count($output) != $outputLength)
                throw new \Exception("The length of output is invalid");
            
            if (count($output) != self::numberOfOnes($output)+self::numberOfZeros($output))
                throw new \Exception("The contents of output is invalid");
            
            $this->_output = $output;
        }
        else if (is_numeric($output)) 
        {
            $this->_output = self::intToBits(intval($output), $outputLength);
        }
        
        $this->_nbVariables = $nbVariables;
        if ($varNames != null)
            $this->_varNames = $varNames;
    }

    /**
     * @param int $integer
     * @return array
     */
    public static function intToBits ($integer, $size = 64)
    {
        return str_split(
            substr(
                str_pad(
                    decbin($integer), 
                    $size, 
                    "0", 
                    STR_PAD_LEFT), 
                -1 * $size
            ));
    }
    
    /**
     * @return boolean
     */
    public function isAlwaysTrue ()
    {
        foreach($this->_output as $bit)
        {
            if ($bit == 0)
                return false;
        }    
        return true;
    }
    
    /**
     * @return boolean
     */
    public function isAlwaysFalse ()
    {
        foreach($this->_output as $bit)
        {
            if ($bit == 1)
                return false;
        }    
        return true;
    }
    
    /**
     * @param array $array
     * @param $element
     * @return int
     */
    public static function numberOfElement (array $array, $element)
    {
        $count = 0;
        foreach ($array as $e)
        {
            if ($e == $element)
                $count++;
        }
        return $count;
    }
    
    /**
     * @param array $bitArray
     * @return int
     */
    public static function numberOfOnes (array $bitArray)
    {
        return self::numberOfElement($bitArray, 1);
    }
    
    /**
     * @param array $bitArray
     * @return int
     */
    public static function numberOfZeros (array $bitArray)
    {
        return self::numberOfElement($bitArray, 0);
    }
    
    private function makeTerms ()
    {
        $numberOfTerms = pow(2, $this->_nbVariables);
        
        for ($i = 0; $i < $numberOfTerms; $i++)
        {
            if ($this->_output[$i] == 1)
            {
                $numberOfOnes = self::numberOfOnes(self::intToBits($i, $this->_nbVariables));
                
                if (array_key_exists($numberOfOnes, $this->_terms))
                    $this->_terms[$numberOfOnes][] = $i;
                else
                    $this->_terms[$numberOfOnes] = [$i];
            }
        }
    }
    
    /**
     * @return array[]|array[][]|int[][]|boolean[][]
     */
    private function buildArrayOfCombinations ()
    {
        $combinations = array();
        
        foreach ($this->_terms as $nbOnes => $terms)
        {
            $combinations[$nbOnes] = [];
            
            foreach ($terms as $term)
            {
                $combinations[$nbOnes][] =
                    array(
                        [$term], // Array of terms used in the combinations
                        0, // Raw Mask used to know if we combine two terms
                        false // Is this term was used in a combination ?
                    );
            }
        }
        
        return $combinations;
    }
    
    /**
     * @param array $firstTerm
     * @param array $secondTerm
     * @return boolean[]|number[]|array[]|boolean
     */
    public static function combineTwoTerms ($firstTerm, $secondTerm)
    {
        /*
         * To check if there is only one bit of difference between two integer
         * values, we use the xor operator (^).
         * If the result is a power of two, then, there is only one bit of difference.
         */
        $xorResult = 
            // We select the first value of the first term
            intval($firstTerm[0][0]) 
            ^
            // We select the first value of the second term
            intval($secondTerm[0][0]); 
        
        /*
         * To check if the result is a power of two, we use the logical-and operator (&).
         * If (xorResult) and (xorResult-1) = 0, it is a power of two (so we can combine the terms).
         */
        if (($xorResult & ($xorResult-1)) == 0)
        {
            // We merge the terms
            $newTermValues = array_merge($firstTerm[0], $secondTerm[0]);
            // We want values sorted by increasing order
            sort($newTermValues);
            
            // Raw Mask = greater term - lower term
            $newRawMask = end($newTermValues)-reset($newTermValues);
            
            // Finally, we return the new term
            return [$newTermValues, $newRawMask, false];
        }
        
        // We return false if the terms can't be combined
        return false;
    }
    
    /**
     * @param array $combinationsArray
     */
    private function savePrimeImplicants (array &$combinationsArray)
    {
        foreach ($combinationsArray as $numOnes => $terms)
        {
            foreach ($terms as $term)
            {
                // If the term was not used in a combination, it's a prime implicants
                if (!$term[2])
                    $this->_primeImplicants[] = $term;
            }
        }
    }
    
    /**
     * @param array $combinationsArray
     * @param int $numOnes
     * @param int $keyOfTerm
     */
    private static function setUsed (array &$combinationsArray, $numOnes, $keyOfTerm)
    {
        $combinationsArray[$numOnes][$keyOfTerm][2] = true;
    }
    
    /**
     * @param array $term1
     * @param array $term2
     * @return boolean
     */
    public static function hasTheSameRawMask (array $term1, array $term2)
    {
        return $term1[1] == $term2[1];
    }
    
    /**
     * @param array $combinationsArray
     * @param int $numOnes
     * @param array $term
     */
    public static function insertATermInCombinationsArray (array &$combinationsArray, $numOnes, array $term)
    {
        if (array_key_exists($numOnes, $combinationsArray))
        {
            if (!in_array($term, $combinationsArray[$numOnes]))
                $combinationsArray[$numOnes][] = $term;
        }
        else
            $combinationsArray[$numOnes] = [$term];
    }
    
    private function determinePrimeImplicants ()
    {
        $combinationsArray = $this->buildArrayOfCombinations();
        
        $canCombine = true;
        
        while ($canCombine)
        {
            $canCombine = false;
            $newCombinationsArray = array();
            
            // We browse the $combinationsArray with the number of ones of terms values
            for ($numOnes = 0; $numOnes < count($this->_output); $numOnes++)
            {
                if (array_key_exists($numOnes, $combinationsArray) 
                    && array_key_exists($numOnes+1, $combinationsArray))
                {
                    // We select a term of $numOnes number of ones
                    foreach ($combinationsArray[$numOnes] as $keyTerm1 => $term1)
                    {
                        /* We select a term of $numOnes+1 number of ones and we are going
                         * to try to combine it with the first one
                         */
                        foreach ($combinationsArray[$numOnes+1] as $keyTerm2 => $term2)
                        {
                            // To combine them, the raw mask of the two terms must be the same
                            if (self::hasTheSameRawMask($term1, $term2))
                            {
                                $combination = self::combineTwoTerms($term1, $term2);
                                if ($combination !== false)
                                {
                                    $canCombine = true;
                                    
                                    self::insertATermInCombinationsArray($newCombinationsArray, $numOnes, $combination);
                                    
                                    self::setUsed($combinationsArray, $numOnes, $keyTerm1);
                                    self::setUsed($combinationsArray, $numOnes+1, $keyTerm2);
                                }
                            }
                        }
                    }
                }
            }
            
            // The not used terms are the prime implicants : we save them
            $this->savePrimeImplicants($combinationsArray);
            // We replace the $combinationsArray with the new terms got by combination
            $combinationsArray = $newCombinationsArray;
        }
    }
    
    /**
     * @return array[]
     */
    private function madePrimeImplicantsChart ()
    {
        $chart = [];
        foreach ($this->_primeImplicants as $key => $primeImplicant)
        {
            foreach ($primeImplicant[0] as $term)
            {
                if (!array_key_exists($term, $chart))
                    $chart[$term] = [];
                
                $chart[$term][] = $key;
            }
        }
        
        return $chart;
    }
    
    /**
     * @param array $primeImplicantsChart
     * @return array[][][]
     */
    private function madePetricksProduct (array $primeImplicantsChart)
    {
        $product = [];
        
        foreach ($primeImplicantsChart as $term => $implicants)
        {
            $array = [];
            foreach ($implicants as $key)
                $array[] = [$key];
            $product[] = $array;
        }
        
        return $product;
    }
    
    /**
     * @param array $product
     * @return mixed
     */
    private function madePetricksDistribution (array $product)
    {
        $i = 0;
        while (count($product) > 1)
        {
            $distribution = [];
            foreach ($product[$i] as $v1)
            {
                foreach ($product[$i+1] as $v2)
                {
                    $newProduct = array_unique(array_merge($v1, $v2));
                    sort($newProduct);
                    $canInsert = true;
                    
                    foreach ($distribution as $key => &$p)
                    {
                        $intersection = array_intersect($p, $newProduct);
                        sort($intersection);
                        
                        if ($p == $intersection)
                            $canInsert = false;
                        else if ($newProduct == $intersection)
                            unset($distribution[$key]);
                    }
                    
                    if ($canInsert)
                        $distribution[] = $newProduct;
                }
            }
            
            unset($product[$i]);
            unset($product[$i+1]);
            
            $product[] = $distribution;
            
            $i += 2;
        }
        
        $result = reset($product);
        sort($result);
        
        return $result;
    }
    
    /**
     * @param array $distribution
     * @return mixed
     */
    public function selectPetricksProducts (array $distribution)
    {
        $products = [];
        
        foreach ($distribution as $product)
        {
            $nbTerms = count($product);
            
            if (!array_key_exists($nbTerms, $products))
                $products[$nbTerms] = [];
            
            $products[$nbTerms][] = $product;
        }
        
        ksort($products);
        return reset($products);
    }
    
    /**
     * @param int $rawMask
     * @return number
     */
    public function countNbLiterals ($rawMask)
    {
        return self::numberOfZeros(self::intToBits($rawMask,$this->_nbVariables));
    }
    
    public function selectTermsWithFewerLiterals (array $selectedPetricksPoducts)
    {
        $nbLiterals = [];
        foreach ($selectedPetricksPoducts as $key => $terms)
        {
            $count = 0;
            
            foreach ($terms as $term)
            {
                $count += $this->countNbLiterals($this->_primeImplicants[$term][1]);
            }
            
            $nbLiterals[$count] = $key;
        }
        
        ksort($nbLiterals);
        return $selectedPetricksPoducts[reset($nbLiterals)];
    }
    
    /**
     * @return array
     */
    public function petricksMethod ()
    {
        $chart = $this->madePrimeImplicantsChart();
        $product = $this->madePetricksProduct($chart);
        $distribution = $this->madePetricksDistribution($product);
        $selectedPetricksPoducts = $this->selectPetricksProducts($distribution);
        $selectedTerms = $this->selectTermsWithFewerLiterals($selectedPetricksPoducts);
        
        return $selectedTerms;
    }
    
    /**
     * @param array $selectedTerms
     */
    public function madeFinalEquation (array $selectedTerms)
    {
        $this->_minimizedFunction = "";
        foreach ($selectedTerms as $termKey)
        {
            $conjunction = "";
            if (self::USE_PARENTHESIS)
                $conjunction .= "(";
            
            $rawMask = self::intToBits($this->_primeImplicants[$termKey][1], $this->_nbVariables);
            $literals = self::intToBits($this->_primeImplicants[$termKey][0][0], $this->_nbVariables);
            
            for ($i = 0; $i < $this->_nbVariables; $i++)
            {
                if ($rawMask[$i] == 0)
                {
                    if ($literals[$i] == 0)
                        $conjunction .= self::LOGICAL_NOT;
                    
                    $conjunction .= $this->_varNames[$i] . self::LOGICAL_AND;
                }
            }
            
            $conjunction = substr($conjunction, 0, strlen($conjunction)-1);
            $this->_minimizedFunction .= $conjunction . " " . self::LOGICAL_OR . " ";
        }
        $this->_minimizedFunction = substr($this->_minimizedFunction, 0, strlen($this->_minimizedFunction)-3);
    }
    
    public function cleanAttributes ()
    {
        $this->_minimizedFunction = "";
        $this->_primeImplicants = [];
    }
    
    public function madeMinimizedFunction ()
    {
        $this->cleanAttributes();
        
        if ($this->isAlwaysFalse())
        {
           $this->_minimizedFunction = "0";
           return;
        }
        else if ($this->isAlwaysTrue())
        {
           $this->_minimizedFunction = "1";
           return;
        }
        
        $this->makeTerms();
        $this->determinePrimeImplicants();
        $selectedTerms = $this->petricksMethod();
        $this->madeFinalEquation($selectedTerms);
    }
    
    /**
     * @return number
     */
    public function getNbVariables()
    {
        return $this->_nbVariables;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->_output;
    }

    /**
     * @return array
     */
    public function getTerms()
    {
        return $this->_terms;
    }

    /**
     * @return array
     */
    public function getVarNames()
    {
        return $this->_varNames;
    }

    /**
     * @return string
     */
    public function getMinimizedFunction()
    {
        if (empty($this->_minimizedFunction))
            $this->madeMinimizedFunction();
        
        return $this->_minimizedFunction;
    }

    public function __toString()
    {
        return $this->getMinimizedFunction();
    }
    
    /**
     * @return array
     */
    public function getPrimeImplicants()
    {
        return $this->_primeImplicants;
    }
    
    static function __set_state(array $array)
    {
        return new MinimalDisjunctiveForm($array["_output"], $array["_nbVariables"], $array["_varNames"]);
    }
    
}

