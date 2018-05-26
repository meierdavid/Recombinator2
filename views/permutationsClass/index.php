<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PermutationClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permutation Classes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permutation-class-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Permutation Class', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'permutation_class',
            'nb_inputs',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
