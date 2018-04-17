<?php

namespace app\controllers;

use Yii;
use app\models\Sequence;
use app\models\SequenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SequenceController implements the CRUD actions for Sequence model.
 */
class SequenceController extends Controller
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
     * Lists all Sequence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SequenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sequence model.
     * @param integer $id_dick_functionnal_structure
     * @param integer $id_semantics
     * @return mixed
     */
    public function actionView($id_dick_functionnal_structure, $id_semantics)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_dick_functionnal_structure, $id_semantics),
        ]);
    }

    /**
     * Creates a new Sequence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sequence();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_dick_functionnal_structure' => $model->id_dick_functionnal_structure, 'id_semantics' => $model->id_semantics]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sequence model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_dick_functionnal_structure
     * @param integer $id_semantics
     * @return mixed
     */
    public function actionUpdate($id_dick_functionnal_structure, $id_semantics)
    {
        $model = $this->findModel($id_dick_functionnal_structure, $id_semantics);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_dick_functionnal_structure' => $model->id_dick_functionnal_structure, 'id_semantics' => $model->id_semantics]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sequence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_dick_functionnal_structure
     * @param integer $id_semantics
     * @return mixed
     */
    public function actionDelete($id_dick_functionnal_structure, $id_semantics)
    {
        $this->findModel($id_dick_functionnal_structure, $id_semantics)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sequence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_dick_functionnal_structure
     * @param integer $id_semantics
     * @return Sequence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_dick_functionnal_structure, $id_semantics)
    {
        if (($model = Sequence::findOne(['id_dick_functionnal_structure' => $id_dick_functionnal_structure, 'id_semantics' => $id_semantics])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
