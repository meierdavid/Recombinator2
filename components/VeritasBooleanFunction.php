<?php
#################################################
#						#
#	VeritasBooleanFunction.php			#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 

    if ( !defined('Framework') ) exit;
    
    class VeritasBooleanFunction extends Veritas
    {
	private $_booleanFunction;
	
	public function __construct (BooleanFunction $booleanFunction)
	{
	    $this->_howManyInputs = $booleanFunction->howManyInputs();
	    $this->_booleanFunction = $booleanFunction;
	    $this->makeOutputs();
	}
	
	public function makeOutputs ()
	{
	    $size = pow(2, $this->_howManyInputs);
	    $this->_outputs = 0;
	    
	    for ($i = 0; $i < $size; ++$i)
	    {
		$activatingList = [];
		$inputs = $i;
		for ($j = 0; $j < $this->_howManyInputs; ++$j)
		{
		    $activatingList[] = $inputs % 2;
		    $inputs = $inputs >> 1;
		}
		
		$this->_outputs += $this->_booleanFunction->isOutputTrue(array_reverse($activatingList));
		if ($i < $size-1)
		    $this->_outputs = $this->_outputs << 1;
	    }
	}
	
	public function to_string ()
	{
	    $table = "";
	    $size = pow(2, $this->_howManyInputs);
	    $outputs = $this->_outputs;

	    $inputNames = $this->_booleanFunction->inputNamesList();

	    foreach ($inputNames as $name)
	    {
		$table .= $name;
		$table .= "\t";
	    }

	    $table .= "outputs \n";

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

		$bottom = $line . (int)($outputs % 2) . "\t \n" . $bottom;
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

	    $inputNames = $this->_booleanFunction->inputNamesList();

	    $first = true;
	    foreach ($inputNames as $name)
	    {
		if ($first)
		{
		    $table .= '<th class="thCornerL">' . $name;
		    $first = false;
		}
		else
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

		$bottom = '<tr class="row'.(int)$row.'">' . $line . '<td>' . (int)($outputs % 2) . "</td></tr>\n" . $bottom;
		$row = !$row;
		$outputs = $outputs >> 1;
	    }
	    $table .= $bottom . '</table>';
	    
	    return $table;
	}
	
	public function inputNamesList ()
	{
	    return $this->_booleanFunction->inputNamesList();
	}
    }