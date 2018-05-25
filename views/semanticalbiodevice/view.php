<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SemanticalBioDevice */

$this->title = $model->id_dick_functionnal_structure;
$this->params['breadcrumbs'][] = ['label' => 'Semantical Bio Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semantical-bio-device-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_dick_functionnal_structure' => $model->id_dick_functionnal_structure, 'id_semantics' => $model->id_semantics], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_dick_functionnal_structure' => $model->id_dick_functionnal_structure, 'id_semantics' => $model->id_semantics], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'permutations_class',
            'weak_constraint:boolean',
            'strong_constraint:boolean',
            'id_dick_functionnal_structure',
            'id_semantics',
        ],
    ]) ?>

</div>
