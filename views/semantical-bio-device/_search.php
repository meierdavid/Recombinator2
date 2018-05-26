<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SemanticalBioDeviceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semantical-bio-device-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'permutation_class') ?>

    <?= $form->field($model, 'weak_constraint')->checkbox() ?>

    <?= $form->field($model, 'strong_constraint')->checkbox() ?>

    <?= $form->field($model, 'id_dyck_functionnal_structure') ?>

    <?= $form->field($model, 'id_semantics') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>