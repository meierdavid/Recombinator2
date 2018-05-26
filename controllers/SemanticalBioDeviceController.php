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
}
