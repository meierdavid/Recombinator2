<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Permutationclasses */

$this->title = 'Update Permutationclasses: ' . $model->id_permutation_class;
$this->params['breadcrumbs'][] = ['label' => 'Permutationclasses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_permutation_class, 'url' => ['view', 'id' => $model->id_permutation_class]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permutationclasses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
