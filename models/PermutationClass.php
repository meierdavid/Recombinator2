<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permutation_class".
 *
 * @property integer $permutation_class
 * @property integer $nb_inputs
 *
 * @property Functions[] $functions
 * @property Sequence[] $sequences
 */
class PermutationClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permutation_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permutation_class'], 'required'],
            [['permutation_class', 'nb_inputs'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permutation_class' => Yii::t('app', 'Permutation Class'),
            'nb_inputs' => Yii::t('app', 'Nb Inputs'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFunctions()
    {
        return $this->hasMany(Functions::className(), ['permutation_class' => 'permutation_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSequences()
    {
        return $this->hasMany(Sequence::className(), ['permutation_class' => 'permutation_class']);
    }
}
