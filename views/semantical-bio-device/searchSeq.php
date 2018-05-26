
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sequence */
/* @var $form yii\widgets\ActiveForm */
?>

<script src="./js/ajax.js"></script>
<div class="sequence-form">

    <?php $form = ActiveForm::begin([
'action' => ['semantical-bio-device/search_sbd_treatment']
]); ?>
    <input type="radio" id="wellFormedFormula"
           name="form" value="wellFormedFormula" checked>
    <label for="contactChoice1">well-Formed Formula</label>
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" id="BinaryNumber"
     name="form" value="BinaryNumber" >
    <label for="contactChoice2">Binary Number</label>
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" id="MultipleFunction"
     name="form" value="MultipleFunction" >
    <label for="contactChoice3">Multiple Function</label>
    
        <div id="wff" class="form-group field-sequence-proposition required">
        <label class="control-label" for="sequence-proposition">well-Formed Formula</label>
        <input type="text" id="sequence-proposition" class="form-control" name="Sequence[proposition]" aria-required="true" onkeyup="chargeAjax('#resultat', 'index.php?r=semantical-bio-device%2Fresult&fonction='+encodeURI($('#sequence-proposition').val().replace(/\+/g, '-')));">
        <div class="help-block"></div>
        </div>
    
        <div id="bn" class="form-group field-sequence-proposition required" style="display: none">
        <label class="control-label" for="sequence-proposition">Binary Number</label>
        <input type="text" id="sequence-BinaryNumber" class="form-control" name="Sequence[BinaryNumber]" aria-required="true" onkeyup="chargeAjax('#resultat', 'index.php?r=semantical-bio-device%2Fresult&dnf='+$('#sequence-BinaryNumber').val());">

        <div class="help-block"></div>
        </div>
        
        <div id="mf" class="form-group field-sequence-proposition required" style="display: none"> 
            <label class="control-label" for="sequence-proposition">Multiple function</label>
            <textarea id="sequence-MultipleFunction" class="form-control" name="Sequence[MultipleFunction]" placeholder="Write one function per line"></textarea>    
            <div class="help-block"></div>
        </div>
    
    
        <div class="help-block"></div>
</div>

    <div class="form-group">
       <?= Html::submitButton( 'search' ,['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<div id="resultat">
</div> 
<img src="img/ajax-loader.gif" alt="loading" class="ajaxLoader" />

<script>
var even = document.getElementById("wellFormedFormula");
even.addEventListener("change",wff);
var even2 = document.getElementById("BinaryNumber");
even2.addEventListener("change",bn);
console.log("test");
var even3 = document.getElementById("MultipleFunction");
even3.addEventListener("change",mf);
function wff(){
    
    var id= document.getElementById("wellFormedFormula");
    if(id.checked==true){
        document.getElementById("wff").style.display = "block";
        document.getElementById("bn").style.display = "none";
        document.getElementById("mf").style.display = "none";
        document.getElementById("sequence-MultipleFunction").value = "";
        document.getElementById("sequence-BinaryNumber").value = "";
        document.getElementById("resultat").innerHTML = "";
    }
  
    
}

function bn(){
    var id= document.getElementById("BinaryNumber");
   
    if(id.checked==true){
        document.getElementById("bn").style.display = "block";
        document.getElementById("wff").style.display = "none";
        document.getElementById("mf").style.display = "none";
        document.getElementById("sequence-MultipleFunction").value = "";
        document.getElementById("sequence-proposition").value = "";
        document.getElementById("resultat").innerHTML = "";
    }
}
    
function mf(){
    var id= document.getElementById("MultipleFunction");
    console.log("nul");
    if(id.checked==true){
        document.getElementById("bn").style.display = "none";
        document.getElementById("wff").style.display = "none";
        document.getElementById("mf").style.display = "block";
        document.getElementById("sequence-proposition").value = "";
        document.getElementById("sequence-BinaryNumber").value = "";
        document.getElementById("resultat").innerHTML = "";
    }
}

    

</script> 