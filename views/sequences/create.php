<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sequences */

$this->title = Yii::t('app', 'Create Sequences');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sequences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sequences-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
