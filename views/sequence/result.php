<h3>Détails de la fonction logique</h3>
<div class="cadreh1">
Fonction :  <?php echo $logic->to_string() ?> <br />
Forme disjonctive : <?php echo $veritas->getMinimalDisjunctiveForm()." (".$veritas->outputToString().")" ?> <br />
 <?php //if (!isset($notBottom)) ?>
Table de vérité : <br />
 <?php echo $veritas->toHTML()?>
</div> 
