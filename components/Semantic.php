<?php
#################################################
#						#
#	Semantic.php				#
#	class représentant la sémantique        #
#	d'une séquence génétique		#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 

	namespace app\components; 
        use Yii;
        use yii\base\Component;
        use yii\base\InvalidConfigException;
	
	class Semantic
	{
                private $_semanticVector;
            
                public function __construct ($value = 0)
                {
                        $this->_semanticVector = new Bitset(7);
                        $this->_semanticVector->setValeur($value);
                }

                public function getSemanticVector()
                {
                        return $this->_semanticVector;
                }
                
                public function getSemanticValue()
                {
                        return $this->_semanticVector->getValeur();
                }
                
                public function isEqualTo(Semantic $s)
                {
                        return $this->getSemanticValue() == $s->getSemanticValue();
                }
                
                public function setExpressed()
                {
                        $this->_semanticVector = new Bitset([0,0,0,1,0,0,0]);
                }
                
                public function setPF($value)
                {
                        $this->_semanticVector[6] = $value; 
                        if ($value)
                        {
                            $this->_semanticVector[5] = 0;
                            $this->_semanticVector[3] = 0;
                        }
                }
                
                public function setPR($value)
                {
                        $this->_semanticVector[0] = $value; 
                        if ($value)
                        {
                            $this->_semanticVector[1] = 0;
                            $this->_semanticVector[3] = 0;
                        }
                }
                
                public function setTF($value)
                { 
                        $this->_semanticVector[5] = $value;
                        if ($value)
                        {
                            $this->_semanticVector[6] = 0;
                            $this->_semanticVector[4] = 0;
                            $this->_semanticVector[3] = 0;
                        }
                }
                
                public function setTR($value)
                {
                        $this->_semanticVector[1] = $value; 
                        if ($value)
                        {
                            $this->_semanticVector[0] = 0;
                            $this->_semanticVector[2] = 0;
                            $this->_semanticVector[3] = 0;
                        }
                }
                
                public function setGF($value)
                {
                        $this->_semanticVector[4] = $value; 
                        if ($value)
                        {
                            $this->_semanticVector[5] = 0;
                            $this->_semanticVector[3] = 0;
                        }
                }
                
                public function setGR($value)
                {
                        $this->_semanticVector[2] = $value; 
                        if ($value)
                        {
                            $this->_semanticVector[1] = 0;
                            $this->_semanticVector[3] = 0;
                        }
                }
                
                public function isExpressed()
                {
                        return $this->_semanticVector[3];
                }
                
                public function isPF()
                {
                        return $this->_semanticVector[6];
                }
                
                public function isPR()
                {
                        return $this->_semanticVector[0];
                }
                
                public function isTF()
                {
                        return $this->_semanticVector[5];
                }
                
                public function isTR()
                {
                        return $this->_semanticVector[1];
                }
                
                public function isGF()
                {
                        return $this->_semanticVector[4];
                }
                
                public function isGR()
                {
                        return $this->_semanticVector[2];
                }
                
                public function isEpsilon()
                {
                        return $this->_semanticVector->getValeur() == 0;
                }
                
                public function isForwardEpsilon()
                {
                        return !$this->isGF() && !$this->isTF() && !$this->isPF() && !$this->isExpressed();
                }
                
                public function isReverseEpsilon()
                {
                        return !$this->isGR() && !$this->isTR() && !$this->isPR() && !$this->isExpressed();
                }
                
                public function isMirror()
                {
                        return $this->isGF() == $this->isGR() && $this->isPF() == $this->isPR() && $this->isTF() == $this->isTR();
                }
                
                public static function staticCombine (Semantic $s1, Semantic $s2)
                {
                        // if both are equals or the s contains an empty vector
                        if ($s1->isEpsilon())
                            return $s2;
                        
                        // If empty vector
                        if ($s2->isEpsilon())
                            return $s1;
                        
                        $result = new Semantic();
                        
                        // If gene is expressed on either one, it is always (0,0,0,1,0,0,0)
                        if ($s1->isExpressed() || $s2->isExpressed())
                        {
                            $result->setExpressed();
                            return $result;
                        }
                        
                        // If there is no gene expressed
                        // Forward
                        if ($s1->isGF() && $s1->isPF())
                        {
                            if ($s2->isGF())
                            {
                                $result->setExpressed();
                                return $result;
                            }
                            
                            else if ($s2->isTF())
                                $result->setGF(1);
                            
                            else
                            {
                                $result->setGF(1);
                                $result->setPF(1);
                            }
                        }
                        
                        else if ($s1->isPF())
                        {
                            if ($s2->isGF())
                            {
                                $result->setExpressed();
                                return $result;
                            }
                            
                            else if ($s2->isTF())
                                $result->setTF(1);
                            
                            else
                                $result->setPF(1);
                        }
                        
                        else if ($s1->isTF())
                        {
                            if ($s2->isPF())
                                $result->setPF(1);
                            
                            else
                                $result->setTF(1);
                        }
                        
                        else if ($s1->isGF())
                        {
                            if ($s2->isPF())
                            {
                                $result->setPF(1);
                                $result->setGF(1);
                            }
                            else
                                $result->setGF(1);
                        }
                        
                        else
                        {
                            $result->setPF($s2->isPF());
                            $result->setTF($s2->isTF());
                            $result->setGF($s2->isGF());
                        }
                        
                        // Reverse
                        if ($s2->isGR() && $s2->isPR())
                        {
                            if ($s1->isGR())
                            {
                                $result->setExpressed();
                                return $result;
                            }
                            
                            else if ($s1->isTR())
                                $result->setGR(1);
                            
                            else
                            {
                                $result->setGR(1);
                                $result->setPR(1);
                            }
                        }
                        
                        else if ($s2->isPR())
                        {
                            if ($s1->isGR())
                            {
                                $result->setExpressed();
                                return $result;
                            }
                            
                            else if ($s1->isTR())
                                $result->setTR(1);
                            
                            else
                                $result->setPR(1);
                        }
                        
                        else if ($s2->isTR())
                        {
                            if ($s1->isPR())
                                $result->setPR(1);
                            
                            else
                                $result->setTR(1);
                        }
                        
                        else if ($s2->isGR())
                        {
                            if ($s1->isPR())
                            {
                                $result->setPR(1);
                                $result->setGR(1);
                            }
                            else
                                $result->setGR(1);
                        }
                        
                        else
                        {
                            $result->setPR($s1->isPR());
                            $result->setTR($s1->isTR());
                            $result->setGR($s1->isGR());
                        }
                        
                        return $result;
                }
                
                public function combine(Semantic $s)
                {
                        $newSemantic = Semantic::staticCombine($this, $s);
                        $this->_semanticVector = $newSemantic->getSemanticVector();
                }
                
                public function reverse()
                {
                        $this->_semanticVector->reverse();
                }
                
                public function excise()
                {
                        $this->_semanticVector = new Bitset(7,0);
                }
                
                public function __tostring()
                {
                        return (string) $this->_semanticVector;
                }
                
                static function __set_state(array $array) 
                {
                    $tmp = new Semantic();
                    $tmp->_semanticVector = $array['_semanticVector'];
                    return $tmp;
                }
	}
