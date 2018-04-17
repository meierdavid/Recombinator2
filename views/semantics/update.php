<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Semantics */

$this->title = 'Update Semantics: ' . $model->id_semantics;
$this->params['breadcrumbs'][] = ['label' => 'Semantics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_semantics, 'url' => ['view', 'id' => $model->id_semantics]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="semantics-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
