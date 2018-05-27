
<script src="./js/ajax.js"></script>
<div id="resultat">
<?php $this->registerCssFile("css/semanticalBioDevice.css");
use app\components\MinimalDisjunctiveForm;
?>
Implementable functions :
<select name="dnf" id="selectDnf">
	<?php foreach (array_unique($semanticalBioDevice->getImplementedFunctions()) as $names => $function) { ?>
 	<option value="<?php echo $function ?>" <?php if (trim($function) == trim($veritas->outputToString())) echo 'selected'; ?>>
		<?php echo (new MinimalDisjunctiveForm($function, log(strlen($function),2)))." (".$function.")"; ?>
		</option>
	<?php } ?>
</select>
<br />
<br />
<table>
	<tr>
		<td>Architecture </td><td> <?php echo $semanticalBioDevice->toHTML() ?></td>
	</tr><tr>
		<td>Text format </td><td> <?php echo $semanticalBioDevice->to_string() ?> </td>
	</tr><tr>
		<td>Boolean function (minimal form) </td><td> <?php echo $veritas->getMinimalDisjunctiveForm(); ?></td>
	</tr><tr>
		<td>Boolean function (binary form) </td><td> <?php echo $veritas->outputToString(); ?></td>
	</tr><tr>
		<td>Length </td><td> <?php echo $semanticalBioDevice->getLength(); ?> bases </td>
	</tr><tr>
		<td>Number of genes </td><td> <?php echo $semanticalBioDevice->getNb_genes(); ?> </td>
	</tr><tr>
		<td>Number of parts </td><td> <?php echo $semanticalBioDevice->getNb_parts(); ?> </td>
	</tr><tr>
		<td>Gene at ends </td><td> <?php echo $semanticalBioDevice->getGene_at_ends() ? 'yes' : 'no'; ?> </td>
	</tr><tr>
		<td>Weak constraint </td><td> <?php echo $semanticalBioDevice->getWeak_constraint() ? 'respected' : 'violated'; ?> </td>
	</tr><tr>
		<td>Strong constraint </td><td> <?php echo $semanticalBioDevice->getStrong_constraint() ? 'respected' : 'violated'; ?> </td>
	</tr>
</table>
<br />
<?php echo $veritas->toHTML(); ?>
<a class="minibouton" href="index.php?r=semantical-bio-device%2Fsearch_sbd_treatment&amp;form=BinaryNumber&amp;data=<?php echo $veritas->getMinimalOutput(); ?>"><span>See architectures implementing the same function.</span></a>
<script>
document.getElementById("selectDnf").addEventListener("change", function(e)
{
	e.preventDefault();
	chargeAjax('#resultat', 'index.php?r=semantical-bio-device%2Fview'+
							'&id_dyck_functionnal_structure=<?php echo $semanticalBioDevice->getId_dyck_functionnal_structure() ?>'+
							'&id_semantics=<?php echo $semanticalBioDevice->getId_semantics() ?>'+
							'&dnf='+e.target.value+
							'&ajax=1');
	console.log("lol");
});
</script>
</div>