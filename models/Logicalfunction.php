<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logical_function".
 *
 * @property string $id_logical_function
 * @property integer $nb_inputs
 * @property integer $ndf
 * @property integer $id_permutation_class
 *
 * @property PermutationClasses $IdPermutationClass
 */
class Logicalfunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logical_function';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nb_inputs', 'ndf', 'id_permutation_class'], 'required'],
            [['nb_inputs', 'ndf', 'id_permutation_class'], 'integer'],
            [['id_permutation_class'], 'exist', 'skipOnError' => true, 'targetClass' => PermutationClasses::className(), 'targetAttribute' => ['id_permutation_class' => 'id_permutation_class']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_logical_function' => Yii::t('app', 'Id Logical Function'),
            'nb_inputs' => Yii::t('app', 'Nb Inputs'),
            'ndf' => Yii::t('app', 'Ndf'),
            'id_permutation_class' => Yii::t('app', 'Id Permutation Class'),
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
