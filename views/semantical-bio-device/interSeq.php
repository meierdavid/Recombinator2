
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
            <label for="sequence">Enter a sequence</label>&nbsp;:&nbsp;<input type="text" class="post" style="width: 320px" id="sequence" name="sequence" onkeyup="chargeAjax('#resultat', 'index.php?r=sequence%2Finter_seq_res&sequence='+encodeURI($('#sequence').val()));" />
            
             <input type="submit" name="submit" class="btn btn-success" value="Interpret" onclick="chargeAjax('#resultat', 'index.php?r=sequence%2Finter_seq_res&sequence='+encodeURI($('#sequence').val())); return false;" class='mainoption'/>
            
        </form>
    </div> 
    <div id="resultat">
    </div>
   
    

    <?php ActiveForm::end(); ?>
<div id="resultat">
</div> 
<img src="img/ajax-loader.gif" alt="{t('chargement')}" class="ajaxLoader" />
