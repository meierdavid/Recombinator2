<?php use yii\helpers\Html;
use yii\grid\GridView;



 echo GridView::widget([
    'dataProvider' => $data,
    'filterModel' => $data,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'dnf',
        'nb_genes',
        'weak_constraint',
        'nb_inputs',
    ]
]); ?>