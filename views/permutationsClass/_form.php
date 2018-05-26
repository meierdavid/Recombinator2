<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PermutationClass */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permutation-class-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'permutation_class')->textInput() ?>

    <?= $form->field($model, 'nb_inputs')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
