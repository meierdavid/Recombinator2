<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Dyck_functionnal_structure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dyck-functionnal-structure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dyck_functionnal_structure')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nb_excisions')->textInput() ?>

    <?= $form->field($model, 'nb_inversions')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
