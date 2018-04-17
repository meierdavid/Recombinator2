<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SemanticsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semantics-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_semantics') ?>

    <?= $form->field($model, 'semantics') ?>

    <?= $form->field($model, 'length') ?>

    <?= $form->field($model, 'nb_genes') ?>

    <?= $form->field($model, 'nb_parts') ?>

    <?php // echo $form->field($model, 'gene_at_ends')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
