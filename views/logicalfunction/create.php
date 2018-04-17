<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Logicalfunction */

$this->title = 'Create Logicalfunction';
$this->params['breadcrumbs'][] = ['label' => 'Logicalfunctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logicalfunction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
