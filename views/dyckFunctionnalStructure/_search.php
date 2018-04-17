<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DyckFunctionnalStructureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dyck-functionnal-structure-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_dick_functionnal_structure') ?>

    <?= $form->field($model, 'dick_functionnal_structure') ?>

    <?= $form->field($model, 'nb_excisions') ?>

    <?= $form->field($model, 'nb_inversions') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
