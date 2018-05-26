<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SemanticalBioDeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Semantical Bio Devices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semantical-bio-device-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Semantical Bio Device', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'permutation_class',
            'weak_constraint:boolean',
            'strong_constraint:boolean',
            'id_dyck_functionnal_structure',
            'id_semantics',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
