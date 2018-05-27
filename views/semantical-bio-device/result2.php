<?php use yii\helpers\Html;
use yii\grid\GridView;



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
                "index.phpr=semantical-bio-device%2Fview?id_dyck_functionnal_structure=".$model["id_dyck_functionnal_structure"].
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