<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Dyck_functionnal_structure */

$this->title = $model->id_dyck_functionnal_structure;
$this->params['breadcrumbs'][] = ['label' => 'Dyck Functionnal Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dyck-functionnal-structure-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_dyck_functionnal_structure], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_dyck_functionnal_structure], [
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
            'id_dyck_functionnal_structure',
            'dyck_functionnal_structure',
            'nb_excisions',
            'nb_inversions',
        ],
    ]) ?>

</div>
