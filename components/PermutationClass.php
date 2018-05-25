<?php

class PermutationClass 
{
	use Hydrate;
	
	private $_permutation_class;
	private $_nb_inputs;
	private $_booleanFunctions = [];
	private $_nbSemanticalBioDevices = 0;
	
	public function addBooleanFunction ($booleanFunction)
	{
		$this->_booleanFunctions[] = $booleanFunction;
	}
	
	public function setPermutation_class ($id)
	{
		$this->_permutation_class = $id;
	}
	
	public function setNb_inputs ($nb)
	{
		$this->_nb_inputs = $nb;
	}
	
	public function setNbSemanticalBioDevices ($nb)
	{
		$this->_nbSemanticalBioDevices = $nb;
	}
	
	public function getBooleanFunctions () { return $this->_booleanFunctions; }
	public function getPermutation_class () { return $this->_permutation_class; }
	public function getNb_inputs () { return $this->_nb_inputs; }
	public function getNbSemanticalBioDevices () { return $this->_nbSemanticalBioDevices; }
}
