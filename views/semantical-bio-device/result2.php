<?php use yii\helpers\Html;
use yii\grid\GridView;



 echo GridView::widget([
    'dataProvider' => $data,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
		['attribute' => 'graphic_format',
			'format' => 'raw'],
        'dnf',
        'nb_genes',
        'nb_parts',
        ['attribute' => 'gene_at_ends', 'value' => function($data){ if ($data) return 'yes'; return 'no'; }],
        ['attribute' => 'weak_constraint', 'value' => function($data){ if ($data) return 'respected'; return 'violated'; }],
        ['attribute' => 'strong_constraint', 'value' => function($data){ if ($data) return 'respected'; return 'violated'; }],
        'length',
        'nb_inputs',
        ['class' => 'yii\grid\ActionColumn',
        'header'=>'Actions',
        'template' => '{view}',
        'buttons' => [

            //view button
            'view' => function ($url, $model) {
                return Html::a('<span class="fa fa-search"></span>View', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>'btn btn-primary btn-xs',                                  
                ]);
            },
        ],
    

         ],
    ]
]); 
?>  