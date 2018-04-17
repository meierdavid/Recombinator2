<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SequenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sequences';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sequence', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'permutations_class',
            'weak_constraint:boolean',
            'strong_constraint:boolean',
            'id_dick_functionnal_structure',
            'id_semantics',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
