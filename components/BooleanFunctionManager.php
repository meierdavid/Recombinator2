<?php
#################################################
#						#
#	BooleanFunctionManager.php			#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 

if ( !defined('Framework') ) exit;  

class BooleanFunctionManager
{
	use ToolsForManagers;
	
	public function __construct ($bdd)
	{
		$this->setBdd($bdd);
	}
	
	public function getListe ($pagination = null, $listeParametres = null, $ordre = null)
	{
		if ($listeParametres != null || $ordre != null)
		{
			$champs = $this->listeColonnes(['boolean_function', 'permutation_class']);
			$champs[] = "b.permutation_class";
		}
		else $champs = null;
		
		$requete = "SELECT * FROM boolean_function b
							JOIN permutation_class p ON p.permutation_class=b.permutation_class";
		
		$limit = "";
		if ($pagination !=  null)
			$limit = $pagination->getLimit();
		
		$nomCache = md5($requete.serialize($champs).serialize($listeParametres).serialize($ordre).serialize($limit));
		
		$$nomCache = new CacheArray($nomCache, 0); 
		$cacheArrayManager = new CacheArrayManager;
		$cacheArrayManager->readCache($$nomCache);
		
		if ($cacheArrayManager->readCache($$nomCache) !== false)  return $$nomCache;
		else
		{
			$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, $limit);
			$liste = $this->genereListe($req, 'BooleanFunction');
			
			$semanticalBioDevicesManager = new SemanticalBioDeviceManager($this->_bdd);
			foreach ($liste as &$booleanFunction)
				$booleanFunction->setNbSemanticalBioDevices($semanticalBioDevicesManager->getNombre($booleanFunction->getPermutation_class()));
			
			$$nomCache->setContenu($liste); 
			$cacheArrayManager->writeCache($$nomCache);
			
			return $liste;
		}
	}
	
	public function getBooleanFunction ($listeParametres = null, $ordre = null)
	{
		if ($listeParametres != null || $ordre != null)
			$champs = $this->listeColonnes(['boolean_function']);
		else $champs = null;
		
		$requete = "SELECT * FROM boolean_function b
		JOIN permutation_class p ON p.permutation_class=b.permutation_class";
		
		$nomCache = md5($requete.serialize($champs).serialize($listeParametres).serialize($ordre).serialize("LIMIT 1"));
		
		$$nomCache = new CacheArray($nomCache, 0); 
		$cacheArrayManager = new CacheArrayManager;
		$cacheArrayManager->readCache($$nomCache);
		
		if ($cacheArrayManager->readCache($$nomCache) !== false)  return $$nomCache->getContenu();
		else
		{
			$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, "LIMIT 1");
			$liste = $this->genereListe($req, 'BooleanFunction');
			
			$wordsManager = new SemanticalBioDeviceManager($this->_bdd);
			foreach ($liste as &$booleanFunction)
				$booleanFunction->setNbSemanticalBioDevices($wordsManager->getNombre($booleanFunction->getId_fn()));
			
			if (key_exists(0, $liste))
				$$nomCache->setContenu($liste[0]); 
			else 
				$$nomCache->setContenu([]);
			
			$cacheArrayManager->writeCache($$nomCache);
			
			return $$nomCache->getContenu();
		}
	}
	
	public function getNombre ()
	{
		$req = $this->_bdd->query("SELECT COUNT(*) AS count FROM boolean_function");
		
		return $req->fetch(PDO::FETCH_ASSOC)['count'];
	}
}
