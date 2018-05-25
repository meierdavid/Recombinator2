<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use app\models\Sequence;
use app\models\SequenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Logic;
use app\components\Veritas;
use app\components\VeritasLogic;
use app\components\MinimalDisjunctiveForm;
use app\components\Word;
use app\components\VeritasWord;
use app\components\Semantic;
use app\components\Bitset;

/**
 * SequenceController implements the CRUD actions for Sequence model.
 */
class SequenceController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all Sequence models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SequenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sequence model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sequence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Sequence();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_sequence]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sequence model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_sequence]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sequence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionResult() {
        $searchModel = new SequenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (isset($_GET['fonction'])) {
            try {

                if (empty($_GET['fonction']))
                    throw new \exception('La fonction ne peut être vide');

                $fonction = trim(str_replace('-', '+', urldecode($_GET['fonction'])));

                $logic = new Logic($fonction);
                $veritas = new VeritasLogic($logic);

                setcookie("fonction", $fonction, time() + 365 * 24 * 3600);

                return $this->render('result', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'logic' => $logic,
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

                $logic = new Logic($fonction);
                $veritas = new VeritasLogic($logic);

                setcookie("dnf", $dnf, time() + 365 * 24 * 3600);

                return $this->render('result', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'logic' => $logic,
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

    public function actionInfo() {
        $searchModel = new SequenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('phpinfo', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

   public function actionSearch_seq_treatment() {
        
        $searchModel = new SequenceSearch();
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
                $logic;
                $nbVariables;
                try {
                    $logic = new Logic($fonction);
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
                    $logic = new Logic($fonction); 
                }
                $veritas = new VeritasLogic($logic);
                $dnfArray[]= $veritas->getMinimalOutput();
                //supprime les doublons
                $dnfArray=array_unique($dnfArray);
                
                // Select all from Sequence Where  'ndf' = bindec($veritas->getMinimalOutput() and  
                // orderby weak_constraint DESC, length ASC
                
                //$searchResult = Sequence::find()->where('ndf > :ndf', [':ndf' => $dnfArray[0]])->orderBy('weak_constraint')->all();
                
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
                ->join("INNER JOIN", 'permutations_class', "sequence.permutations_class = permutations_class.permutation_class")
                ->join("INNER JOIN", 'dyck_functionnal_structure',"sequence.id_dick_functionnal_structure = dyck_functionnal_structure.id_dick_functionnal_structure")
                ->join("INNER JOIN", 'functions',"functions.permutations_class = permutations_class.permutation_class")
                ->where(['ndf' => $dnfArray[0]])
                ->all();
                //$searchResult2 = Sequence::find()->orderBy('weak_constraint')->joinWith('Permutassion_class')->joinWith('Functions')->where('ndf > :ndf', [':ndf' => $dnfArray[0]]);
                /*
                $searchResult3 = (new \yii\db\Query())
                ->select(['dyck_functionnal_structure', 'semantics','nb_inputs', 'length','nb_genes', 'gene_at_ends','nb_parts', 'nb_excisions','nb_inversions', 'weak_constraint','strong_constraint'])
                ->from('sequence','permutation_class','Semantics','dyck_functionnal_structure','functions')
                ->where(['ndf' => $dnfArray[0]])->all();*/
                   // chaine de 01 avec la fonction get minimal output
                //$wordsManager = new WordsManager($bdd);
                //$logicManager = new LogicManager($bdd);

                /* $pagination = new Pagination(30, $wordsManager->getNombre($logicManager->getLogic(
                  ['ndf',bindec($veritas->getMinimalOutput ()),DB::SQL_AND,'nb_inputs',$veritas->getMinimalNbInputs()])->getId_fn()),
                  'listSeq.php?output='.$veritas->getMinimalOutput ()."&amp;nbInputs=".$veritas->getMinimalNbInputs());
                  if (isset($_GET['page'])) $pagination->setPageActuelle($_GET['page']);
                  $pagination->setPremier(false); */

                //Requête SQL pour récuperer la liste des séquences
                
                //$liste = $wordsManager->getListe($pagination, ['ndf', bindec($veritas->getMinimalOutput()), DB::SQL_AND, 'nb_inputs', $veritas->getMinimalNbInputs()], array(['champ' => 'weak_constraint', 'sens' => DB::ORDRE_DESC], ['champ' => 'length', 'sens' => DB::ORDRE_ASC]));

                /* $tpl->assign(array(
                  'listeSequence' => $liste,
                  'pages' => $pagination->getPages()));

                  $tpl->display('listSeq.html'); */

                return $this->render('result', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $searchResult3,
                            'logic' => $logic,
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

    // -----------------------------------SearchSeq
    // recupère la variable proposition. 
    // proposition : fonction qui implémente les sequence qu'on cherche
    // Logic / Logic Manager / VeritasLogic / WordsManager dans Includes
    public function actionSearch_seq() {
        $model = new Sequence();

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

    public function actionInter_seq_res() {
        if (isset($_GET['sequence'])) {
            try {
                if (empty($_GET['sequence']))
                    throw new \exception(t('La séquence ne peut être vide'));

                $word = new Word(urldecode($_GET['sequence']));
                $word->exceptionsIfInvalid();

                setcookie("sequence", urldecode($_GET['sequence']), time() + 365 * 24 * 3600);
                $veritas = new VeritasWord($word);

                return $this->render('detailView', [
                            'word' => $word,
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
    public function actionInter_seq() {
        $model = new Sequence();

        if (isset($_POST['Sequence']['proposition'])) {

            // requete SQL A VOIR AVEC GUIGUI

            return $this->render('listeResult', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('interSeq', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Sequence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Sequence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Sequence::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

/*requête word manager : "SELECT s.id_s, sequence, weak_constraint, strong_constraint, length, word, names
			    FROM sequence s
			    JOIN implements i ON i.id_s=s.id_s
			    JOIN logical_functions lf ON lf.id_lf=i.id_lf 
			    JOIN sequence_features sf ON sf.id_sf=s.id_sf  
			    JOIN dyck_words dw ON dw.id_dw=s.id_dw
			    JOIN namings n ON n.id_n=i.id_n
			    "*/