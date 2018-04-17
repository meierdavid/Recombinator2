<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sequences */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Sequences',
]) . $model->id_sequence;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sequences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_sequence, 'url' => ['view', 'id' => $model->id_sequence]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sequences-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
