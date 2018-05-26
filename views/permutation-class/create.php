<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Permutation_class */

$this->title = 'Create Permutation Class';
$this->params['breadcrumbs'][] = ['label' => 'Permutation Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permutation-class-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
