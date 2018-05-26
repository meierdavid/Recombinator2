<?php

namespace app\controllers;

use Yii;
use app\models\SemanticalBioDevice;
use app\models\SemanticalBioDeviceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BooleanFunction;
use app\components\VeritasBooleanFunction;
use app\components\MinimalDisjunctiveForm;
use yii\db\Query;

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
            try {
                $fonction = $value;
                $booleanFunction;
                $nbVariables;
                try {
                    $booleanFunction = new BooleanFunction($fonction);
                } catch (\Exception $e) {
                    $nbVariables = log(strlen($value)) / log(2);
                    if (intval($nbVariables) != $nbVariables) {
                        throw new \exception('There should be 2^n figures with n integer');
                    }
                    $fonction = (string) new MinimalDisjunctiveForm($value, $nbVariables);

                    if ($fonction == "0") {
                        $fonction = "a.!a";
                    }
                    if ($fonction == "1") {
                        $fonction = "a+!a";
                    }
                    $booleanFunction = new BooleanFunction($fonction); 
                }
                $veritas = new VeritasBooleanFunction($booleanFunction);
                $dnfArray[]= $veritas->getMinimalOutput();
                //supprime les doublons
                $dnfArray=array_unique($dnfArray);
                
                // Select all from Sequence Where  'dnf' = bindec($veritas->getMinimalOutput() and  
                // orderby weak_constraint DESC, length ASC
                
                //$searchResult = Sequence::find()->where('dnf > :dnf', [':dnf' => $dnfArray[0]])->orderBy('weak_constraint')->all();
                
                //TEST QUERY
                $user = (new Query())->select(['*'])->from('users')->join("INNER JOIN", "comment","users.id_user = comment.id_user")->all();
                $user = new ArrayDataProvider([
                    'allModels' => $user,
                    'sort' => [
                        'attributes' => ['last_name', 'first_name','content'],
                    ],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
                
                /*$user = new ActiveDataProvider(['query' => $query,
                                               'pagination' =>['pageSize' => 10,],
                                               'sort' => [
                                                   'defaultOrder'=> [
                                                    'created_at' => SORT_DESC,
                                                    'title' => SORT_ASC, 
                                                   ]
                                               ],
                                              ]);*/
                        
                    $searchResult3 = (new \yii\db\Query())
                ->select([ '*'])
                ->from('sequence')->join("INNER JOIN", 'semantics',"sequence.id_semantics = semantics.id_semantics")
                ->join("INNER JOIN", 'permutation_class', "sequence.permutation_class = permutation_class.permutation_class")
                ->join("INNER JOIN", 'dyck_functionnal_structure',"sequence.id_dyck_functionnal_structure = dyck_functionnal_structure.id_dyck_functionnal_structure")
                ->join("INNER JOIN", 'functions',"functions.permutation_class = permutation_class.permutation_class")
                ->where(['dnf' => $dnfArray[0]])
                ->all();
                //$searchResult2 = Sequence::find()->orderBy('weak_constraint')->joinWith('Permutassion_class')->joinWith('Functions')->where('dnf > :dnf', [':dnf' => $dnfArray[0]]);
                /*
                $searchResult3 = (new \yii\db\Query())
                ->select(['dyck_functionnal_structure', 'semantics','nb_inputs', 'length','nb_genes', 'gene_at_ends','nb_parts', 'nb_excisions','nb_inversions', 'weak_constraint','strong_constraint'])
                ->from('sequence','permutation_class','Semantics','dyck_functionnal_structure','functions')
                ->where(['dnf' => $dnfArray[0]])->all();*/
                   // chaine de 01 avec la fonction get minimal output
                //$semanticalBioDevicesManager = new SemanticalBioDevicesManager($bdd);
                //$booleanFunctionManager = new BooleanFunctionManager($bdd);

                /* $pagination = new Pagination(30, $semanticalBioDevicesManager->getNombre($booleanFunctionManager->getBooleanFunction(
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

                return $this->render('result', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $searchResult3,
                            'booleanFunction' => $booleanFunction,
                            'veritas' => $veritas,
                            'user' => $user
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

    public function actionInter_sbd_res() {
        if (isset($_GET['sequence'])) {
            try {
                if (empty($_GET['sequence']))
                    throw new \exception(t('The architecture cannot be empty'));

                $semanticalBioDevice = new \app\components\SemanticalBioDevice(urldecode($_GET['sequence']));
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
