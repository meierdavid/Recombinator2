<?php
#################################################
#						#
#	LogicManager.php			#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 
    
    namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
    

    
    class LogicManager
    {
	use ToolsForManagers;
	    
	public function __construct ($bdd)
	{
	    $this->setBdd($bdd);
	}
	    
	public function getListe (Pagination $pagination, $listeParametres = null, $ordre = null)
	{
	    if ($listeParametres != null || $ordre != null)
		$champs = $this->listeColonnes(['logical_functions']);
	    else $champs = null;
		    
	    $requete = "SELECT * FROM logical_functions ";
	    
	    $nomCache = md5($requete.serialize($champs).serialize($listeParametres).serialize($ordre).serialize($pagination->getLimit()));
	    
	    $$nomCache = new CacheArray($nomCache, 0); 
	    $cacheArrayManager = new CacheArrayManager;
	    $cacheArrayManager->readCache($$nomCache);
	    
	    if ($cacheArrayManager->readCache($$nomCache) !== false)  return $$nomCache;
	    else
	    {
		$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, $pagination->getLimit());
		$liste = $this->genereListe($req, 'Logic');
		
		$wordsManager = new WordsManager($this->_bdd);
		foreach ($liste as &$logic)
		    $logic->setNbWords($wordsManager->getNombre($logic->getId_fn()));
		
		$$nomCache->setContenu($liste); 
		$cacheArrayManager->writeCache($$nomCache);
		
		return $liste;
	    }
	}
	    
	public function getLogic ($listeParametres = null, $ordre = null)
	{
	    if ($listeParametres != null || $ordre != null)
		$champs = $this->listeColonnes(['logical_functions']);
	    else $champs = null;
		    
	    $requete = "SELECT * FROM logical_functions ";
	    
	    $nomCache = md5($requete.serialize($champs).serialize($listeParametres).serialize($ordre).serialize("LIMIT 1"));
	    
	    $$nomCache = new CacheArray($nomCache, 0); 
	    $cacheArrayManager = new CacheArrayManager;
	    $cacheArrayManager->readCache($$nomCache);
	    
	    if ($cacheArrayManager->readCache($$nomCache) !== false)  return $$nomCache->getContenu();
	    else
	    {
		$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, "LIMIT 1");
		$liste = $this->genereListe($req, 'Logic');
		
		$wordsManager = new WordsManager($this->_bdd);
		foreach ($liste as &$logic)
		    $logic->setNbWords($wordsManager->getNombre($logic->getId_fn()));
		
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
	    $req = $this->_bdd->query("SELECT COUNT(*) AS count FROM logical_functions");
	    
	    return $req->fetch(PDO::FETCH_ASSOC)['count'];
	}
    }
