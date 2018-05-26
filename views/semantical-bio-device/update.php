<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SemanticalBioDevice */

$this->title = 'Update Semantical Bio Device: ' . $model->id_dyck_functionnal_structure;
$this->params['breadcrumbs'][] = ['label' => 'Semantical Bio Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dyck_functionnal_structure, 'url' => ['view', 'id_dyck_functionnal_structure' => $model->id_dyck_functionnal_structure, 'id_semantics' => $model->id_semantics]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="semantical-bio-device-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
