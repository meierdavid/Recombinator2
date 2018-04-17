<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dyck_functionnal_structure */

$this->title = 'Update Dyck Functionnal Structure: ' . $model->id_dick_functionnal_structure;
$this->params['breadcrumbs'][] = ['label' => 'Dyck Functionnal Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dick_functionnal_structure, 'url' => ['view', 'id' => $model->id_dick_functionnal_structure]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dyck-functionnal-structure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
