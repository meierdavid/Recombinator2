<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Logicalfunction */

$this->title = $model->id_logical_function;
$this->params['breadcrumbs'][] = ['label' => 'Logicalfunctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logicalfunction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_logical_function], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_logical_function], [
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
            'id_logical_function',
            'nb_inputs',
            'ndf',
            'id_permutation_class',
        ],
    ]) ?>

</div>
