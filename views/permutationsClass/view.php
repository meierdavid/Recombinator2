<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PermutationsClass */

$this->title = $model->permutation_class;
$this->params['breadcrumbs'][] = ['label' => 'Permutations Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permutations-class-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->permutation_class], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->permutation_class], [
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
            'permutation_class',
            'nb_inputs',
        ],
    ]) ?>

</div>
