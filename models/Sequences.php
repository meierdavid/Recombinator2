<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sequences".
 *
 * @property string $id_sequence
 * @property resource $semantics
 * @property string $functional_structure
 * @property integer $weak_constraint
 * @property integer $strong_constraint
 * @property integer $size
 * @property integer $nb_genes
 * @property integer $genes_at_ends
 * @property integer $id_permutation_class
 * @property integer $binary_number
 * @property integer $proposition
 * 
 * @property PermutationClasses $idPermutationClass
 */
class Sequences extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sequences';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semantics', 'functional_structure', 'weak_constraint', 'strong_constraint', 'size', 'nb_genes', 'genes_at_ends', 'id_permutation_class'], 'required'],
            [['weak_constraint', 'strong_constraint', 'size', 'nb_genes', 'genes_at_ends', 'id_permutation_class'], 'integer'],
            //[['binary_number'], 'integer', min([1])],
            [['semantics','proposition'], 'string', 'max' => 9],
            [['functional_structure','proposition'], 'string', 'max' => 12],
            [['id_permutation_class'], 'exist', 'skipOnError' => true, 'targetClass' => PermutationClasses::className(), 'targetAttribute' => ['id_permutation_class' => 'id_permutation_class']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sequence' => Yii::t('app', 'Id Sequence'),
            'semantics' => Yii::t('app', 'Semantics'),
            'functional_structure' => Yii::t('app', 'Functional Structure'),
            'weak_constraint' => Yii::t('app', 'Weak Constraint'),
            'strong_constraint' => Yii::t('app', 'Strong Constraint'),
            'size' => Yii::t('app', 'Size'),
            'nb_genes' => Yii::t('app', 'Nb Genes'),
            'genes_at_ends' => Yii::t('app', 'Genes At Ends'),
            'id_permutation_class' => Yii::t('app', 'Id Permutation Class'),
            'binary_number' => Yii::t('app', 'Binary Number'),
            'proposition' => Yii::t('app', 'Proposition')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPermutationClass()
    {
        return $this->hasOne(PermutationClasses::className(), ['id_permutation_class' => 'id_permutation_class']);
    }
}
