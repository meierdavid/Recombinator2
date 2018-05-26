<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Booleanfunction */

$this->title = 'Create Booleanfunction';
$this->params['breadcrumbs'][] = ['label' => 'Booleanfunctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booleanfunction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
