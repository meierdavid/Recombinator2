<?php
#################################################
#						#
#	VeritasSemanticalBioDevice.php			#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 

    if ( !defined('Framework') ) exit;
    
    class VeritasSemanticalBioDevice extends Veritas
    {
	private $_semanticalBioDevice;
	
	public function __construct (SemanticalBioDevice $semanticalBioDevice)
	{
	    if ($semanticalBioDevice->isValid())
	    {
		$this->_howManyInputs = $semanticalBioDevice->howManyInputs();
		$this->_semanticalBioDevice = $semanticalBioDevice;
		$this->makeOutputs();
	    }
	    else
		throw new exception(t("The semanticalBioDevice %e is invalid.", [$semanticalBioDevice->to_string()]));
	}
	
	public function makeOutputs ()
	{
	    $size = pow(2, $this->_howManyInputs);
	    $this->_outputs = 0;
	    
	    for ($i = 0; $i < $size; ++$i)
	    {
		/*if ($this->_semanticalBioDevice->getSubSemanticalBioDevice($i)->isGeneActivated() != -1)
		{*/
		    $this->_outputs += intval($this->_semanticalBioDevice->getSubSemanticalBioDevice($i)->isGeneActivated());
		    if ($i < $size-1)
			$this->_outputs = $this->_outputs << 1;
		/*}
		else 
		    throw new exception("Impossible to create the truth table, the semanticalBioDevice is not valid (promoters too far from genes).\n SemanticalBioDevice: " . $this->_semanticalBioDevice->to_string() . "\n Invalid subsemanticalBioDevice: " . $this->_semanticalBioDevice->getSubSemanticalBioDevice($i)->to_string() . "\n");*/
	    }
	}
	
	public function to_string ()
	{
	    $table = "";
	    $size = pow(2, $this->_howManyInputs);
	    $outputs = $this->_outputs;

	    $inputNames = $this->_semanticalBioDevice->inputNamesList();
	    

	    foreach ($inputNames as $name)
	    {
		$table .= $name;
		$table .= "\t";
	    }

	    $table .= "outputs \t";
	    $table .= "semanticalBioDevice\n";

	    $bottom = "";
	    for ($i = $size-1; $i >= 0; --$i)
	    {
		$inputs = $i;
		$line = "";
		for ($j = 0; $j < $this->_howManyInputs; ++$j)
		{
		    $line = (int)($inputs % 2) . "\t" .$line;
		    $inputs = $inputs >> 1;
		}
		$semanticalBioDevice = "\t" . $this->_semanticalBioDevice->getSubSemanticalBioDevice($i)->to_string();

		$bottom = $line . (int)($outputs % 2) . $semanticalBioDevice . "\t \n" . $bottom;
		$outputs = $outputs >> 1;
	    }
	    $table .= $bottom;
	    
	    return $table;
	}
	
	public function toHTML ()
	{
	    $table = '<table class="truthTable"><thead><tr>';
	    $size = pow(2, $this->_howManyInputs);
	    $outputs = $this->_outputs;

	    $inputNames = $this->_semanticalBioDevice->inputNamesList();

	    $table .= '<th class="thCornerL">Séquence</th>';
	    foreach ($inputNames as $name)
	    {
		$table .= '<th>' . $name;
		$table .= "</th>";
	    }

	    $table .= '<th class="thCornerR"> outputs </th></tr></thead>';

	    $bottom = "";
	    $row = 0;
	    for ($i = $size-1; $i >= 0; --$i)
	    {
		$inputs = $i;
		$line = "";
		for ($j = 0; $j < $this->_howManyInputs; ++$j)
		{
		    $line = '<td>' . (int)($inputs % 2) . "</td>" .$line;
		    $inputs = $inputs >> 1;
		}
		$line = "<td>" . $this->_semanticalBioDevice->getSubSemanticalBioDevice($i)->toHTML() . '</td>' . $line;

		$bottom = '<tr class="row'.(int)$row.'">' . $line . '<td>' . (int)($outputs % 2) . "</td></tr>" . $bottom;
		$row = !$row;
		$outputs = $outputs >> 1;
	    }
	    $table .= $bottom . '</table>';
	    
	    return $table;
	}
	
	public function inputNamesList ()
	{
	    return $this->_semanticalBioDevice->inputNamesList();
	}
    }
