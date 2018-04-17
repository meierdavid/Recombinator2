<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Semantics */

$this->title = 'Create Semantics';
$this->params['breadcrumbs'][] = ['label' => 'Semantics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semantics-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
