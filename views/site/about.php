<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'A propos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Description de l'application web :
    </p>

    <code><?= __FILE__ ?> </code>
</div>
