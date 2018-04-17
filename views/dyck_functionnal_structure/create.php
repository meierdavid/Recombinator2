<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dyck_functionnal_structure */

$this->title = 'Create Dyck Functionnal Structure';
$this->params['breadcrumbs'][] = ['label' => 'Dyck Functionnal Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dyck-functionnal-structure-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
