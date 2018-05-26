<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogicalfunctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logicalfunctions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logicalfunction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Logicalfunction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_logical_function',
            'nb_inputs',
            'dnf',
            'id_permutation_class',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
