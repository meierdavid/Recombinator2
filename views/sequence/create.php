<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sequence */

$this->title = 'Create Sequence';
$this->params['breadcrumbs'][] = ['label' => 'Sequences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
