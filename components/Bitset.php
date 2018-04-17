<?php
#################################################
#						#
#	Bitset.php				#
#	class array de bits       		#
#	          				#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 

	namespace app\components; 
        use Yii;
        use yii\base\Component;
        use yii\base\InvalidConfigException;
	
	class Bitset implements \ArrayAccess, \SeekableIterator, \Countable
	{
                private $_taille;
                private $_valeur;
                private $_position = 0;
            
                public function __construct ($arg1 = 64, $valeur = 0)
                {
                        if (is_array($arg1))
                        {
                                $this->_valeur = 0;
                                $this->_taille = count($arg1);
                                $i = 0;
                                foreach ($arg1 as $val)
                                {
                                    $this->_valeur += (bool) $val;
                                    $i++;
                                    if ($i != count($arg1))
                                        $this->_valeur = $this->_valeur << 1;
                                }
                        }
                        else
                        {
                                $this->setTaille($arg1);
                                $this->setValeur($valeur);
                        }
                }
                
                public function setTaille ($taille)
                {
                        if ($taille <= 64)
                            $this->_taille = $taille;
                        else 
                            $this->_taille = 64;
                }
            
                public function setValeur($valeur)
                {
                        if ($valeur >= 0)
                            $this->_valeur = intval($valeur) % ($this->_taille!=64?pow(2, $this->_taille):1);
                        else
                            $this->_valeur = (intval($valeur) << (64-$this->_taille)) >> (64-$this->_taille);
                }
                
                public function getValeur ()
                {
                        return $this->_valeur;
                }
		
		private function getBit ($key)
		{
                        $bit = $this->_valeur >> $key;
                        return abs ($bit % 2);
		}
		
		private function setBit ($key, $bit)
		{
                        $this->_valeur = (bool) $bit ? ($this->_valeur | 1 << $key) : ($this->_valeur & ~(1 << $key));
		}
		
		private function unsetBit ($key)
		{
                        $before = $this->_valeur % pow (2,$key);
                        $after = $this->_valeur >> ($key+1);
                        $this->_valeur = $before + ($after << $key);
                        $this->_taille--;
		}
		
		public function current()
		{
			return $this->getBit($this->_position);
		}
		
		public function key()
		{
			return $this->_position;
		}
		
		public function next()
		{
			$this->_position++;
		}
		
		public function rewind()
		{
			$this->_position = 0;
		}
		
		public function valid()
		{
			return $this->_position < $this->_taille;
		}
		
		public function seek($position)
		{
			$anciennePosition = $this->_position;
			$this->_position = $position;
			
			if (!$this->valid())
			{
				trigger_error('La position spécifiée n\'est pas valide', E_USER_WARNING);
				$this->_position = $anciennePosition;
			}
		}
		
		public function offsetExists($key)
		{
			return $key < $this->_taille;
		}
		
		public function offsetGet($key)
		{
			return $this->getBit($key);
		}
		
		public function offsetSet($key, $value)
		{
			$this->setBit($key, $value);
		}
		
		public function offsetUnset($key)
		{
			$this->unsetBit($key);
		}
		
		public function count()
		{
			return $this->_taille;
		}
		
		public function fetch ()
		{
			if ($this->valid())
			{
				$current = $this->current();
				$this->next();
				return $current;
			}
			else return false;
		}
		
		public static function staticNot($b)
		{
                        $newVal = (~$b->_valeur) % pow(2, $b->_taille+1);
                        return new Bitset($newVal, $b->_taille);
		}
		
		public function not ()
		{
                        $this->setValeur(~$this->_valeur);
		}
		
		public function reverse ()
		{
                        $newValeur = 0;
                        for ($i = 0; $i < $this->_taille; $i++)
                        {
                                $newValeur += intval($this->getBit($i));
                                if ($i < $this->_taille-1)
                                    $newValeur = $newValeur << 1;
                        }
                        $this->_valeur = $newValeur;
		}
		
		public function __tostring()
		{
                        return substr(str_pad(decbin($this->_valeur), $this->_taille, "0", STR_PAD_LEFT), -1 * $this->_taille); 
		}
		
                static function __set_state(array $array) 
                {
                    $tmp = new Bitset($array['_taille'], $array['_valeur']);
                    return $tmp;
                }
	}
