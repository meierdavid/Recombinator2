<?php $this->registerCssFile("css/word.css");?>
<h3>Details of the sequence</h3>
<div class="cadreh1">
Sequence : <?php echo $word->toHTML() ?><br />
Text format : <?php echo $word->to_string() ?> <br />
Implemented function : <?php echo $veritas->getMinimalDisjunctiveForm();echo " ("; echo $veritas->outputToString(); echo ")"?><br/>
Size : <?php echo $word->getLength(); ?> bases <br />
Symmetric : <?php echo $word->getSymetric()->toHTML() ?> <br />
Truth table : <br />
<?php echo $veritas->toHTML(); ?>
<a class="minibouton" href="listSeq.php?output=<?php echo $veritas->getMinimalOutput(); ?>&amp;nbInputs=<?php echo $veritas->getMinimalNbInputs();?>"><span>See sequences implementing the same function.</span></a>
</div>
