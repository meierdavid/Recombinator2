<?php use yii\helpers\Html;
use yii\grid\GridView;
?>
<h3>Details of the boolean function</h3>
<div class="cadreh1">
Function :  <?php echo $booleanFunction->to_string() ?> <br />
Disjunctive form : <?php echo $veritas->getMinimalDisjunctiveForm()." (".$veritas->outputToString().")" ?> <br />
 <?php //if (!isset($notBottom)) ?>
Truth table : <br />
 <?php echo $veritas->toHTML()?>
</div> 

