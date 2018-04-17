<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dyck_functionnal_structure".
 *
 * @property integer $id_dick_functionnal_structure
 * @property string $dick_functionnal_structure
 * @property integer $nb_excisions
 * @property integer $nb_inversions
 *
 * @property Sequence[] $sequences
 * @property Semantics[] $idSemantics
 */
class DyckFunctionnalStructure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dyck_functionnal_structure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nb_excisions', 'nb_inversions'], 'integer'],
            [['dick_functionnal_structure'], 'string', 'max' => 18],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_dick_functionnal_structure' => Yii::t('app', 'Id Dick Functionnal Structure'),
            'dick_functionnal_structure' => Yii::t('app', 'Dick Functionnal Structure'),
            'nb_excisions' => Yii::t('app', 'Nb Excisions'),
            'nb_inversions' => Yii::t('app', 'Nb Inversions'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSequences()
    {
        return $this->hasMany(Sequence::className(), ['id_dick_functionnal_structure' => 'id_dick_functionnal_structure']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSemantics()
    {
        return $this->hasMany(Semantics::className(), ['id_semantics' => 'id_semantics'])->viaTable('sequence', ['id_dick_functionnal_structure' => 'id_dick_functionnal_structure']);
    }
}
