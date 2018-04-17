<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Logicalfunction */

$this->title = 'Update Logicalfunction: ' . $model->id_logical_function;
$this->params['breadcrumbs'][] = ['label' => 'Logicalfunctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_logical_function, 'url' => ['view', 'id' => $model->id_logical_function]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="logicalfunction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
