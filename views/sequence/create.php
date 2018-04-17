<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sequence */

$this->title = Yii::t('app', 'Create Sequence');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sequence'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequence-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
