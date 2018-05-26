<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "boolean_function".
 *
 * @property integer $dnf
 * @property integer $permutation_class
 *
 * @property PermutationClass $permutationClass
 */
class Booleanfunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'boolean_function';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dnf'], 'required'],
            [['dnf', 'permutation_class'], 'integer'],
            [['permutation_class'], 'exist', 'skipOnError' => true, 'targetClass' => PermutationClass::className(), 'targetAttribute' => ['permutation_class' => 'permutation_class']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dnf' => Yii::t('app', 'Ndf'),
            'permutation_class' => Yii::t('app', 'Permutation Class'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermutationClass()
    {
        return $this->hasOne(PermutationClass::className(), ['permutation_class' => 'permutation_class']);
    }
}
