<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Logicalfunction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logicalfunction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nb_inputs')->textInput() ?>

    <?= $form->field($model, 'dnf')->textInput() ?>

    <?= $form->field($model, 'id_permutation_class')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
