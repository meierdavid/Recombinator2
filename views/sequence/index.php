<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SequenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sequence');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Sequence'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Search Sequence'), ['search_seq'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Interprete Sequence'), ['inter_seq'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_sequence',
            'semantics',
            'functional_structure',
            'weak_constraint',
            'strong_constraint',
            // 'size',
            // 'nb_genes',
            // 'genes_at_ends',
            // 'id_permutation_class',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    

