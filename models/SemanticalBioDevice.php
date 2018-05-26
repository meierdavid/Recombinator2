<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "semantical_bio_device".
 *
 * @property integer $permutation_class
 * @property boolean $weak_constraint
 * @property boolean $strong_constraint
 * @property integer $id_dyck_functionnal_structure
 * @property integer $id_semantics
 *
 * @property Comment[] $comments
 * @property DyckFunctionnalStructure $idDickFunctionnalStructure
 * @property PermutationClass $permutationClass
 * @property Semantics $idSemantics
 * @property string $graphic_format
 */
class SemanticalBioDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'semantical_bio_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['graphic_format'], 'safe'],
            [['permutation_class'], 'required'],
            [['permutation_class'], 'integer'],
            [['weak_constraint', 'strong_constraint'], 'boolean'],
            [['id_dyck_functionnal_structure'], 'exist', 'skipOnError' => true, 'targetClass' => DyckFunctionnalStructure::className(), 'targetAttribute' => ['id_dyck_functionnal_structure' => 'id_dyck_functionnal_structure']],
            [['permutation_class'], 'exist', 'skipOnError' => true, 'targetClass' => PermutationClass::className(), 'targetAttribute' => ['permutation_class' => 'permutation_class']],
            [['id_semantics'], 'exist', 'skipOnError' => true, 'targetClass' => Semantics::className(), 'targetAttribute' => ['id_semantics' => 'id_semantics']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permutation_class' => Yii::t('app', 'Permutation Class'),
            'graphic_format' => Yii::t('app', 'Graphic format'),
            'weak_constraint' => Yii::t('app', 'Weak Constraint'),
            'strong_constraint' => Yii::t('app', 'Strong Constraint'),
            'id_dyck_functionnal_structure' => Yii::t('app', 'Id Dick Functionnal Structure'),
            'id_semantics' => Yii::t('app', 'Id Semantics'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_dyck_functionnal_structure' => 'id_dyck_functionnal_structure', 'id_semantics' => 'id_semantics']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDickFunctionnalStructure()
    {
        return $this->hasOne(DyckFunctionnalStructure::className(), ['id_dyck_functionnal_structure' => 'id_dyck_functionnal_structure']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermutationClass()
    {
        return $this->hasOne(PermutationClass::className(), ['permutation_class' => 'permutation_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSemantics()
    {
        return $this->hasOne(Semantics::className(), ['id_semantics' => 'id_semantics']);
    }
}
