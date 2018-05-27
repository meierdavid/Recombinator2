<?php 

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin(
	['action' => 
		Url::to(
			['semantical-bio-device/search_sbd_treatment', 
			'form' => $form, 
			'data' => $dataForm])]); 
?>
<div class="sequence-form">
Number of genes: <br />
	&nbsp;&nbsp;&nbsp;<label for="minNb_genes">Minimum: </label><input type="number" value="0" name="minNb_genes" />
	&nbsp;&nbsp;&nbsp;<label for="maxNb_genes">Maximum: </label><input type="number" value="99" name="maxNb_genes" />
	<br />
Number of parts: <br />
	&nbsp;&nbsp;&nbsp;<label for="minNb_parts">Minimum: </label><input type="number" value="0" name="minNb_parts" />
	&nbsp;&nbsp;&nbsp;<label for="maxNb_parts">Maximum: </label><input type="number" value="99" name="maxNb_parts" />
	<br />
Length: <br />
	&nbsp;&nbsp;&nbsp;<label for="minLength">Minimum: </label><input type="number" value="0" name="minLength" />
	&nbsp;&nbsp;&nbsp;<label for="maxLength">Maximum: </label><input type="number" value="99999" name="maxLength" />
	<br />
<input type="checkbox" value="0" name="strong_constraint" /><label for="strong_constraint"> &nbsp;&nbsp;&nbsp;Strong constraint</label><br />
<input type="checkbox" value="0" name="weak_constraint" /><label for="weak_constraint"> &nbsp;&nbsp;&nbsp;Weak constraint</label><br />
<input type="checkbox" value="0" name="gene_at_ends" /><label for="gene_at_ends"> &nbsp;&nbsp;&nbsp;Gene at ends</label><br />
    
        <div class="help-block"></div>
</div>

    <div class="form-group">
       <?= Html::submitButton( 'filter' ,['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php


 echo GridView::widget([
    'dataProvider' => $data,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
		['attribute' => 'architecture',
			'format' => 'raw'],
        'dnf',
        'nb_genes',
        'nb_parts',
        ['attribute' => 'gene_at_ends', 'value' => function($data){ if ($data['gene_at_ends']) return 'yes'; return 'no'; }],
        ['attribute' => 'weak_constraint', 'value' => function($data){ if ($data['weak_constraint']) return 'respected'; return 'violated'; }],
        ['attribute' => 'strong_constraint', 'value' => function($data){ if ($data['strong_constraint']) return 'respected'; return 'violated'; }],
        'length',
        'nb_inputs',
        ['class' => 'yii\grid\ActionColumn',
        'header'=>'Actions',
        'template' => '{view}',
        'buttons' => [

            //view button
            'view' => function ($url, $model) {
                return Html::a('<span class="fa fa-search"></span>View', 
                "index.php?r=semantical-bio-device%2Fview&id_dyck_functionnal_structure=".$model["id_dyck_functionnal_structure"].
					"&id_semantics=".$model["id_semantics"].
					"&dnf=".$model["dnf"], 
				[
                            'title' => Yii::t('app', 'View'),
                            'class'=>'btn btn-primary btn-xs',                                  
                ]);
            },
        ],
    

         ],
    ]
]); 
?>  