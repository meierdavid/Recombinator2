<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Dyck_functionnal_structureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dyck-functionnal-structure-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_dyck_functionnal_structure') ?>

    <?= $form->field($model, 'dyck_functionnal_structure') ?>

    <?= $form->field($model, 'nb_excisions') ?>

    <?= $form->field($model, 'nb_inversions') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
