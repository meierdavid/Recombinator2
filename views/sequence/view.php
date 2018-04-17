<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sequence */

$this->title = $model->id_sequence;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sequence'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_sequence], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_sequence], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_sequence',
            'semantics',
            'functional_structure',
            'weak_constraint',
            'strong_constraint',
            'size',
            'nb_genes',
            'genes_at_ends',
            'id_permutation_class',
        ],
    ]) ?>

</div>
