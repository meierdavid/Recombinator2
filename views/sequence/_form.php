<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sequence */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sequence-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'permutations_class')->textInput() ?>

    <?= $form->field($model, 'weak_constraint')->checkbox() ?>

    <?= $form->field($model, 'strong_constraint')->checkbox() ?>

    <?= $form->field($model, 'id_dick_functionnal_structure')->textInput() ?>

    <?= $form->field($model, 'id_semantics')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
