<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PermutationsClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permutations Classes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permutations-class-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Permutations Class', ['create'], ['class' => 'btn btn-success']) ?>
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
