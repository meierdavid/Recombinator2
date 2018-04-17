<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sequence */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sequence-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'semantics')->textInput() ?>

    <?= $form->field($model, 'functional_structure')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weak_constraint')->textInput() ?>

    <?= $form->field($model, 'strong_constraint')->textInput() ?>

    <?= $form->field($model, 'size')->textInput() ?>

    <?= $form->field($model, 'nb_genes')->textInput() ?>

    <?= $form->field($model, 'genes_at_ends')->textInput() ?>

    <?= $form->field($model, 'id_permutation_class')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
