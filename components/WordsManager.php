<?php
#################################################
#						#
#	WordsManager.php			#
#	Créateur : Guillaume KIHLI		#
#						#
################################################# 
    namespace app\components; 
    use Yii;
    use yii\base\Component;
    use yii\base\InvalidConfigException;
    
    class WordsManager
    {
	use ToolsForManagers;
	    
	public function __construct ($bdd)
	{
	    $this->setBdd($bdd);
	}
	    
	public function getListe (Pagination $pagination, $listeParametres = null, $ordre = null)
	{
	    if ($listeParametres != null || $ordre != null)
		$champs = $this->listeColonnes(['sequences', 'logical_functions', 'sequences_features', 'implements', 'namings', 'dyck_words']);
	    else $champs = null;
		    
	    $requete = "SELECT s.id_s, sequence, weak_constraint, strong_constraint, length, word, names
			    FROM sequences s
			    JOIN implements i ON i.id_s=s.id_s
			    JOIN logical_functions lf ON lf.id_lf=i.id_lf 
			    JOIN sequences_features sf ON sf.id_sf=s.id_sf  
			    JOIN dyck_words dw ON dw.id_dw=s.id_dw
			    JOIN namings n ON n.id_n=i.id_n
			    ";
	    
	    $nomCache = md5($requete.serialize($champs).serialize($listeParametres).serialize($ordre).serialize($pagination->getLimit()));
	    
	    $$nomCache = new CacheArray($nomCache, 0); 
	    $cacheArrayManager = new CacheArrayManager;
	    $cacheArrayManager->readCache($$nomCache);
	    
	    if ($cacheArrayManager->readCache($$nomCache) !== false)  return $$nomCache;
	    else
	    {
		$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, $pagination->getLimit());
		$liste = $this->genereListe($req, 'Word');
		
		$$nomCache->setContenu($liste); 
		$cacheArrayManager->writeCache($$nomCache);
		
		return $liste;
	    }
	}
	    
	public function getWord ($listeParametres = null, $ordre = null)
	{
	    if ($listeParametres != null || $ordre != null)
		$champs = $this->listeColonnes(['sequences', 'logical_functions', 'sequences_features', 'implements', 'namings', 'dyck_words']);
	    else $champs = null;
	    
	    $champs[] = "s.id_s";
		    
	    $requete = "SELECT s.id_s, sequence, weak_constraint, strong_constraint, length, word, names
			    FROM sequences s
			    JOIN implements i ON i.id_s=s.id_s
			    JOIN logical_functions lf ON lf.id_lf=i.id_lf 
			    JOIN sequences_features sf ON sf.id_sf=s.id_sf  
			    JOIN dyck_words dw ON dw.id_dw=s.id_dw
			    JOIN namings n ON n.id_n=i.id_n
			    ";
	    
	    $nomCache = md5($requete.serialize($champs).serialize($listeParametres).serialize($ordre).serialize(" LIMIT 1"));
	    
	    $$nomCache = new CacheArray($nomCache, 0); 
	    $cacheArrayManager = new CacheArrayManager;
	    $cacheArrayManager->readCache($$nomCache);
	    
	    if ($cacheArrayManager->readCache($$nomCache) !== false)  return $$nomCache->getContenu();
	    else
	    {
		$req = $this->executeRequeteListe($requete, $champs, $listeParametres, $ordre, " LIMIT 1");
		$liste = $this->genereListe($req, 'Word');
		
		$$nomCache->setContenu($liste[0]); 
		$cacheArrayManager->writeCache($$nomCache);
		
		return $liste[0];
	    }
	}
	
	public function getNombre ($id_fn = null)
	{
	    if ($id_fn != null && is_numeric($id_fn)) $reqId_fn = " WHERE id_lf = :id_lf ";
	    else $reqId_fn = '';
	    
	    $req = $this->_bdd->prepare("SELECT COUNT(*) AS count FROM implements ".$reqId_fn);
	    
	    if ($id_fn != null && is_numeric($id_fn)) $req->bindValue(':id_lf', $id_fn, PDO::PARAM_INT);
	    $cache = $req->executeWithCache(null, 0, 'nb_words_'.$id_fn);
	    
	    return $cache->fetch(PDO::FETCH_ASSOC)['count'];
	}
    }
