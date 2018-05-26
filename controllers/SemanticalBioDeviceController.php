<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use app\models\SemanticalBioDevice;
use app\models\SemanticalBioDeviceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BooleanFunction;
use app\components\VeritasBooleanFunction;
use app\components\MinimalDisjunctiveForm;
use app\components\VeritasSemanticalBioDevice;
use app\components\SemanticalBioDevice as SemanticalBiologicalDevice;
use yii\db\Query;
use yii\data\Pagination;

/**
 * SemanticalBioDeviceController implements the CRUD actions for SemanticalBioDevice model.
 */
class SemanticalBioDeviceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SemanticalBioDevice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SemanticalBioDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SemanticalBioDevice model.
     * @param integer $id_dyck_functionnal_structure
     * @param integer $id_semantics
     * @return mixed
     */
    public function actionView($id_dyck_functionnal_structure, $id_semantics)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_dyck_functionnal_structure, $id_semantics),
        ]);
    }

    /**
     * Creates a new SemanticalBioDevice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SemanticalBioDevice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_dyck_functionnal_structure' => $model->id_dyck_functionnal_structure, 'id_semantics' => $model->id_semantics]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SemanticalBioDevice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_dyck_functionnal_structure
     * @param integer $id_semantics
     * @return mixed
     */
    public function actionUpdate($id_dyck_functionnal_structure, $id_semantics)
    {
        $model = $this->findModel($id_dyck_functionnal_structure, $id_semantics);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_dyck_functionnal_structure' => $model->id_dyck_functionnal_structure, 'id_semantics' => $model->id_semantics]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SemanticalBioDevice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_dyck_functionnal_structure
     * @param integer $id_semantics
     * @return mixed
     */
    public function actionDelete($id_dyck_functionnal_structure, $id_semantics)
    {
        $this->findModel($id_dyck_functionnal_structure, $id_semantics)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SemanticalBioDevice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_dyck_functionnal_structure
     * @param integer $id_semantics
     * @return SemanticalBioDevice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_dyck_functionnal_structure, $id_semantics)
    {
        if (($model = SemanticalBioDevice::findOne(['id_dyck_functionnal_structure' => $id_dyck_functionnal_structure, 'id_semantics' => $id_semantics])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSearch_sbd() {
        $model = new SemanticalBioDevice();

        if (isset($_POST['Sequence']['proposition'])) {

            // requete SQL A VOIR AVEC GUIGUI

            return $this->render('listeResult', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('searchSeq', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionResult() {
        $searchModel = new SemanticalBioDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (isset($_GET['fonction'])) {
            try {

                if (empty($_GET['fonction']))
                    throw new \exception('La fonction ne peut être vide');

                $fonction = trim(str_replace('-', '+', urldecode($_GET['fonction'])));

                $booleanFunction = new BooleanFunction($fonction);
                $veritas = new VeritasBooleanFunction($booleanFunction);

                setcookie("fonction", $fonction, time() + 365 * 24 * 3600);

                return $this->render('result', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'booleanFunction' => $booleanFunction,
                            'veritas' => $veritas,
                ]);
            } catch (\Exception $e) {
                return $this->render('erreur', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'erreur' => $e->getMessage(),
                ]);
            }
        }
        if (isset($_GET["dnf"])) {
            try {

                if (empty($_GET['dnf']) && $_GET['dnf'] != "0") {

                    throw new \exception('La fonction ne peut être vide');
                }
                $dnf = trim($_GET['dnf']);
                $nbVariables = log(strlen($dnf)) / log(2);
                if (intval($nbVariables) != $nbVariables) {
                    throw new \exception('There should be 2^n figures with n integer');
                }
                $fonction = (string) new MinimalDisjunctiveForm($dnf, $nbVariables);

                if ($fonction == "0") {
                    $fonction = "a.!a";
                }
                if ($fonction == "1") {
                    $fonction = "a+!a";
                }

                $booleanFunction = new BooleanFunction($fonction);
                $veritas = new VeritasBooleanFunction($booleanFunction);

                setcookie("dnf", $dnf, time() + 365 * 24 * 3600);

                return $this->render('result', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'booleanFunction' => $booleanFunction,
                            'veritas' => $veritas,
                ]);
            } catch (\Exception $e) {
                return $this->render('erreur', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'erreur' => $e->getMessage(),
                ]);
            }
        }
    }
    
    public function actionSearch_sbd_treatment() {
        
        $searchModel = new SemanticalBioDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        
        $functionArray;
       // if(isset($_POST['form'])){
        if ($_POST['form'] == 'wellFormedFormula') {
            $functionArray[0] = $_POST['Sequence']['proposition'];
        }
        if ($_POST['form'] == 'BinaryNumber') {
            $functionArray[0] = $_POST['Sequence']['BinaryNumber'];
        }
        if ($_POST['form'] == 'MultipleFunction') {
            $functionArray = explode("\n", $_POST['Sequence']['MultipleFunction']);
        }
        foreach ($functionArray as $key => $value) {
            if ($value == "") {
                throw new \Exception("Function can't be empty");
            }
        }
        $dnfArray = [];
        foreach ($functionArray as $key => $value) {
            try 
            {
                $fonction = $value;
                $booleanFunction;
                $nbVariables;
                try 
                {
                    $booleanFunction = new BooleanFunction($fonction);
                } 
                catch (\Exception $e) 
                {
                    $nbVariables = log(strlen($value),2);
                    if (intval($nbVariables) != $nbVariables) 
					{
                        throw new \exception('There should be 2^n figures with n integer');
                    }
                    $fonction = (string) new MinimalDisjunctiveForm($value, $nbVariables);

                    if ($fonction == "0") 
					{
                        $fonction = "a.!a";
                    }
                    if ($fonction == "1") 
					{
                        $fonction = "a+!a";
                    }
                    $booleanFunction = new BooleanFunction($fonction); 
                }
                $veritas = new VeritasBooleanFunction($booleanFunction);
                $dnfArray[]= $veritas->getMinimalOutput();
                //supprime les doublons
                $dnfArray=array_unique($dnfArray);
                
               
                //TEST QUERY
                /*$query = (new Query())->select(['*'])->from('boolean_function')->join("INNER JOIN", "permutation_class","boolean_function.permutation_class = permutation_class.permutation_class")
                        ->join("INNER JOIN", "semantical_bio_device","semantical_bio_device.permutation_class = permutation_class.permutation_class")
                        ->join("INNER JOIN", "semantics","semantics.id_semantics = semantical_bio_device.id_semantics")
                        ->join("INNER JOIN", "dyck_functionnal_structure","semantical_bio_device.id_dyck_functionnal_structure = dyck_functionnal_structure.id_dyck_functionnal_structure")
                        ->where(['dnf' => $dnfArray[0]])->one();
                */
        
            /*    $count=Yii::$app->db->createCommand('SELECT COUNT(*)FROM boolean_function INNER JOIN permutation_class ON (boolean_function.permutation_class = permutation_class.permutation_class) 
                INNER JOIN semantical_bio_device ON (permutation_class.permutation_class = semantical_bio_device.permutation_class) 
                INNER JOIN semantics ON (semantical_bio_device.id_semantics = semantics.id_semantics) 
                INNER JOIN dyck_functionnal_structure ON (semantical_bio_device.id_dyck_functionnal_structure = dyck_functionnal_structure.id_dyck_functionnal_structure) 
                WHERE dnf=:dnfvalue ',[':dnfvalue' => $dnfArray[0]])->queryScalar();
                var_dump($count);
                
                $dataProvider = new SqlDataProvider([
                'sql' => 'SELECT * ' . 
                'FROM boolean_function ' .
                'INNER JOIN permutation_class ON (boolean_function.permutation_class = permutation_class.permutation_class) ' .
                'INNER JOIN semantical_bio_device ON (permutation_class.permutation_class = semantical_bio_device.permutation_class) ' .
                'INNER JOIN semantics ON (semantical_bio_device.id_semantics = semantics.id_semantics) ' .
                'INNER JOIN dyck_functionnal_structure ON (semantical_bio_device.id_dyck_functionnal_structure = dyck_functionnal_structure.id_dyck_functionnal_structure) ' .
                'WHERE dnf=:dnfvalue ' ,
                'params' => [':dnfvalue' => $dnfArray[0]],
]);
                                $dataProvider->setTotalCount($count);
                                
				$dataProvider->setPagination([
					'pageSize' => 2,
				]);
				foreach ($dataProvider->getModels() as $m)
				{
					$sbd = new SemanticalBiologicalDevice();
					$sbd->hydrate($m);
					$newModels[] = $sbd->getModel();
				}
				
				$dataProvider->setModels($newModels);*/
                                
                
				$query = new Query;
				// compose the query
				$query
					->from('boolean_function')
					->innerjoin('permutation_class', "boolean_function.permutation_class = permutation_class.permutation_class")
					->innerjoin('semantical_bio_device', "permutation_class.permutation_class = semantical_bio_device.permutation_class")
					->innerjoin('semantics', "semantical_bio_device.id_semantics = semantics.id_semantics")
					->innerjoin('dyck_functionnal_structure', "semantical_bio_device.id_dyck_functionnal_structure = dyck_functionnal_structure.id_dyck_functionnal_structure")
					;
				
				foreach ($dnfArray as $dnf)
				{
					$query->orwhere(["dnf" => $dnf]);
				}
					
				$pages = new Pagination(['totalCount' => $query->count()]);
				$provider = new ActiveDataProvider([
					'query' => $query,
					'pagination' => $pages,
					'sort' => [
						'defaultOrder' => [
							'length' => SORT_ASC,
							'weak_constraint' => SORT_DESC, 
						],
						'attributes' => [
							'weak_constraint',
							'strong_constraint',
							'length',
							'nb_inputs',
							'gene_at_ends',
							'nb_parts',
							'nb_genes'
						],
				],]);
				
				$newModels = [];  
				foreach ($provider->getModels() as $m)
				{
					$sbd = new SemanticalBiologicalDevice();
					$sbd->hydrate($m);
					$newModels[] = $sbd->getModel();
				}
				$provider->setModels($newModels);
               
                /*$pagination = new Pagination(30, $semanticalBioDevicesManager->getNombre($booleanFunctionManager->getBooleanFunction(
                  ['dnf',bindec($veritas->getMinimalOutput ()),DB::SQL_AND,'nb_inputs',$veritas->getMinimalNbInputs()])->getId_fn()),
                  'listSeq.php?output='.$veritas->getMinimalOutput ()."&amp;nbInputs=".$veritas->getMinimalNbInputs());
                  if (isset($_GET['page'])) $pagination->setPageActuelle($_GET['page']);
                  $pagination->setPremier(false); */

                //Requête SQL pour récuperer la liste des séquences
                
                //$liste = $semanticalBioDevicesManager->getListe($pagination, ['dnf', bindec($veritas->getMinimalOutput()), DB::SQL_AND, 'nb_inputs', $veritas->getMinimalNbInputs()], array(['champ' => 'weak_constraint', 'sens' => DB::ORDRE_DESC], ['champ' => 'length', 'sens' => DB::ORDRE_ASC]));

                /* $tpl->assign(array(
                  'listeSequence' => $liste,
                  'pages' => $pagination->getPages()));

                  $tpl->display('listSeq.html'); */
                                
                return $this->render('result2', [
                            'searchModel' => $searchModel,
                            'data' => $provider,
                            'booleanFunction' => $booleanFunction,
                            'veritas' => $veritas,
							'pages'=>$pages
                            
                ]);
            } catch (\Exception $e) {
                return $this->render('erreur', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $provider,
                            'erreur' => $e->getMessage(),
                ]);
            }
      //  }
        }
        //SI on change de page avec la pagination
        /*else{
            
        
            return $this->render('result2', [
                            'data' => $dataProvider, 
                ]);
        }*/
    }

    public function actionInter_sbd_res() {
        if (isset($_GET['sequence'])) {
            try {
                if (empty($_GET['sequence']))
                    throw new \exception(t('The architecture cannot be empty'));

                $semanticalBioDevice = new SemanticalBiologicalDevice(urldecode($_GET['sequence']));
                $semanticalBioDevice->exceptionsIfInvalid();

                setcookie("sequence", urldecode($_GET['sequence']), time() + 365 * 24 * 3600);
                $veritas = new VeritasSemanticalBioDevice($semanticalBioDevice);

                return $this->render('detailView', [
                            'semanticalBioDevice' => $semanticalBioDevice,
                            'veritas' => $veritas,
                ]);
            } catch (\Exception $e) {

                return $this->render('erreur', [
                            'erreur' => $e->getMessage(),
                ]);
            }
        } else {
            if (isset($_COOKIE['sequence'])) {

                $sequence = $_COOKIE['sequence'];
                return $this->render('detailView', [
                            'sequence' => $sequence,
                ]);
            } else
                return $this->render('detailView', [
                            'sequence' => '',
                ]);
        }
    }

    //Interpreter des Sequence
    public function actionInter_sbd() {
        $model = new SemanticalBioDevice();

        if (isset($_POST['Sequence']['proposition'])) {

            // requete SQL A VOIR AVEC GUIGUI

            return $this->render('listeResult', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('interSbd', [
                        'model' => $model,
            ]);
        }
    }
}
