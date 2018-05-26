<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "semantics".
 *
 * @property integer $id_semantics
 * @property resource $semantics
 * @property integer $length
 * @property integer $nb_genes
 * @property integer $nb_parts
 * @property boolean $gene_at_ends
 *
 * @property Sequence[] $sequences
 * @property DyckFunctionnalStructure[] $idDickFunctionnalStructures
 */
class Semantics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'semantics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semantics'], 'string'],
            [['length', 'nb_genes', 'nb_parts'], 'integer'],
            [['gene_at_ends'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_semantics' => Yii::t('app', 'Id Semantics'),
            'semantics' => Yii::t('app', 'Semantics'),
            'length' => Yii::t('app', 'Length'),
            'nb_genes' => Yii::t('app', 'Nb Genes'),
            'nb_parts' => Yii::t('app', 'Nb Parts'),
            'gene_at_ends' => Yii::t('app', 'Gene At Ends'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSequences()
    {
        return $this->hasMany(Sequence::className(), ['id_semantics' => 'id_semantics']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDickFunctionnalStructures()
    {
        return $this->hasMany(DyckFunctionnalStructure::className(), ['id_dyck_functionnal_structure' => 'id_dyck_functionnal_structure'])->viaTable('sequence', ['id_semantics' => 'id_semantics']);
    }
}
