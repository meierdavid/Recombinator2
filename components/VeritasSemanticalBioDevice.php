<?php
namespace app\components; 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

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
			$this->_outputs += intval($this->_semanticalBioDevice->getSubSemanticalBioDevice($i)->isGeneActivated());
			if ($i < $size-1)
				$this->_outputs = $this->_outputs << 1;
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
			$table .= '<th><span class="site_' . $name . '">' . $name;
			$table .= "</span></th>";
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
