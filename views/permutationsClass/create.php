<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PermutationsClass */

$this->title = 'Create Permutations Class';
$this->params['breadcrumbs'][] = ['label' => 'Permutations Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permutations-class-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
