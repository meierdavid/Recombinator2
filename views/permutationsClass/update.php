<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PermutationsClass */

$this->title = 'Update Permutations Class: ' . $model->permutation_class;
$this->params['breadcrumbs'][] = ['label' => 'Permutations Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->permutation_class, 'url' => ['view', 'id' => $model->permutation_class]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permutations-class-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
