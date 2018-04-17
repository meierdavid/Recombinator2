<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SequencesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sequences-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_sequence') ?>

    <?= $form->field($model, 'semantics') ?>

    <?= $form->field($model, 'functional_structure') ?>

    <?= $form->field($model, 'weak_constraint') ?>

    <?= $form->field($model, 'strong_constraint') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'nb_genes') ?>

    <?php // echo $form->field($model, 'genes_at_ends') ?>

    <?php // echo $form->field($model, 'id_permutation_class') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
