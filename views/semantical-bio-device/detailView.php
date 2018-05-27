<?php $this->registerCssFile("css/semanticalBioDevice.css");?>
<h3>Details of the sequence</h3>
<div class="cadreh1">
Sequence : <?php echo $semanticalBioDevice->toHTML() ?><br />
Text format : <?php echo $semanticalBioDevice->to_string() ?> <br />
Implemented function : <?php echo $veritas->getMinimalDisjunctiveForm();echo " ("; echo $veritas->outputToString(); echo ")"?><br/>
Size : <?php echo $semanticalBioDevice->getLength(); ?> bases <br />
Symmetric : <?php echo $semanticalBioDevice->getSymetric()->toHTML() ?> <br />
Truth table : <br />
<?php echo $veritas->toHTML(); ?>
<a class="minibouton" href="index.php?r=semantical-bio-device%2Fsearch_sbd_treatment&amp;form=BinaryNumber&amp;data=<?php echo $veritas->getMinimalOutput(); ?>"><span>See sequences implementing the same function.</span></a>
</div>
