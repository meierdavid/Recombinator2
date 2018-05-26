<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BooleanfunctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Booleanfunctions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booleanfunction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Booleanfunction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dnf',
            'permutation_class',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
