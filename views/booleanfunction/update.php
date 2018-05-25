<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Booleanfunction */

$this->title = 'Update Booleanfunction: ' . $model->ndf;
$this->params['breadcrumbs'][] = ['label' => 'Booleanfunctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ndf, 'url' => ['view', 'id' => $model->ndf]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="booleanfunction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
