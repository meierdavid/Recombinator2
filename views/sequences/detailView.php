<?php $this->registerCssFile("css/word.css");?>
<h3>Détails de la séquence</h3>
<div class="cadreh1">
Séquence : <?php echo $word->toHTML() ?><br />
Format texte : <?php echo $word->to_string() ?> <br />
Fonction implémentée : <?php echo $veritas->getMinimalDisjunctiveForm(); echo $veritas->outputToString(); ?><br />
Taille : <?php echo $word->getLength(); ?> bases <br />
Symétrique : <?php echo $word->getSymetric()->toHTML() ?> <br />
Table de vérité : <br />
<?php echo $veritas->toHTML(); ?>
<a class="minibouton" href="listSeq.php?output=<?php echo $veritas->getMinimalOutput(); ?>&amp;nbInputs=<?php echo $veritas->getMinimalNbInputs();?>"><span>Voir les séquences implémentant la même fonction.</span></a>
</div>
