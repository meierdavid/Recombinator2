<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Permutationclasses */

$this->title = 'Create Permutationclasses';
$this->params['breadcrumbs'][] = ['label' => 'Permutationclasses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permutationclasses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
