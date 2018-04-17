<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Semantics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semantics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'semantics')->textInput() ?>

    <?= $form->field($model, 'length')->textInput() ?>

    <?= $form->field($model, 'nb_genes')->textInput() ?>

    <?= $form->field($model, 'nb_parts')->textInput() ?>

    <?= $form->field($model, 'gene_at_ends')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
