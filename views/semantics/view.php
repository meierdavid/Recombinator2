<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Semantics */

$this->title = $model->id_semantics;
$this->params['breadcrumbs'][] = ['label' => 'Semantics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semantics-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_semantics], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_semantics], [
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
            'id_semantics',
            'semantics',
            'length',
            'nb_genes',
            'nb_parts',
            'gene_at_ends:boolean',
        ],
    ]) ?>

</div>
