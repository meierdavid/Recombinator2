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
	</tr>
</table>
<br />
<?php echo $veritas->toHTML(); ?>
<a class="minibouton" href="index.php?r=semantical-bio-device%2Fsearch_sbd_treatment&amp;form=BinaryNumber&amp;data=<?php echo $veritas->getMinimalOutput(); ?>"><span>See architectures implementing the same function.</span></a>
<script>
document.getElementById("selectDnf").addEventListener("change", function(e)
{
	e.preventDefault();
	chargeAjax('#resultat', 'index.php?r=semantical-bio-device%2Finter_sbd_res&sequence='+encodeURI($('#sequence').val()+"&dnf="+e.target.value));
});
</script>
