<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sequences */
/* @var $form yii\widgets\ActiveForm */
?>

<script src="./js/ajax.js"></script>
<div class="sequences-form">

    <?php $form = ActiveForm::begin(); ?>
    <input type="radio" id="wellFormedFormula"
           name="form" value="email" checked>
    <label for="contactChoice1">well-Formed Formula</label>
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" id="BinaryNumber"
     name="form" value="telephone" >
    <label for="contactChoice2">Binary Number</label>

    
        <div id="wff" class="form-group field-sequences-proposition required">
        <label class="control-label" for="sequences-proposition">well-Formed Formula</label>
        <input type="text" id="sequences-proposition" class="form-control" name="Sequences[proposition]" aria-required="true" onkeyup="chargeAjax('#resultat', 'index.php?r=sequences%2Fresult&fonction='+encodeURI($('#sequences-proposition').val().replace(/\+/g, '-')));">
        <div class="help-block"></div>
        </div>
    
    <div id="bn" class="form-group field-sequences-proposition required" style="display: none">
<label class="control-label" for="sequences-proposition">Binary Number</label>
<input type="text" id="sequences-BinaryNumber" class="form-control" name="Sequences[BinaryNumber]" aria-required="true">

<div class="help-block"></div>
</div>
        <div class="help-block"></div>
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Search') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<div id="resultat">
</div> 
<img src="img/ajax-loader.gif" alt="{t('chargement')}" class="ajaxLoader" />


<h2>Résultats</h2>


<script>
var even = document.getElementById("wellFormedFormula");
even.addEventListener("change",wff);
var even2 = document.getElementById("BinaryNumber");
even2.addEventListener("change",bn);
console.log(even);
function wff(){
    console.log("test");
    var id= document.getElementById("wellFormedFormula");
    var idres= document.getElementById("resultat");
    console.log(id.checked);
    if(id.checked==true){
        document.getElementById("wff").style.display = "block";
        document.getElementById("bn").style.display = "none";
        idres.style.display="block";
    }
  
    
}

function bn(){
    var id= document.getElementById("BinaryNumber");
    var idres= document.getElementById("resultat");
    if(id.checked==true){
        document.getElementById("bn").style.display = "block";
        document.getElementById("wff").style.display = "none";
        idres.style.display="none";
    }

    
}
</script> 