<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Dyck_functionnal_structureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dyck Functionnal Structures';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dyck-functionnal-structure-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dyck Functionnal Structure', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_dick_functionnal_structure',
            'dick_functionnal_structure',
            'nb_excisions',
            'nb_inversions',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
