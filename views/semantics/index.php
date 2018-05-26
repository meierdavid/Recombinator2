<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SemanticsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Semantics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semantics-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Semantics', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_semantics',
            //'semantics',
            'length',
            'nb_genes',
            'nb_parts',
            // 'gene_at_ends:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
