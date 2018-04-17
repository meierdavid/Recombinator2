<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sequence */

$this->title = 'Update Sequence: ' . $model->id_dick_functionnal_structure;
$this->params['breadcrumbs'][] = ['label' => 'Sequences', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dick_functionnal_structure, 'url' => ['view', 'id_dick_functionnal_structure' => $model->id_dick_functionnal_structure, 'id_semantics' => $model->id_semantics]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sequence-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
