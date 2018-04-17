<?php
#################################################
#						#
#	VeritasWord.php			#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 
    namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
    
    class VeritasWord extends Veritas
    {
	private $_word;
	
	public function __construct (Word $word)
	{
	    if ($word->isValid())
	    {
		$this->_howManyInputs = $word->howManyInputs();
		$this->_word = $word;
		$this->makeOutputs();
	    }
	    else
		throw new \exception(t("The word %e is invalid.", [$word->to_string()]));
	}
	
	public function makeOutputs ()
	{
	    $size = pow(2, $this->_howManyInputs);
	    $this->_outputs = 0;
	    
	    for ($i = 0; $i < $size; ++$i)
	    {
		/*if ($this->_word->getSubWord($i)->isGeneActivated() != -1)
		{*/
		    $this->_outputs += intval($this->_word->getSubWord($i)->isGeneActivated());
		    if ($i < $size-1)
			$this->_outputs = $this->_outputs << 1;
		/*}
		else 
		    throw new exception("Impossible to create the truth table, the word is not valid (promoters too far from genes).\n Word: " . $this->_word->to_string() . "\n Invalid subword: " . $this->_word->getSubWord($i)->to_string() . "\n");*/
	    }
	}
	
	public function to_string ()
	{
	    $table = "";
	    $size = pow(2, $this->_howManyInputs);
	    $outputs = $this->_outputs;

	    $inputNames = $this->_word->inputNamesList();
	    

	    foreach ($inputNames as $name)
	    {
		$table .= $name;
		$table .= "\t";
	    }

	    $table .= "outputs \t";
	    $table .= "word\n";

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
		$word = "\t" . $this->_word->getSubWord($i)->to_string();

		$bottom = $line . (int)($outputs % 2) . $word . "\t \n" . $bottom;
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

	    $inputNames = $this->_word->inputNamesList();

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
		$line = "<td>" . $this->_word->getSubWord($i)->toHTML() . '</td>' . $line;

		$bottom = '<tr class="row'.(int)$row.'">' . $line . '<td>' . (int)($outputs % 2) . "</td></tr>" . $bottom;
		$row = !$row;
		$outputs = $outputs >> 1;
	    }
	    $table .= $bottom . '</table>';
	    
	    return $table;
	}
	
	public function inputNamesList ()
	{
	    return $this->_word->inputNamesList();
	}
    }
