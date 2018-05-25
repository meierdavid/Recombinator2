<?php

class PermutationClassManager
{
	use ToolsForManagers;
	private $_booleanFunctionManager;
	private $_semanticalBioDeviceManager;
	
	public function __construct ($bdd)
	{
		$this->setBdd($bdd);
		$this->_booleanFunctionManager = new BooleanFunctionManager($bdd);
		$this->_semanticalBioDeviceManager = new SemanticalBioDeviceManager($bdd);
	}
	
	public function getListe (Pagination $pagination, $listeParametres = null, $ordre = null)
	{
		if ($listeParametres != null || $ordre != null)
		{
			$champs = $this->listeColonnes(['logical_functions', 'permutation_class']);
		}
		else $champs = null;
		
		$requete = "SELECT * FROM permutation_class ";
		
		$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, $pagination->getLimit());
		$liste = $this->genereListe($req, 'PermutationClass');
		
		foreach ($liste as $class)
		{
			foreach ($this->_booleanFunctionManager->getListe(null, ['b.permutation_class', $class->getPermutation_class()]) as $bf)
			{
				$class->addBooleanFunction($bf);
				$class->setNbSemanticalBioDevices($this->_semanticalBioDeviceManager->getNombre($class->getPermutation_class()));
			}
		}
		
		return $liste;
	}
	
	public function getPermutationClass ($id)
	{
		$champs = $this->listeColonnes(['logical_functions', 'permutation_class']);
		$requete = "SELECT * FROM permutation_class ";
		$listeParametres = ["permutation_class", $id];
		
		$req = $this->executeRequeteListe($requete, $champs, $listeParametres, null, " LIMIT 1");
		$liste = $this->genereListe($req, 'PermutationClass');
		
		foreach ($liste as $class)
		{
			foreach ($this->_booleanFunctionManager->getListe(null, ['b.permutation_class', $class->getPermutation_class()]) as $bf)
			{
				$class->addBooleanFunction($bf);
				$class->setNbSemanticalBioDevices($this->_semanticalBioDeviceManager->getNombre($class->getPermutation_class()));
			}
		}
		
		return reset($liste);
	}
	
	public function getNombre ()
	{
		$req = $this->_bdd->query("SELECT COUNT(*) AS count FROM permutation_class");
		
		return $req->fetch(PDO::FETCH_ASSOC)['count'];
	}
}
