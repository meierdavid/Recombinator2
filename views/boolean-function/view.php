<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Booleanfunction */

$this->title = $model->dnf;
$this->params['breadcrumbs'][] = ['label' => 'Booleanfunctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booleanfunction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->dnf], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->dnf], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dnf',
            'permutation_class',
        ],
    ]) ?>

</div>
