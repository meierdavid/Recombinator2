<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "boolean_function".
 *
 * @property integer $ndf
 * @property integer $permutations_class
 *
 * @property PermutationsClass $permutationsClass
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
            [['ndf'], 'required'],
            [['ndf', 'permutations_class'], 'integer'],
            [['permutations_class'], 'exist', 'skipOnError' => true, 'targetClass' => PermutationsClass::className(), 'targetAttribute' => ['permutations_class' => 'permutation_class']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ndf' => Yii::t('app', 'Ndf'),
            'permutations_class' => Yii::t('app', 'Permutations Class'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermutationsClass()
    {
        return $this->hasOne(PermutationsClass::className(), ['permutation_class' => 'permutations_class']);
    }
}
