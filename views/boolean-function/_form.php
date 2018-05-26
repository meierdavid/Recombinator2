<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Booleanfunction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booleanfunction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dnf')->textInput() ?>

    <?= $form->field($model, 'permutation_class')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
