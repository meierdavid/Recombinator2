<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sequence".
 *
 * @property integer $permutations_class
 * @property boolean $weak_constraint
 * @property boolean $strong_constraint
 * @property integer $id_dick_functionnal_structure
 * @property integer $id_semantics
 *
 * @property Comment[] $comments
 * @property DyckFunctionnalStructure $idDickFunctionnalStructure
 * @property PermutationsClass $permutationsClass
 * @property Semantics $idSemantics
 */
class Sequence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sequence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permutations_class'], 'required'],
            [['permutations_class'], 'integer'],
            [['weak_constraint', 'strong_constraint'], 'boolean'],
            [['id_dick_functionnal_structure'], 'exist', 'skipOnError' => true, 'targetClass' => DyckFunctionnalStructure::className(), 'targetAttribute' => ['id_dick_functionnal_structure' => 'id_dick_functionnal_structure']],
            [['permutations_class'], 'exist', 'skipOnError' => true, 'targetClass' => PermutationsClass::className(), 'targetAttribute' => ['permutations_class' => 'permutation_class']],
            [['id_semantics'], 'exist', 'skipOnError' => true, 'targetClass' => Semantics::className(), 'targetAttribute' => ['id_semantics' => 'id_semantics']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permutations_class' => Yii::t('app', 'Permutations Class'),
            'weak_constraint' => Yii::t('app', 'Weak Constraint'),
            'strong_constraint' => Yii::t('app', 'Strong Constraint'),
            'id_dick_functionnal_structure' => Yii::t('app', 'Id Dick Functionnal Structure'),
            'id_semantics' => Yii::t('app', 'Id Semantics'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_dick_functionnal_structure' => 'id_dick_functionnal_structure', 'id_semantics' => 'id_semantics']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDickFunctionnalStructure()
    {
        return $this->hasOne(DyckFunctionnalStructure::className(), ['id_dick_functionnal_structure' => 'id_dick_functionnal_structure']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermutationsClass()
    {
        return $this->hasOne(PermutationsClass::className(), ['permutation_class' => 'permutations_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSemantics()
    {
        return $this->hasOne(Semantics::className(), ['id_semantics' => 'id_semantics']);
    }
}
