<?php use yii\helpers\Html;
use yii\grid\GridView;
?>
<h3>Details of the logical function</h3>
<div class="cadreh1">
Function :  <?php echo $logic->to_string() ?> <br />
Disjunctive form : <?php echo $veritas->getMinimalDisjunctiveForm()." (".$veritas->outputToString().")" ?> <br />
 <?php //if (!isset($notBottom)) ?>
Truth table : <br />
 <?php echo $veritas->toHTML()?>
</div> 

<?php
//var_dump($user);

 echo GridView::widget([
    'dataProvider' => $user,
    'filterModel' => $user,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'last_name',
        'first_name',
        'content',
        ['class' => 'yii\grid\ActionColumn'],
    ]
]); ?>