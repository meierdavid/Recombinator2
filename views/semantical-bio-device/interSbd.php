
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sequence */
/* @var $form yii\widgets\ActiveForm */
?>

<script src="./js/ajax.js"></script>
<div class="sequence-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <h1>Intrepret a Sequence</h1>
    
    <div class="cadreh1">
        <form method="post" action="#" onsubmit="return false;">
            <label for="sequence">Enter an architecture</label>&nbsp;:&nbsp;<input type="text" class="post" style="width: 320px" id="sequence" name="sequence" />
             <input type="submit" name="submit" id="submit" class="btn btn-success" value="Interpret" class='mainoption'/>
            
        </form>
    </div> 
    <div id="resultat">
    </div>
   <script>
	document.getElementById("submit").addEventListener("click", function(e)
	{
		e.preventDefault();
		chargeAjax('#resultat', 'index.php?r=semantical-bio-device%2Finter_sbd_res&sequence='+encodeURI($('#sequence').val()));
	});
	document.getElementById("sequence").addEventListener("keyup", function()
	{
		chargeAjax('#resultat', 'index.php?r=semantical-bio-device%2Finter_sbd_res&sequence='+encodeURI($('#sequence').val()));
	});
   </script>
    

    <?php ActiveForm::end(); ?>
<div id="resultat">
</div> 
<img src="img/ajax-loader.gif" alt="{t('chargement')}" class="ajaxLoader" />
