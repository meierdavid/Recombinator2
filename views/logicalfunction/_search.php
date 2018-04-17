<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LogicalfunctionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logicalfunction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_logical_function') ?>

    <?= $form->field($model, 'nb_inputs') ?>

    <?= $form->field($model, 'ndf') ?>

    <?= $form->field($model, 'id_permutation_class') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
