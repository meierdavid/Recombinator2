<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SequenceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sequence-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'permutations_class') ?>

    <?= $form->field($model, 'weak_constraint')->checkbox() ?>

    <?= $form->field($model, 'strong_constraint')->checkbox() ?>

    <?= $form->field($model, 'id_dick_functionnal_structure') ?>

    <?= $form->field($model, 'id_semantics') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
